<?php
/**
 * Utilitários de Segurança e Proteção da Aplicação
 * Inclui: Sessões Seguras, Proteção CSRF, Honeypot, Prevenção XSS e Rate Limiting.
 */

declare(strict_types=1);

require_once __DIR__ . '/env.php';

/**
 * Inicializa a sessão com parâmetros rigorosos de segurança
 */
function init_secure_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        $sessionLifetime = (int) env('SESSION_LIFETIME', 3600);

        // Configurações de cookies de sessão (se os headers ainda não tiverem sido enviados)
        if (!headers_sent()) {
            session_set_cookie_params([
                'lifetime' => $sessionLifetime,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            @ini_set('session.use_strict_mode', '1');
            @ini_set('session.use_only_cookies', '1');
            @ini_set('session.cookie_httponly', '1');
        }

        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        // Regenerar ID periodicamente para prevenir Session Fixation
        if (!isset($_SESSION['session_created_at'])) {
            $_SESSION['session_created_at'] = time();
        } elseif (time() - $_SESSION['session_created_at'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['session_created_at'] = time();
        }
    }
}

/**
 * Gera e retorna um token CSRF criptograficamente seguro vinculado à sessão
 */
function get_csrf_token(): string {
    init_secure_session();

    if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time']) || (time() - $_SESSION['csrf_token_time'] > 7200)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }

    return $_SESSION['csrf_token'];
}

/**
 * Valida o token CSRF recebido contra o armazenado na sessão
 */
function verify_csrf_token(?string $token): bool {
    init_secure_session();

    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }

    // Utiliza hash_equals para mitigar timing attacks
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Verifica se o campo Honeypot foi preenchido por um bot automatizado
 */
function is_honeypot_triggered(array $input, string $field = 'website'): bool {
    return !empty($input[$field]);
}

/**
 * Escapamento seguro contra Cross-Site Scripting (XSS)
 */
function e(?string $value): string {
    if ($value === null) {
        return '';
    }
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Controle básico de taxa de requisições (Rate Limiting) por IP e Sessão
 */
function check_rate_limit(string $action = 'submit_lead', int $maxAttempts = 5, int $decaySeconds = 300): bool {
    init_secure_session();

    $now = time();
    $key = "rate_limit_{$action}";

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'count' => 1,
            'start' => $now
        ];
        return true;
    }

    $elapsed = $now - $_SESSION[$key]['start'];

    if ($elapsed > $decaySeconds) {
        $_SESSION[$key] = [
            'count' => 1,
            'start' => $now
        ];
        return true;
    }

    if ($_SESSION[$key]['count'] >= $maxAttempts) {
        return false;
    }

    $_SESSION[$key]['count']++;
    return true;
}
