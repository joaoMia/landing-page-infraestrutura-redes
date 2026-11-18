<?php
/**
 * Página de Confirmação de Inscrição / Sucesso
 * Segue a mesma identidade visual da Landing Page.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/functions.php';

init_secure_session();

// Recuperar dados de confirmação da sessão (se existirem)
$temCadastroRecente = !empty($_SESSION['lead_sucesso']);
$leadNome = $_SESSION['lead_nome'] ?? 'Cliente';
$leadEmail = $_SESSION['lead_email'] ?? '';
$leadTelefone = $_SESSION['lead_telefone'] ?? '';

// Mascarar o e-mail para privacidade na exibição
$emailMascarado = '';
if (!empty($leadEmail)) {
    $partes = explode('@', $leadEmail);
    if (count($partes) === 2) {
        $usuario = $partes[0];
        $dominio = $partes[1];
        $len = strlen($usuario);
        if ($len > 2) {
            $mascarado = substr($usuario, 0, 2) . str_repeat('*', max(1, $len - 2));
        } else {
            $mascarado = $usuario . '***';
        }
        $emailMascarado = $mascarado . '@' . $dominio;
    }
}

// Limpar flash de sucesso para não persistir infinitamente
unset($_SESSION['lead_sucesso']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscrição Realizada com Sucesso! | NexusRedes Infraestrutura</title>
  
  <meta name="theme-color" content="#070a12">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="success-page">

  <div class="success-card">
    <!-- Ícone Visual de Sucesso -->
    <div class="success-icon-wrap" aria-hidden="true">
      <svg class="success-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
        <polyline points="22 4 12 14.01 9 11.01"></polyline>
      </svg>
    </div>

    <!-- Título Principal Obrigatório -->
    <h1 class="success-title">Inscrição realizada com sucesso!</h1>

    <!-- Mensagem Agradecendo o Interesse -->
    <p class="success-message">
      Agradecemos pelo seu interesse em nossos serviços de infraestrutura de redes! Seus dados foram recebidos com segurança por nossa equipe técnica.
    </p>

    <!-- Resumo dos Dados Recebidos (se disponíveis na sessão) -->
    <?php if ($temCadastroRecente): ?>
      <div class="success-details-box">
        <div class="success-detail-row">
          <span class="success-detail-label">Solicitante:</span>
          <span class="success-detail-value"><?php echo e($leadNome); ?></span>
        </div>
        <?php if (!empty($emailMascarado)): ?>
          <div class="success-detail-row">
            <span class="success-detail-label">E-mail Registrado:</span>
            <span class="success-detail-value"><?php echo e($emailMascarado); ?></span>
          </div>
        <?php endif; ?>
        <?php if (!empty($leadTelefone)): ?>
          <div class="success-detail-row">
            <span class="success-detail-label">Telefone (E.164):</span>
            <span class="success-detail-value"><?php echo e($leadTelefone); ?></span>
          </div>
        <?php endif; ?>
        <div class="success-detail-row">
          <span class="success-detail-label">Protocolo de Envio:</span>
          <span class="success-detail-value">NX-<?php echo strtoupper(substr(md5((string)time()), 0, 8)); ?></span>
        </div>
      </div>
    <?php endif; ?>

    <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 2rem;">
      Um especialista em redes entrará em contato em menos de <strong>2 horas úteis</strong> para dar andamento ao seu diagnóstico.
    </p>

    <!-- Botão para Retornar à Página Inicial -->
    <a href="index.php" class="btn btn-primary btn-lg">
      <svg class="btn-icon" style="transform: rotate(180deg);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="5" y1="12" x2="19" y2="12"></line>
        <polyline points="12 5 19 12 12 19"></polyline>
      </svg>
      <span>Retornar à Página Inicial</span>
    </a>
  </div>

</body>
</html>
