<?php
/**
 * Teste Funcional dos Endpoints da API
 * Simula requisições POST para a camada de controle do formulário
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/env.php';
require_once dirname(__DIR__) . '/includes/security.php';
require_once dirname(__DIR__) . '/includes/functions.php';

echo "\n[9] Testando Endpoint da API (Simulação de Fluxos de Requisição)\n";

// 1. Simulação com Token CSRF Inválido
$tokenInvalido = 'csrf_invalido';
$validaCsrf = verify_csrf_token($tokenInvalido);
assert(!$validaCsrf, "CSRF inválido é bloqueado corretamente");
echo "  ✔ PASS: Bloqueio contra requisições com CSRF ausente ou forjado\n";

// 2. Simulação com Bot acionando Honeypot
$postBot = [
    'nome' => 'Robô Spammer',
    'email' => 'bot@spammer.com',
    'website' => 'http://spam-link.ru' // Honeypot preenchido
];
$isBot = is_honeypot_triggered($postBot, 'website');
assert($isBot, "Honeypot detectou com sucesso envio automatizado");
echo "  ✔ PASS: Honeypot desvia e neutraliza envios automatizados\n";

// 3. Simulação de Rate Limit
$rateLimitOk = check_rate_limit('test_api_limit', 3, 60);
assert($rateLimitOk, "Primeira tentativa dentro do limite permitida");
check_rate_limit('test_api_limit', 3, 60);
check_rate_limit('test_api_limit', 3, 60);
$rateLimitExceeded = !check_rate_limit('test_api_limit', 3, 60);
assert($rateLimitExceeded, "Quarta tentativa é bloqueada por Rate Limiting");
echo "  ✔ PASS: Rate Limiting por sessão / IP atuando com precisão\n";

echo "\n\033[32mSIMULAÇÃO DE API CONCLUÍDA COM SUCESSO!\033[0m\n\n";
