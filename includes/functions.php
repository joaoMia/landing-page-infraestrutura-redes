<?php
/**
 * Funções Auxiliares de Validação, Sanitização e Manipulação de Dados
 * Inclui validação avançada de telefone internacional compatível com normas ITU-T E.164 / libphonenumber.
 */

declare(strict_types=1);

// Carregar autoload do Composer se existir
$vendorAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}

/**
 * Sanitiza texto genérico removendo tags, quebras maliciosas e espaços excedentes
 */
function sanitize_string(?string $value): string {
    if ($value === null) {
        return '';
    }
    // Remove caracteres nulos e normaliza quebras de linha
    $cleaned = str_replace("\0", '', $value);
    // Remove tags HTML
    $cleaned = strip_tags($cleaned);
    // Remove espaços múltiplos
    $cleaned = preg_replace('/\s+/', ' ', $cleaned) ?? '';
    return trim($cleaned);
}

/**
 * Sanitiza e normaliza endereço de e-mail
 */
function sanitize_email(?string $email): string {
    if ($email === null) {
        return '';
    }
    $cleaned = trim($email);
    $sanitized = filter_var($cleaned, FILTER_SANITIZE_EMAIL);
    return is_string($sanitized) ? strtolower($sanitized) : '';
}

/**
 * Validação rigorosa de Nome Completo
 * Requisitos:
 * - Não vazio ou apenas espaços
 * - Mínimo de 3 e máximo de 100 caracteres
 * - Pelo menos duas palavras (Nome e Sobrenome)
 * - Aceita caracteres acentuados (UTF-8) e apóstrofos/hífens
 */
function validate_name(string $name): array {
    $name = trim($name);
    $len = mb_strlen($name, 'UTF-8');

    if ($len === 0) {
        return [
            'valid' => false,
            'message' => 'O nome completo é obrigatório.'
        ];
    }

    if ($len < 3 || $len > 100) {
        return [
            'valid' => false,
            'message' => 'O nome deve conter entre 3 e 100 caracteres.'
        ];
    }

    // Deve conter pelo menos duas palavras com no mínimo 2 caracteres cada
    $words = preg_split('/\s+/', $name);
    if (!$words || count($words) < 2) {
        return [
            'valid' => false,
            'message' => 'Por favor, informe seu nome e sobrenome.'
        ];
    }

    $validWordCount = 0;
    foreach ($words as $word) {
        if (mb_strlen($word, 'UTF-8') >= 2) {
            $validWordCount++;
        }
    }

    if ($validWordCount < 2) {
        return [
            'valid' => false,
            'message' => 'Por favor, informe um nome e sobrenome válidos (mínimo de 2 caracteres cada).'
        ];
    }

    // Expressão regular permitindo letras de qualquer alfabeto latino/acentuadas, apóstrofos e hífens
    if (!preg_match("/^[\p{L}][\p{L}\'\-\.\s]+[\p{L}]$/u", $name)) {
        return [
            'valid' => false,
            'message' => 'O nome contém caracteres inválidos. Utilize apenas letras.'
        ];
    }

    return [
        'valid' => true,
        'value' => $name
    ];
}

/**
 * Validação rigorosa de E-mail
 * Requisitos:
 * - Filtro nativo do PHP FILTER_VALIDATE_EMAIL
 * - Comprimento máximo seguro (150 caracteres)
 * - Estrutura de domínio plausível
 */
function validate_email_address(string $email): array {
    $email = strtolower(trim($email));

    if ($email === '') {
        return [
            'valid' => false,
            'message' => 'O e-mail é obrigatório.'
        ];
    }

    if (strlen($email) > 150) {
        return [
            'valid' => false,
            'message' => 'O e-mail informado excede o limite máximo de 150 caracteres.'
        ];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'valid' => false,
            'message' => 'Por favor, informe um endereço de e-mail válido.'
        ];
    }

    // Validação adicional de domínio
    $parts = explode('@', $email);
    if (count($parts) !== 2 || empty($parts[1]) || !str_contains($parts[1], '.')) {
        return [
            'valid' => false,
            'message' => 'O domínio do e-mail é inválido.'
        ];
    }

    return [
        'valid' => true,
        'value' => $email
    ];
}

/**
 * Validação e formatação de Telefone Internacional
 * Suporta libphonenumber oficial (se presente no autoload) com fallback robusto
 * que valida normas ITU-T E.164, prefixos DDI e regras do Brasil (+55).
 * 
 * @param string $ddi Código DDI do país (ex: '+55' ou '55')
 * @param string $phone Número nacional ou completo informado
 * @param string|null $countryIso Código ISO 2 do país (ex: 'br', 'us')
 * @return array{valid: bool, ddi?: string, phone?: string, e164?: string, message?: string}
 */
function validate_and_format_phone(string $ddi, string $phone, ?string $countryIso = 'br'): array {
    $ddi = trim($ddi);
    $phone = trim($phone);

    if ($phone === '') {
        return [
            'valid' => false,
            'message' => 'O número de telefone é obrigatório.'
        ];
    }

    // Normalizar DDI para sempre conter o prefixo '+'
    if (!str_starts_with($ddi, '+')) {
        $ddi = '+' . ltrim($ddi, '0');
    }

    // DDI deve ter formato + e de 1 a 4 dígitos
    if (!preg_match('/^\+[1-9]\d{0,3}$/', $ddi)) {
        return [
            'valid' => false,
            'message' => 'Código de país (DDI) inválido.'
        ];
    }

    // Extrair apenas dígitos do número telefônico nacional
    $nationalDigits = preg_replace('/\D/', '', $phone);

    // Se o usuário digitou o número com DDI embutido, remover o DDI do início do número nacional
    $ddiDigits = ltrim($ddi, '+');
    if (str_starts_with($nationalDigits, $ddiDigits) && strlen($nationalDigits) > 8) {
        $nationalDigits = substr($nationalDigits, strlen($ddiDigits));
    }

    // Se a libphonenumber oficial do Google estiver disponível via Composer
    if (class_exists('\libphonenumber\PhoneNumberUtil')) {
        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $country = strtoupper($countryIso ?: 'BR');
            $proto = $phoneUtil->parse($phone, $country);

            if ($phoneUtil->isValidNumber($proto)) {
                $e164Formatted = $phoneUtil->format($proto, \libphonenumber\PhoneNumberFormat::E164);
                $countryCode = '+' . $proto->getCountryCode();
                $nationalNumber = (string) $proto->getNationalNumber();

                return [
                    'valid' => true,
                    'ddi'   => $countryCode,
                    'phone' => $nationalNumber,
                    'e164'  => $e164Formatted
                ];
            }
        } catch (\Throwable $e) {
            // Continua para o fallback de validação interna
        }
    }

    // Fallback nativo: Validação por padrões ITU-T E.164 e regras nacionais
    if (empty($nationalDigits)) {
        return [
            'valid' => false,
            'message' => 'O telefone informado não contém dígitos válidos.'
        ];
    }

    // Regras específicas para o Brasil (+55)
    if ($ddi === '+55') {
        // Telefones brasileiros válidos: DDD (2 dígitos) + 8 dígitos (fixo) ou 9 dígitos (celular)
        // Total: 10 ou 11 dígitos
        $len = strlen($nationalDigits);
        if ($len < 10 || $len > 11) {
            return [
                'valid' => false,
                'message' => 'O telefone brasileiro deve conter DDD + 8 ou 9 dígitos (ex: 11 98888-7777).'
            ];
        }

        $ddd = (int) substr($nationalDigits, 0, 2);
        // Lista de DDDs válidos no Brasil
        $validDDDs = [
            11, 12, 13, 14, 15, 16, 17, 18, 19,
            21, 22, 24, 27, 28,
            31, 32, 33, 34, 35, 37, 38,
            41, 42, 43, 44, 45, 46, 47, 48, 49,
            51, 53, 54, 55,
            61, 62, 63, 64, 65, 66, 67, 68, 69,
            71, 73, 74, 75, 77, 79,
            81, 82, 83, 84, 85, 86, 87, 88, 89,
            91, 92, 93, 94, 95, 96, 97, 98, 99
        ];

        if (!in_array($ddd, $validDDDs, true)) {
            return [
                'valid' => false,
                'message' => "O DDD '{$ddd}' não é um código de área válido no Brasil."
            ];
        }

        // Se for celular (11 dígitos), o primeiro dígito do número deve ser 9
        if ($len === 11) {
            $primeiroDigito = $nationalDigits[2];
            if ($primeiroDigito !== '9') {
                return [
                    'valid' => false,
                    'message' => 'Celulares com 11 dígitos no Brasil devem iniciar com o dígito 9 após o DDD.'
                ];
            }
        } elseif ($len === 10) {
            // Se for fixo (10 dígitos), o primeiro dígito geralmente é entre 2 e 5
            $primeiroDigito = (int) $nationalDigits[2];
            if ($primeiroDigito < 2 || $primeiroDigito > 5) {
                return [
                    'valid' => false,
                    'message' => 'Telefone fixo com formato inválido para o Brasil.'
                ];
            }
        }
    } else {
        // Regras gerais internacionais E.164:
        // Número nacional geralmente tem entre 6 e 14 dígitos
        $len = strlen($nationalDigits);
        if ($len < 6 || $len > 14) {
            return [
                'valid' => false,
                'message' => 'O telefone informado não atende ao padrão internacional de comprimento.'
            ];
        }
    }

    $e164 = $ddi . $nationalDigits;

    // Norma E.164 determina comprimento máximo total de 15 dígitos (excluindo o '+')
    $totalDigits = strlen(ltrim($e164, '+'));
    if ($totalDigits < 8 || $totalDigits > 15) {
        return [
            'valid' => false,
            'message' => 'O telefone completo excede o padrão internacional E.164 (máx. 15 dígitos).'
        ];
    }

    return [
        'valid' => true,
        'ddi'   => $ddi,
        'phone' => $nationalDigits,
        'e164'  => $e164
    ];
}

/**
 * Retorna o IP do cliente respeitando privacidade e LGPD
 */
function get_client_ip(): string {
    $keys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ips = explode(',', (string) $_SERVER[$key]);
            foreach ($ips as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
    }

    return '127.0.0.1';
}

/**
 * Emite resposta JSON padronizada e encerra a execução
 */
function json_response(bool $success, string $message, array $errors = [], array $extra = [], int $httpCode = 200): void {
    // Limpar buffers anteriores se houver
    if (ob_get_length()) {
        ob_clean();
    }

    http_response_code($httpCode);
    header('Content-Type: application/json; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

    $payload = array_merge([
        'success'   => $success,
        'message'   => $message,
        'errors'    => $errors,
        'timestamp' => date('c')
    ], $extra);

    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
