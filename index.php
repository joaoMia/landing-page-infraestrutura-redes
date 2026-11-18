<?php
/**
 * Landing Page - NexusRedes Infraestrutura de Alta Performance
 * Arquitetura Moderna, Acessível e Otimizada para Conversão
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/functions.php';

// Inicializar sessão segura e obter token CSRF
init_secure_session();
$csrfToken = get_csrf_token();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
  <title>NexusRedes | Especialistas em Infraestrutura de Redes e Cabeamento Estruturado</title>
  <meta name="description" content="Projetos, cabeamento estruturado, organização de racks, switches e infraestrutura de redes corporativas de alta disponibilidade. Solicite seu diagnóstico gratuito.">
  <meta name="keywords" content="infraestrutura de redes, cabeamento estruturado, organização de racks, switches, roteadores, redes corporativas, fibra óptica, certificação fluke">
  <meta name="author" content="NexusRedes Infraestrutura">
  <meta name="theme-color" content="#070a12">

  <!-- Open Graph / Redes Sociais -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="NexusRedes | Infraestrutura de Redes de Alta Performance">
  <meta property="og:description" content="Soluções corporativas completas em redes: cabeamento estruturado, organização de racks e equipamentos de alta disponibilidade.">
  <meta property="og:locale" content="pt_BR">

  <!-- Fontes do Google: Inter e JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- intl-tel-input CSS para Seletor Internacional com Bandeiras -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

  <!-- Estilos Customizados -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <!-- =====================================================================
       Header & Barra de Navegação
       ===================================================================== -->
  <header class="site-header" id="header">
    <div class="container nav-container">
      <a href="#inicio" class="logo" aria-label="NexusRedes - Página Inicial">
        <div class="logo-icon-wrap" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
            <line x1="6" y1="6" x2="6.01" y2="6"></line>
            <line x1="6" y1="18" x2="6.01" y2="18"></line>
          </svg>
        </div>
        <span>Nexus<span class="logo-highlight">Redes</span></span>
      </a>

      <!-- Menu Principal -->
      <nav aria-label="Navegação Principal">
        <ul class="nav-menu" id="navMenu">
          <li><a href="#inicio" class="nav-link active">Início</a></li>
          <li><a href="#servicos" class="nav-link">Serviços</a></li>
          <li><a href="#diferenciais" class="nav-link">Diferenciais</a></li>
          <li><a href="#metodologia" class="nav-link">Como Trabalhamos</a></li>
          <li><a href="#contato" class="nav-link">Contato</a></li>
        </ul>
      </nav>

      <!-- Botão CTA no Topo -->
      <button type="button" class="btn btn-primary btn-sm nav-cta-btn" data-open-modal aria-haspopup="dialog">
        <span>Solicitar Orçamento</span>
        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"></line>
          <polyline points="12 5 19 12 12 19"></polyline>
        </svg>
      </button>

      <!-- Botão Menu Mobile -->
      <button type="button" class="mobile-toggle" id="mobileToggle" aria-label="Abrir Menu de Navegação" aria-expanded="false" aria-controls="navMenu">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>
    </div>
  </header>

  <main>
    <!-- =====================================================================
         Hero Section
         ===================================================================== -->
    <section class="hero-section" id="inicio">
      <div class="container hero-grid">
        <div class="hero-content">
          <div class="badge">
            <span class="badge-pulse" aria-hidden="true"></span>
            <span>Soluções Corporativas de Alta Disponibilidade • SLA 99.98%</span>
          </div>

          <h1 class="hero-title">
            Infraestrutura de Redes <span class="gradient-text">Inteligente, Segura</span> e de Alta Performance
          </h1>

          <p class="hero-subtitle">
            Projetamos, organizamos e implementamos cabeamento estruturado, servidores e redes corporativas para empresas que não podem parar. Máxima velocidade, certificação técnica e zero instabilidade.
          </p>

          <div class="hero-actions">
            <button type="button" class="btn btn-primary btn-lg" data-open-modal aria-haspopup="dialog">
              <span>Solicitar Diagnóstico Gratuito</span>
              <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>
            <a href="#servicos" class="btn btn-secondary btn-lg">
              <span>Ver Serviços</span>
            </a>
          </div>

          <!-- Métricas e Prova Social -->
          <div class="hero-stats">
            <div class="stat-item">
              <h3>+15.000<span>m</span></h3>
              <p>Cabos Cat6/6A & Fibra Lançados</p>
            </div>
            <div class="stat-item">
              <h3>99.98<span>%</span></h3>
              <p>Uptime Médio em Redes Gerenciadas</p>
            </div>
            <div class="stat-item">
              <h3>+350</h3>
              <p>Racks Reestruturados e Certificados</p>
            </div>
          </div>
        </div>

        <!-- Elemento Visual Interativo: Topologia de Rede SVG -->
        <div class="hero-visual" aria-hidden="true">
          <div class="topology-card">
            <div class="topology-header">
              <div class="topology-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="2" y1="12" x2="22" y2="12"></line>
                  <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                </svg>
                <span>TOPOLOGIA DE REDE CORE L3</span>
              </div>
              <div class="status-indicator">
                <span class="status-dot"></span>
                <span>ONLINE 10Gbps</span>
              </div>
            </div>

            <!-- SVG Interativo com Nós de Rede e Fluxo de Dados -->
            <svg class="network-svg-wrap" viewBox="0 0 440 280" fill="none" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <linearGradient id="linkGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stop-color="#00d2ff" stop-opacity="0.8" />
                  <stop offset="100%" stop-color="#2563eb" stop-opacity="0.8" />
                </linearGradient>
                <filter id="glowEffect" x="-20%" y="-20%" width="140%" height="140%">
                  <feGaussianBlur stdDeviation="3" result="blur" />
                  <feComposite in="SourceGraphic" in2="blur" operator="over" />
                </filter>
              </defs>

              <!-- Conexões de Fibra (Linhas de Enlace) -->
              <line x1="220" y1="40" x2="120" y2="130" stroke="rgba(255,255,255,0.15)" stroke-width="2" />
              <line x1="220" y1="40" x2="320" y2="130" stroke="rgba(255,255,255,0.15)" stroke-width="2" />
              <line x1="120" y1="130" x2="320" y2="130" stroke="rgba(255,255,255,0.1)" stroke-width="2" stroke-dasharray="4 4" />
              <line x1="120" y1="130" x2="60" y2="230" stroke="rgba(255,255,255,0.15)" stroke-width="2" />
              <line x1="120" y1="130" x2="160" y2="230" stroke="rgba(255,255,255,0.15)" stroke-width="2" />
              <line x1="320" y1="130" x2="280" y2="230" stroke="rgba(255,255,255,0.15)" stroke-width="2" />
              <line x1="320" y1="130" x2="380" y2="230" stroke="rgba(255,255,255,0.15)" stroke-width="2" />

              <!-- Cabos Ativos com Fluxo de Luz Animado -->
              <line x1="220" y1="40" x2="120" y2="130" stroke="url(#linkGrad)" stroke-width="2" class="cable-flow" />
              <line x1="220" y1="40" x2="320" y2="130" stroke="url(#linkGrad)" stroke-width="2" class="cable-flow-fast" />
              <line x1="120" y1="130" x2="60" y2="230" stroke="url(#linkGrad)" stroke-width="2" class="cable-flow" />
              <line x1="320" y1="130" x2="380" y2="230" stroke="url(#linkGrad)" stroke-width="2" class="cable-flow-fast" />

              <!-- Nó Central: Gateway / Firewall de Borda -->
              <g transform="translate(220, 40)">
                <circle r="22" fill="#0d1424" stroke="#00d2ff" stroke-width="2" filter="url(#glowEffect)" />
                <rect x="-8" y="-8" width="16" height="16" rx="3" fill="#00d2ff" opacity="0.8" />
                <text x="0" y="34" fill="#94a3b8" font-size="10" font-family="monospace" text-anchor="middle">Firewall / Gateway</text>
              </g>

              <!-- Nó 1: Switch Core A -->
              <g transform="translate(120, 130)">
                <circle r="18" fill="#0d1424" stroke="#2563eb" stroke-width="2" />
                <rect x="-6" y="-6" width="12" height="12" rx="2" fill="#2563eb" />
                <text x="0" y="28" fill="#94a3b8" font-size="9" font-family="monospace" text-anchor="middle">Switch L3 Principal</text>
              </g>

              <!-- Nó 2: Switch Core B (Redundância) -->
              <g transform="translate(320, 130)">
                <circle r="18" fill="#0d1424" stroke="#2563eb" stroke-width="2" />
                <rect x="-6" y="-6" width="12" height="12" rx="2" fill="#2563eb" />
                <text x="0" y="28" fill="#94a3b8" font-size="9" font-family="monospace" text-anchor="middle">Switch Redundante</text>
              </g>

              <!-- Nós de Acesso / Clientes / Servidores -->
              <g transform="translate(60, 230)">
                <rect x="-14" y="-12" width="28" height="24" rx="4" fill="#111827" stroke="#10b981" stroke-width="1.5" />
                <line x1="-8" y1="-2" x2="8" y2="-2" stroke="#10b981" stroke-width="1.5" />
                <line x1="-8" y1="4" x2="2" y2="4" stroke="#10b981" stroke-width="1.5" />
                <text x="0" y="24" fill="#64748b" font-size="8" font-family="monospace" text-anchor="middle">Servidores</text>
              </g>

              <g transform="translate(160, 230)">
                <circle r="12" fill="#111827" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" />
                <text x="0" y="24" fill="#64748b" font-size="8" font-family="monospace" text-anchor="middle">VLAN Dados</text>
              </g>

              <g transform="translate(280, 230)">
                <circle r="12" fill="#111827" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" />
                <text x="0" y="24" fill="#64748b" font-size="8" font-family="monospace" text-anchor="middle">Wi-Fi 6 Corp</text>
              </g>

              <g transform="translate(380, 230)">
                <rect x="-14" y="-12" width="28" height="24" rx="4" fill="#111827" stroke="#10b981" stroke-width="1.5" />
                <line x1="-8" y1="-2" x2="8" y2="-2" stroke="#10b981" stroke-width="1.5" />
                <text x="0" y="24" fill="#64748b" font-size="8" font-family="monospace" text-anchor="middle">Storage / SAN</text>
              </g>
            </svg>

            <!-- Tags Técnicas -->
            <div class="topology-tags">
              <span class="tech-pill">VLANs 802.1Q</span>
              <span class="tech-pill">LACP 802.3ad</span>
              <span class="tech-pill">QoS VoIP</span>
              <span class="tech-pill">STP / RSTP</span>
              <span class="tech-pill">Zero Packet Loss</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- =====================================================================
         Seção de Serviços Especializados
         ===================================================================== -->
    <section class="section section-alt" id="servicos">
      <div class="container">
        <div class="section-header">
          <div class="badge">Especialização Técnica</div>
          <h2 class="section-title">Soluções Completas de Infraestrutura</h2>
          <p class="section-subtitle">
            Do projeto físico à configuração lógica avançada, entregamos excelência para suportar as operações mais críticas do seu negócio.
          </p>
        </div>

        <div class="services-grid">
          <!-- Serviço 1: Instalação e Configuração de Redes -->
          <article class="service-card">
            <div class="service-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="2" y1="12" x2="22" y2="12"></line>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
              </svg>
            </div>
            <h3 class="service-title">Instalação e Configuração de Redes</h3>
            <p class="service-desc">
              Implementação de arquiteturas de rede corporativa locais (LAN) e de longa distância (WAN/SD-WAN), com Wi-Fi 6 corporativo, mapa de calor e roaming contínuo sem quedas.
            </p>
            <ul class="service-features">
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Wi-Fi Empresarial de Alta Densidade</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Segmentação de Tráfego e Redes Visitantes</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Balanceamento e Redundância de Links</span>
              </li>
            </ul>
            <button type="button" class="btn btn-secondary btn-block" data-open-modal data-service="Instalação e Configuração de Redes">
              <span>Solicitar Este Serviço</span>
            </button>
          </article>

          <!-- Serviço 2: Cabeamento Estruturado -->
          <article class="service-card">
            <div class="service-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
              </svg>
            </div>
            <h3 class="service-title">Cabeamento Estruturado</h3>
            <p class="service-desc">
              Projetos e execução de cabeamento de dados, voz e imagem nas categorias Cat6, Cat6A e Fibra Óptica (monomodo e multimodo), seguindo rigorosamente as normas TIA/EIA-568.
            </p>
            <ul class="service-features">
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Cabos de Par Trançado Cat6 / Cat6A 10Gbps</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Fusão e Conectorização de Fibra Óptica</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Certificação de Canal e Link Permanente</span>
              </li>
            </ul>
            <button type="button" class="btn btn-secondary btn-block" data-open-modal data-service="Cabeamento Estruturado">
              <span>Solicitar Este Serviço</span>
            </button>
          </article>

          <!-- Serviço 3: Organização de Racks e Patch Panels -->
          <article class="service-card">
            <div class="service-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                <line x1="6" y1="6" x2="6.01" y2="6"></line>
                <line x1="6" y1="18" x2="6.01" y2="18"></line>
              </svg>
            </div>
            <h3 class="service-title">Organização de Racks e Patch Panels</h3>
            <p class="service-desc">
              Transformação completa de racks caóticos e cheios de fios soltos em ambientes industriais organizados, com réguas de organização, patch cords sob medida e etiquetagem TIA-606.
            </p>
            <ul class="service-features">
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Eliminação do Superaquecimento por Cabos</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Identificação Numérica Ponto a Ponto</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Facilidade Imediata para Manutenção</span>
              </li>
            </ul>
            <button type="button" class="btn btn-secondary btn-block" data-open-modal data-service="Organização de Racks e Patch Panels">
              <span>Solicitar Este Serviço</span>
            </button>
          </article>

          <!-- Serviço 4: Configuração de Switches e Roteadores -->
          <article class="service-card">
            <div class="service-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="16 18 22 12 16 6"></polyline>
                <polyline points="8 6 2 12 8 18"></polyline>
              </svg>
            </div>
            <h3 class="service-title">Configuração de Switches e Roteadores</h3>
            <p class="service-desc">
              Parametrização avançada de equipamentos gerenciáveis L2 e L3. Criação de VLANs, agregação de links (LACP), spanning-tree para evitar loops de rede e políticas de QoS.
            </p>
            <ul class="service-features">
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Segmentação de Redes por VLANs</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Priorização de Pacotes para Telefonia VoIP</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Configuração Cisco, MikroTik, Ubiquiti, Dell</span>
              </li>
            </ul>
            <button type="button" class="btn btn-secondary btn-block" data-open-modal data-service="Configuração de Switches e Roteadores">
              <span>Solicitar Este Serviço</span>
            </button>
          </article>

          <!-- Serviço 5: Infraestrutura de Redes Corporativas -->
          <article class="service-card">
            <div class="service-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                <path d="M2 17l10 5 10-5"></path>
                <path d="M2 12l10 5 10-5"></path>
              </svg>
            </div>
            <h3 class="service-title">Infraestrutura de Redes Corporativas</h3>
            <p class="service-desc">
              Estruturação física completa de salas técnicas e CPDs: no-breaks senoidais gerenciáveis, contenção de cabos sob piso elevado, calhas metálicas e sistemas de aterramento dedicados.
            </p>
            <ul class="service-features">
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Salas Técnicas e CPDs com Padrão Data Center</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Proteção Elétrica e Aterramento TIA-607</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Projetos Escaláveis para Expansão Futura</span>
              </li>
            </ul>
            <button type="button" class="btn btn-secondary btn-block" data-open-modal data-service="Infraestrutura de Redes Corporativas">
              <span>Solicitar Este Serviço</span>
            </button>
          </article>

          <!-- Serviço 6: Manutenção e Otimização de Redes -->
          <article class="service-card">
            <div class="service-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
              </svg>
            </div>
            <h3 class="service-title">Manutenção e Otimização de Redes</h3>
            <p class="service-desc">
              Diagnóstico de problemas crônicos, latências anormais e perdas de pacotes. Auditorias com equipamentos de precisão Fluke Networks e planos de manutenção preventiva continuada.
            </p>
            <ul class="service-features">
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Eliminação Definitiva de Gargalos e Lentidão</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Auditoria e Laudos com Certificador Fluke</span>
              </li>
              <li class="service-feature-item">
                <svg class="service-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Contratos de Suporte com SLA Garantido</span>
              </li>
            </ul>
            <button type="button" class="btn btn-secondary btn-block" data-open-modal data-service="Manutenção e Otimização de Redes">
              <span>Solicitar Este Serviço</span>
            </button>
          </article>
        </div>
      </div>
    </section>

    <!-- =====================================================================
         Seção de Diferenciais
         ===================================================================== -->
    <section class="section" id="diferenciais">
      <div class="container">
        <div class="section-header">
          <div class="badge">Por que a NexusRedes?</div>
          <h2 class="section-title">Engenharia de Redes sem Improvisos</h2>
          <p class="section-subtitle">
            Garantimos confiabilidade estrutural para que sua equipe trabalhe focada nos negócios, sem dores de cabeça com infraestrutura.
          </p>
        </div>

        <div class="differentials-grid">
          <div class="diff-card">
            <div class="diff-icon-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg>
            </div>
            <h3 class="diff-title">Certificação Fluke Networks</h3>
            <p class="diff-desc">
              Cada ponto de rede e link de fibra é testado e certificado individualmente com entrega de laudo oficial em PDF.
            </p>
          </div>

          <div class="diff-card">
            <div class="diff-icon-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
            </div>
            <h3 class="diff-title">Zero Downtime na Transição</h3>
            <p class="diff-desc">
              Executamos migrações e reorganizações de racks em janelas noturnas ou finais de semana, sem interromper seu expediente comercial.
            </p>
          </div>

          <div class="diff-card">
            <div class="diff-icon-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
              </svg>
            </div>
            <h3 class="diff-title">Documentação As-Built Completa</h3>
            <p class="diff-desc">
              Entregamos diagramas de topologia física e lógica, mapa de portas nos patch panels e tabelas de endereçamento IP.
            </p>
          </div>

          <div class="diff-card">
            <div class="diff-icon-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
            </div>
            <h3 class="diff-title">Segurança e Segmentação</h3>
            <p class="diff-desc">
              Isolamento de tráfego crítico, proteção de servidores contra acessos não autorizados e criação de redes dedicadas para convidados.
            </p>
          </div>

          <div class="diff-card">
            <div class="diff-icon-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
              </svg>
            </div>
            <h3 class="diff-title">Componentes Homologados</h3>
            <p class="diff-desc">
              Trabalhamos exclusivamente com marcas líderes mundiais: Furukawa, CommScope, Cisco, Ubiquiti e MikroTik.
            </p>
          </div>

          <div class="diff-card">
            <div class="diff-icon-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
            </div>
            <h3 class="diff-title">Garantia Estendida</h3>
            <p class="diff-desc">
              Oferecemos garantia de até 25 anos para instalações de cabeamento estruturado passivo 100% certificado.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- =====================================================================
         Metodologia / Como Trabalhamos
         ===================================================================== -->
    <section class="section section-alt" id="metodologia">
      <div class="container">
        <div class="section-header">
          <div class="badge">Processo Transparente</div>
          <h2 class="section-title">Como Conduzimos Seu Projeto</h2>
          <p class="section-subtitle">
            Um fluxo de engenharia estruturado do primeiro contato ao pós-entrega com suporte contínuo.
          </p>
        </div>

        <div class="process-grid">
          <div class="process-step">
            <span class="step-number">01</span>
            <h3 class="step-title">Diagnóstico Técnico</h3>
            <p class="step-desc">
              Visita técnica presencial ou remota para mapear sua topologia atual, gargalos e necessidades de expansão.
            </p>
          </div>

          <div class="process-step">
            <span class="step-number">02</span>
            <h3 class="step-title">Engenharia e Projeto</h3>
            <p class="step-desc">
              Dimensionamento preciso dos pontos de rede, materiais, equipamentos ativos e cronograma de execução sem impacto.
            </p>
          </div>

          <div class="process-step">
            <span class="step-number">03</span>
            <h3 class="step-title">Execução e Certificação</h3>
            <p class="step-desc">
              Instalação padrão TIA-568, conectorização cuidadosa, organização de racks e emissão de laudo técnico de certificação.
            </p>
          </div>

          <div class="process-step">
            <span class="step-number">04</span>
            <h3 class="step-title">As-Built e Suporte</h3>
            <p class="step-desc">
              Entrega da documentação completa da rede organizada, treinamento da equipe interna e suporte pós-implementação.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- =====================================================================
         CTA Final
         ===================================================================== -->
    <section class="section" id="contato">
      <div class="container">
        <div class="cta-banner">
          <div class="badge">Atendimento Rápido</div>
          <h2 class="cta-title">Sua Empresa Pronta para Operar na Velocidade Máxima e sem Quedas</h2>
          <p class="cta-subtitle">
            Agende uma avaliação técnica sem compromisso com nossos engenheiros de infraestrutura de redes. Receba um plano detalhado para modernizar seu ambiente.
          </p>
          <button type="button" class="btn btn-primary btn-lg" data-open-modal aria-haspopup="dialog">
            <span>Falar com um Especialista Agora</span>
            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>
        </div>
      </div>
    </section>
  </main>

  <!-- =====================================================================
       Footer
       ===================================================================== -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="#inicio" class="logo">
            <div class="logo-icon-wrap" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
              </svg>
            </div>
            <span>Nexus<span class="logo-highlight">Redes</span></span>
          </a>
          <p>
            Especialistas em engenharia de redes, cabeamento estruturado e infraestrutura de alta performance para médias e grandes corporações.
          </p>
        </div>

        <div class="footer-col">
          <h4>Serviços</h4>
          <ul class="footer-links">
            <li><a href="#servicos" data-open-modal data-service="Cabeamento Estruturado">Cabeamento Estruturado</a></li>
            <li><a href="#servicos" data-open-modal data-service="Organização de Racks">Organização de Racks</a></li>
            <li><a href="#servicos" data-open-modal data-service="Switches e Roteadores">Configuração de Switches</a></li>
            <li><a href="#servicos" data-open-modal data-service="Wi-Fi Corporativo">Wi-Fi Corporativo</a></li>
            <li><a href="#servicos" data-open-modal data-service="Auditoria e Certificação">Certificação de Redes</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Navegação</h4>
          <ul class="footer-links">
            <li><a href="#inicio">Início</a></li>
            <li><a href="#servicos">Serviços</a></li>
            <li><a href="#diferenciais">Diferenciais</a></li>
            <li><a href="#metodologia">Como Trabalhamos</a></li>
            <li><a href="#contato">Solicitar Orçamento</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Atendimento</h4>
          <ul class="footer-links">
            <li><span>📍 São Paulo, SP & Todo o Brasil</span></li>
            <li><span>📞 +55 (11) 4002-8922</span></li>
            <li><span>✉️ contato@nexusredes.com.br</span></li>
            <li><span>🕒 Seg - Sex: 08:00 às 18:00</span></li>
            <li><span>🚨 Plantão 24/7 para Emergências</span></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <div>
          &copy; <?php echo date('Y'); ?> NexusRedes Infraestrutura de Telecomunicações Ltda. Todos os direitos reservados.
        </div>
        <div class="footer-lgpd">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
          <span>Conformidade com a LGPD (Lei 13.709/2018) • Seus dados estão seguros</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- =====================================================================
       Modal Popup do Formulário de Inscrição
       ===================================================================== -->
  <div class="modal-overlay" id="leadModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-header">
        <button type="button" class="modal-close-btn" id="modalCloseBtn" aria-label="Fechar janela de inscrição" data-close-modal>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
        <h2 class="modal-title" id="modalTitle">Solicitar Atendimento Técnico</h2>
        <p class="modal-subtitle">
          Preencha os dados abaixo para receber um diagnóstico preliminar e proposta customizada para sua infraestrutura.
        </p>
      </div>

      <div class="modal-body">
        <!-- Banner de Alertas (Erros ou Sucesso) -->
        <div class="form-alert" id="formAlert" role="alert"></div>

        <form id="leadForm" novalidate>
          <!-- Proteção CSRF -->
          <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo e($csrfToken); ?>">

          <!-- Honeypot contra Bots (Invisível para Usuários Humanos) -->
          <input type="text" name="website" id="website" style="display:none !important; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">

          <!-- Campos Ocultos do Telefone Internacional -->
          <input type="hidden" name="ddi" id="ddi" value="+55">
          <input type="hidden" name="telefone_e164" id="telefone_e164" value="">
          <input type="hidden" name="pais_iso" id="pais_iso" value="br">

          <!-- Campo: Nome Completo -->
          <div class="form-group">
            <label for="nome" class="form-label">
              Nome Completo <span class="req">*</span>
            </label>
            <input 
              type="text" 
              class="form-control" 
              id="nome" 
              name="nome" 
              placeholder="Ex: Carlos Eduardo Silveira" 
              required 
              maxlength="100"
              autocomplete="name"
            >
            <span class="field-error" id="error-nome"></span>
          </div>

          <!-- Campo: E-mail -->
          <div class="form-group">
            <label for="email" class="form-label">
              E-mail Corporativo ou Pessoal <span class="req">*</span>
            </label>
            <input 
              type="email" 
              class="form-control" 
              id="email" 
              name="email" 
              placeholder="Ex: carlos@suaempresa.com.br" 
              required 
              maxlength="150"
              autocomplete="email"
            >
            <span class="field-error" id="error-email"></span>
          </div>

          <!-- Campo: Telefone Internacional com intl-tel-input -->
          <div class="form-group">
            <label for="telefone" class="form-label">
              Telefone / WhatsApp <span class="req">*</span>
            </label>
            <input 
              type="tel" 
              class="form-control" 
              id="telefone" 
              name="telefone" 
              placeholder="(11) 98888-7777" 
              required
              autocomplete="tel"
            >
            <span class="field-error" id="error-telefone"></span>
          </div>

          <!-- Campo: Serviço de Interesse -->
          <div class="form-group">
            <label for="servico_interesse" class="form-label">
              Serviço Principal de Interesse
            </label>
            <select class="form-control" id="servico_interesse" name="servico_interesse">
              <option value="Cabeamento Estruturado">Cabeamento Estruturado (Cat6, Cat6A, Fibra)</option>
              <option value="Organização de Racks e Patch Panels">Organização de Racks e Patch Panels</option>
              <option value="Instalação e Configuração de Redes">Instalação e Configuração de Redes Corporativas</option>
              <option value="Configuração de Switches e Roteadores">Configuração de Switches e Roteadores</option>
              <option value="Infraestrutura de Redes Corporativas">Infraestrutura Completa de CPD / Sala Técnica</option>
              <option value="Manutenção e Otimização de Redes">Manutenção, Diagnóstico e Auditoria Fluke</option>
              <option value="Outro / Projeto Sob Medida">Outro / Projeto Sob Medida</option>
            </select>
          </div>

          <!-- Campo: Mensagem Opcional -->
          <div class="form-group">
            <label for="mensagem" class="form-label">
              Observações ou Detalhes da Infraestrutura (Opcional)
            </label>
            <textarea 
              class="form-control" 
              id="mensagem" 
              name="mensagem" 
              rows="3" 
              placeholder="Descreva brevemente a situação atual (ex: quantidade de pontos de rede, tamanho da empresa, etc.)"
            ></textarea>
          </div>

          <!-- Checkbox LGPD -->
          <div class="form-group">
            <div class="checkbox-group">
              <input type="checkbox" id="lgpd_consent" name="lgpd_consent" value="1" class="checkbox-custom" required>
              <label for="lgpd_consent" class="checkbox-label">
                Concordo com o tratamento dos meus dados exclusivamente para fins de contato comercial e elaboração da proposta técnica, em total conformidade com a <strong>LGPD</strong>.
              </label>
            </div>
            <span class="field-error" id="error-lgpd"></span>
          </div>

          <!-- Botão de Envio -->
          <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitBtn">
            <span class="spinner" aria-hidden="true"></span>
            <span class="btn-text">Enviar Solicitação de Orçamento</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- intl-tel-input JS da CDN Oficial -->
  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

  <!-- Script da Aplicação -->
  <script src="assets/js/script.js"></script>
</body>
</html>
