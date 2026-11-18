<?php
/**
 * Suíte de Testes Automatizados - NexusRedes Infraestrutura
 * Executa testes unitários e de integração para validar:
 * - Validações de Nome, E-mail e Telefone Internacional E.164
 * - Sanitização e Prevenção de XSS e SQL Injection
 * - Proteção CSRF e Honeypot
 * - Resiliência contra dados manipulados
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/env.php';
require_once dirname(__DIR__) . '/includes/security.php';
require_once dirname(__DIR__) . '/includes/functions.php';

class TestSuite {
    private int $passed = 0;
    private int $failed = 0;
    private array $failures = [];

    public function assert(bool $condition, string $testName, string $failDetails = ''): void {
        if ($condition) {
            $this->passed++;
            echo "  \033[32m✔ PASS:\033[0m {$testName}\n";
        } else {
            $this->failed++;
            $msg = "  \033[31m✖ FAIL:\033[0m {$testName}" . ($failDetails ? " ({$failDetails})" : "");
            $this->failures[] = $msg;
            echo "{$msg}\n";
        }
    }

    public function run(): void {
        echo "\n=======================================================\n";
        echo " INICIANDO SUÍTE DE TESTES AUTOMATIZADOS (NEXUSREDES)\n";
        echo "=======================================================\n\n";

        $this->testEnvLoader();
        $this->testSecurityAndCsrf();
        $this->testXssSanitization();
        $this->testNameValidation();
        $this->testEmailValidation();
        $this->testInternationalPhoneValidation();
        $this->testSqlInjectionPayloads();
        $this->testDatabaseIntegration();

        echo "\n-------------------------------------------------------\n";
        echo " RESUMO DOS TESTES: \n";
        echo " Aprovados: {$this->passed}\n";
        echo " Falhas:    {$this->failed}\n";
        echo " Total:     " . ($this->passed + $this->failed) . "\n";
        echo "-------------------------------------------------------\n";

        if ($this->failed > 0) {
            echo "\nDetalhes das Falhas:\n";
            foreach ($this->failures as $f) {
                echo "{$f}\n";
            }
            exit(1);
        } else {
            echo "\n\033[32mTODOS OS TESTES FORAM CONCLUÍDOS COM SUCESSO!\033[0m\n\n";
        }
    }

    private function testEnvLoader(): void {
        echo "[1] Testando Carregamento de Variáveis de Ambiente (.env)\n";
        $appUrl = env('APP_URL');
        $this->assert(!empty($appUrl), "Leitura de APP_URL da configuração");
        $this->assert(env('NON_EXISTENT_VAR', 'default_val') === 'default_val', "Fallback de valor padrão quando variável não existe");
    }

    private function testSecurityAndCsrf(): void {
        echo "\n[2] Testando Segurança, Sessão e Proteção CSRF\n";
        $token = get_csrf_token();
        $this->assert(!empty($token) && strlen($token) === 64, "Geração de CSRF token com 64 caracteres hexadecimais");
        $this->assert(verify_csrf_token($token), "Validação bem-sucedida do token CSRF legítimo");
        $this->assert(!verify_csrf_token('token_falso_adulterado_12345'), "Rejeição de CSRF token falso / adulterado");
        $this->assert(!verify_csrf_token(''), "Rejeição de CSRF token vazio");

        // Honeypot
        $this->assert(is_honeypot_triggered(['website' => 'http://spam-bot.com']), "Honeypot detecta preenchimento por bot");
        $this->assert(!is_honeypot_triggered(['website' => '']), "Honeypot permite envio legítimo com campo vazio");
    }

    private function testXssSanitization(): void {
        echo "\n[3] Testando Prevenção contra Cross-Site Scripting (XSS)\n";
        $maliciousPayload = '<script>alert("XSS")</script>';
        $escaped = e($maliciousPayload);
        $this->assert(!str_contains($escaped, '<script>'), "Escapamento seguro de tags <script> com e()");

        $sanitizedString = sanitize_string('  <b>Texto em negrito com tags</b>  ');
        $this->assert($sanitizedString === 'Texto em negrito com tags', "Remoção de tags HTML e trim de espaços com sanitize_string()");
    }

    private function testNameValidation(): void {
        echo "\n[4] Testando Validações de Nome Completo\n";

        // Casos válidos
        $this->assert(validate_name('Carlos Eduardo Silveira')['valid'], "Nome válido comum aceito");
        $this->assert(validate_name('Ana Cláudia D\'Ávila')['valid'], "Nome com acentos e apóstrofo aceito");
        $this->assert(validate_name('Jean-Luc Picard')['valid'], "Nome composto com hífen aceito");

        // Casos inválidos
        $this->assert(!validate_name('')['valid'], "Rejeição de nome vazio");
        $this->assert(!validate_name('    ')['valid'], "Rejeição de nome apenas com espaços");
        $this->assert(!validate_name('Carlos')['valid'], "Rejeição de apenas o primeiro nome (exige nome e sobrenome)");
        $this->assert(!validate_name('A B')['valid'], "Rejeição de iniciais curtas menores que 3 caracteres");
        $this->assert(!validate_name('Carlos <script>alert(1)</script>')['valid'], "Rejeição de nome contendo caracteres HTML/XSS");
        $this->assert(!validate_name('Carlos123 456')['valid'], "Rejeição de nome contendo números");
    }

    private function testEmailValidation(): void {
        echo "\n[5] Testando Validações de E-mail\n";

        // Casos válidos
        $this->assert(validate_email_address('contato@nexusredes.com.br')['valid'], "E-mail corporativo .com.br aceito");
        $this->assert(validate_email_address('carlos.silveira+tag@gmail.com')['valid'], "E-mail com sub-endereçamento (+) aceito");

        // Casos inválidos
        $this->assert(!validate_email_address('')['valid'], "Rejeição de e-mail vazio");
        $this->assert(!validate_email_address('email_sem_arroba.com')['valid'], "Rejeição de e-mail sem @");
        $this->assert(!validate_email_address('email@sem_dominio')['valid'], "Rejeição de e-mail sem TLD / ponto no domínio");
        $this->assert(!validate_email_address('user@.com')['valid'], "Rejeição de e-mail com estrutura de domínio inválida");
        $this->assert(!validate_email_address('teste@dominio.com<script>')['valid'], "Rejeição de payload XSS injetado no e-mail");
    }

    private function testInternationalPhoneValidation(): void {
        echo "\n[6] Testando Validações de Telefone Internacional e Formatação E.164\n";

        // Brasil (+55) - Celular 11 dígitos com 9
        $brCell = validate_and_format_phone('+55', '(11) 98888-7777', 'br');
        $this->assert($brCell['valid'] && $brCell['e164'] === '+5511988887777', "Celular Brasil (SP 11) formatado em E.164 (+5511988887777)");

        // Brasil (+55) - Fixo 10 dígitos
        $brLandline = validate_and_format_phone('+55', '(21) 3456-7890', 'br');
        $this->assert($brLandline['valid'] && $brLandline['e164'] === '+552134567890', "Telefone fixo Brasil (RJ 21) formatado em E.164 (+552134567890)");

        // Brasil (+55) - DDD Inválido (ex: 00 ou 05)
        $brInvalidDDD = validate_and_format_phone('+55', '(00) 98888-7777', 'br');
        $this->assert(!$brInvalidDDD['valid'], "Rejeição de telefone com DDD inexistente no Brasil (00)");

        // Brasil (+55) - Celular de 11 dígitos sem iniciar com 9
        $brInvalidCellStart = validate_and_format_phone('+55', '(11) 78888-7777', 'br');
        $this->assert(!$brInvalidCellStart['valid'], "Rejeição de celular BR de 11 dígitos que não inicia com 9");

        // Estados Unidos (+1)
        $usPhone = validate_and_format_phone('+1', '202-555-0143', 'us');
        $this->assert($usPhone['valid'] && $usPhone['e164'] === '+12025550143', "Telefone internacional EUA (+1) formatado em E.164 (+12025550143)");

        // Portugal (+351)
        $ptPhone = validate_and_format_phone('+351', '912 345 678', 'pt');
        $this->assert($ptPhone['valid'] && $ptPhone['e164'] === '+351912345678', "Telefone internacional Portugal (+351) formatado em E.164 (+351912345678)");

        // DDI Inválido
        $invalidDdi = validate_and_format_phone('abc', '99999999', 'br');
        $this->assert(!$invalidDdi['valid'], "Rejeição de DDI malformado");
    }

    private function testSqlInjectionPayloads(): void {
        echo "\n[7] Testando Resiliência contra Tentativas de SQL Injection\n";

        $sqlPayloads = [
            "' OR '1'='1",
            "admin' --",
            "'; DROP TABLE inscricoes; --",
            "1' UNION SELECT 1, 'admin', 'pass' --",
            "Carlos Eduardo' OR 1=1 --"
        ];

        foreach ($sqlPayloads as $payload) {
            $nameValidation = validate_name($payload);
            // Nenhum payload de injeção SQL pode ser aceito como nome válido
            $this->assert(!$nameValidation['valid'], "Rejeição segura de payload SQL Injection no nome: '{$payload}'");
        }
    }

    private function testDatabaseIntegration(): void {
        echo "\n[8] Testando Mecanismo de Banco de Dados e Constraints com SQLite In-Memory\n";

        // Testamos as regras SQL da tabela inscricoes e constraints de unicidade
        try {
            $pdo = new PDO('sqlite::memory:');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $pdo->exec("
                CREATE TABLE inscricoes (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    nome TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    ddi TEXT NOT NULL,
                    telefone TEXT NOT NULL,
                    telefone_e164 TEXT NOT NULL UNIQUE,
                    servico_interesse TEXT DEFAULT NULL,
                    mensagem TEXT DEFAULT NULL,
                    ip_origem TEXT DEFAULT NULL,
                    user_agent TEXT DEFAULT NULL,
                    status TEXT NOT NULL DEFAULT 'novo',
                    data_inscricao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                )
            ");

            // 1. Inserir primeiro registro legítimo
            $stmt = $pdo->prepare("
                INSERT INTO inscricoes (nome, email, ddi, telefone, telefone_e164, data_inscricao)
                VALUES (:nome, :email, :ddi, :telefone, :telefone_e164, datetime('now'))
            ");
            $stmt->execute([
                ':nome' => 'Lucas Mendes',
                ':email' => 'lucas@empresa.com.br',
                ':ddi' => '+55',
                ':telefone' => '11999998888',
                ':telefone_e164' => '+5511999998888'
            ]);
            $this->assert((int)$pdo->lastInsertId() > 0, "Inserção com Prepared Statement realizada com sucesso");

            // 2. Tentar cadastrar o mesmo e-mail (deve falhar por constraint)
            $duplicateEmailCaught = false;
            try {
                $stmt->execute([
                    ':nome' => 'Outro Usuário',
                    ':email' => 'lucas@empresa.com.br',
                    ':ddi' => '+55',
                    ':telefone' => '11988887777',
                    ':telefone_e164' => '+5511988887777'
                ]);
            } catch (PDOException $e) {
                $duplicateEmailCaught = true;
            }
            $this->assert($duplicateEmailCaught, "Constraint impede cadastro duplicado de e-mail");

            // 3. Tentar cadastrar o mesmo telefone E.164 (deve falhar por constraint)
            $duplicatePhoneCaught = false;
            try {
                $stmt->execute([
                    ':nome' => 'Terceiro Usuário',
                    ':email' => 'outro@empresa.com.br',
                    ':ddi' => '+55',
                    ':telefone' => '11999998888',
                    ':telefone_e164' => '+5511999998888'
                ]);
            } catch (PDOException $e) {
                $duplicatePhoneCaught = true;
            }
            $this->assert($duplicatePhoneCaught, "Constraint impede cadastro duplicado de telefone E.164");

        } catch (\Throwable $e) {
            $this->assert(false, "Falha na simulação de banco de dados", $e->getMessage());
        }
    }
}

// Execução
$suite = new TestSuite();
$suite->run();
