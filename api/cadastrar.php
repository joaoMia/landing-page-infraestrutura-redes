<?php
/**
 * Endpoint de Processamento do Formulário de Inscrição / Lead
 * Validações rigorosas, proteção CSRF, Honeypot, mitigação de SQL Injection e prevenção de duplicidades.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/security.php';
require_once dirname(__DIR__) . '/includes/functions.php';

// Inicializar sessão segura
init_secure_session();

// 1. Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Método HTTP não permitido. Utilize POST.', [], [], 405);
}

// 2. Extrair dados da requisição (suporta FormData padrão e JSON)
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$inputData = [];

if (stripos($contentType, 'application/json') !== false) {
    $rawInput = file_get_contents('php://input');
    $decoded = json_decode($rawInput, true);
    if (is_array($decoded)) {
        $inputData = $decoded;
    }
} else {
    $inputData = $_POST;
}

// 3. Controle contra robôs (Honeypot)
// O campo 'website' é invisível para humanos. Se estiver preenchido, é um bot.
if (is_honeypot_triggered($inputData, 'website')) {
    // Simula atraso sutil e descarta silenciosamente
    usleep(500000);
    json_response(true, 'Solicitação processada com sucesso!', [], ['redirect' => 'sucesso.php']);
}

// 4. Rate Limiting por sessão / IP
if (!check_rate_limit('submit_lead', 8, 300)) {
    json_response(
        false, 
        'Limite de tentativas temporariamente excedido. Por favor, aguarde alguns minutos antes de tentar novamente.', 
        [], 
        [], 
        429
    );
}

// 5. Validação de CSRF Token
$receivedCsrfToken = $inputData['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
if (!verify_csrf_token($receivedCsrfToken)) {
    json_response(
        false, 
        'A sua sessão expirou ou o token de segurança é inválido. Por favor, recarregue a página.', 
        ['csrf' => 'Token de segurança inválido ou expirado.'], 
        [], 
        403
    );
}

// 6. Sanitização e Validação dos Campos
$errors = [];

// Nome completo
$rawNome = $inputData['nome'] ?? '';
$nomeValidation = validate_name(sanitize_string($rawNome));
if (!$nomeValidation['valid']) {
    $errors['nome'] = $nomeValidation['message'];
}

// E-mail
$rawEmail = $inputData['email'] ?? '';
$emailValidation = validate_email_address(sanitize_email($rawEmail));
if (!$emailValidation['valid']) {
    $errors['email'] = $emailValidation['message'];
}

// Telefone Internacional
$rawDdi = $inputData['ddi'] ?? '+55';
$rawPhone = $inputData['telefone'] ?? '';
$rawCountryIso = $inputData['pais_iso'] ?? 'br';

$phoneValidation = validate_and_format_phone(
    sanitize_string($rawDdi), 
    sanitize_string($rawPhone), 
    sanitize_string($rawCountryIso)
);

if (!$phoneValidation['valid']) {
    $errors['telefone'] = $phoneValidation['message'];
}

// Campos opcionais de contexto
$servicoInteresse = sanitize_string($inputData['servico_interesse'] ?? 'Geral / Infraestrutura de Redes');
$mensagem = sanitize_string($inputData['mensagem'] ?? '');

// Termo LGPD
$lgpdConsent = !empty($inputData['lgpd_consent']);
if (!$lgpdConsent) {
    $errors['lgpd_consent'] = 'É necessário concordar com o tratamento dos dados para podermos entrar em contato.';
}

// Se houver erros de validação no formulário, retorna 422
if (!empty($errors)) {
    json_response(
        false, 
        'Por favor, corrija os campos destacados no formulário.', 
        $errors, 
        [], 
        422
    );
}

// Dados validados
$nomeLimpo = $nomeValidation['value'];
$emailLimpo = $emailValidation['value'];
$ddiLimpo = $phoneValidation['ddi'];
$telefoneLimpo = $phoneValidation['phone'];
$telefoneE164 = $phoneValidation['e164'];
$clientIp = get_client_ip();
$userAgent = substr(sanitize_string($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido'), 0, 250);

// 7. Persistência no Banco de Dados com PDO e Prepared Statements
try {
    $pdo = Database::getConnection();

    // Verificação de duplicidade de e-mail
    $stmtCheckEmail = $pdo->prepare('SELECT id FROM inscricoes WHERE email = :email LIMIT 1');
    $stmtCheckEmail->execute([':email' => $emailLimpo]);
    if ($stmtCheckEmail->fetch()) {
        json_response(
            false, 
            'O e-mail informado já possui uma solicitação cadastrada.', 
            ['email' => 'Este e-mail já foi registrado anteriormente. Nossa equipe entrará em contato.'], 
            [], 
            409
        );
    }

    // Verificação de duplicidade de telefone E.164
    $stmtCheckPhone = $pdo->prepare('SELECT id FROM inscricoes WHERE telefone_e164 = :telefone_e164 LIMIT 1');
    $stmtCheckPhone->execute([':telefone_e164' => $telefoneE164]);
    if ($stmtCheckPhone->fetch()) {
        json_response(
            false, 
            'O telefone informado já possui uma solicitação cadastrada.', 
            ['telefone' => 'Este número de telefone já foi cadastrado anteriormente em nosso sistema.'], 
            [], 
            409
        );
    }

    // Inserção segura com parâmetros vinculados (Prevenção total contra SQL Injection)
    $sqlInsert = 'INSERT INTO inscricoes (
                    nome, 
                    email, 
                    ddi, 
                    telefone, 
                    telefone_e164, 
                    servico_interesse, 
                    mensagem, 
                    ip_origem, 
                    user_agent, 
                    data_inscricao
                  ) VALUES (
                    :nome, 
                    :email, 
                    :ddi, 
                    :telefone, 
                    :telefone_e164, 
                    :servico_interesse, 
                    :mensagem, 
                    :ip_origem, 
                    :user_agent, 
                    NOW()
                  )';

    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        ':nome'              => $nomeLimpo,
        ':email'             => $emailLimpo,
        ':ddi'               => $ddiLimpo,
        ':telefone'          => $telefoneLimpo,
        ':telefone_e164'     => $telefoneE164,
        ':servico_interesse' => $servicoInteresse,
        ':mensagem'          => $mensagem !== '' ? $mensagem : null,
        ':ip_origem'         => $clientIp,
        ':user_agent'        => $userAgent,
    ]);

    // Armazenar confirmação na sessão para renderização segura em sucesso.php
    $_SESSION['lead_sucesso'] = true;
    $_SESSION['lead_nome'] = $nomeLimpo;
    $_SESSION['lead_email'] = $emailLimpo;
    $_SESSION['lead_telefone'] = $telefoneE164;
    $_SESSION['lead_timestamp'] = time();

    // Renovar token CSRF após submissão bem-sucedida para evitar reutilização
    unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);

    json_response(
        true, 
        'Inscrição realizada com sucesso! Redirecionando para a página de confirmação...', 
        [], 
        ['redirect' => 'sucesso.php'], 
        200
    );

} catch (PDOException $e) {
    // Tratamento de duplicação por concorrência (código SQL 23000)
    if ($e->getCode() === '23000') {
        error_log('[Database Concurrency] Registro duplicado capturado pela constraint: ' . $e->getMessage());
        json_response(
            false, 
            'Já existe um cadastro com este e-mail ou telefone.', 
            ['email' => 'Os dados informados já constam em nosso banco de dados.'], 
            [], 
            409
        );
    }

    // Registra o erro no log interno do servidor sem vazar dados ao cliente
    error_log('[Database Error] Falha ao gravar lead: ' . $e->getMessage());

    json_response(
        false, 
        'Houve uma falha inesperada ao salvar sua solicitação. Por favor, tente novamente em instantes.', 
        ['geral' => 'Erro interno de processamento.'], 
        [], 
        500
    );
} catch (\Throwable $t) {
    error_log('[System Error] Exceção não tratada: ' . $t->getMessage());

    json_response(
        false, 
        'Ocorreu uma instabilidade momentânea. Por favor, tente novamente mais tarde.', 
        [], 
        [], 
        500
    );
}
