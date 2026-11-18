
# landing-page-infraestrutura-redes
Landing page para divulgação de serviços de infraestrutura de redes, desenvolvida com PHP, MySQL, JavaScript e CSS, utilizando IA como ferramenta de apoio ao desenvolvimento.

# NexusRedes — Landing Page de Infraestrutura de Redes de Alta Performance

Projeto profissional e moderno de **Landing Page para Divulgação de Serviços de Infraestrutura de Redes e Captação de Leads**, desenvolvido com foco em alta conversão, design futurista/tecnológico, validações completas de ponta a ponta e rígidas camadas de segurança da informação.

---

## 🚀 Visão Geral do Projeto

A **NexusRedes** é uma solução completa para apresentação institucional e conversão de clientes corporativos para serviços de infraestrutura de TI:
- Cabeamento estruturado (Cat6, Cat6A e Fibra Óptica).
- Organização e identificação de racks e patch panels (normas TIA/EIA).
- Configuração de switches L2/L3 e roteadores corporativos.
- Implantação e otimização de redes locais (LAN), WAN e Wi-Fi 6 empresarial.
- Auditorias técnicas, certificação Fluke Networks e contratos de manutenção.

---

## 🛠️ Tecnologias Utilizadas

- **HTML5:** Estrutura semântica, acessibilidade (WAI-ARIA), meta tags Open Graph e SEO.
- **CSS3:** Estilização moderna em tema *Dark Tech*, gradientes vibrantes, efeitos *glassmorphism*, animações SVG de tráfego de dados e responsividade completa (Mobile First).
- **JavaScript (ES6+):** Manipulação dinâmica do modal, validações em tempo real, integração com a biblioteca oficial **intl-tel-input** e submissão assíncrona via **Fetch API (AJAX)**.
- **PHP (7.4 / 8.0+):** Processamento seguro no backend, sanitização de dados, validação de números internacionais no padrão **ITU-T E.164**, controle de sessão blindado e proteção contra bots.
- **MySQL (5.7 / 8.0+):** Armazenamento relacional com **PDO**, consultas preparadas (*Prepared Statements*), índices otimizados e restrições de integridade únicas (*Unique Constraints*).
- **intl-tel-input (v18):** Componente internacional de telefone com bandeiras de mais de 200 países, pesquisa inteligente, preenchimento automático do DDI e máscara.

---

## 📂 Estrutura de Diretórios

```text
landing-page/
│
├── index.php                 # Página principal da Landing Page com Modal
├── sucesso.php               # Página de confirmação pós-cadastro (feedback visual)
│
├── assets/
│   ├── css/
│   │   └── style.css         # Folha de estilos completa, responsiva e componentes
│   ├── js/
│   │   └── script.js         # Lógica frontend, modal, intl-tel-input e AJAX Fetch
│   ├── img/                  # Imagens e ilustrações
│   └── icons/                # Ícones SVG de infraestrutura
│
├── config/
│   └── database.php          # Conexão PDO segura (Singleton, utf8mb4, emulated prepares false)
│
├── includes/
│   ├── env.php               # Leitor nativo de variáveis de ambiente (.env)
│   ├── security.php          # Sessões seguras, proteção CSRF, Honeypot e Rate Limiting
│   └── functions.php         # Sanitizadores, validação E.164, libphonenumber e JSON Response
│
├── api/
│   └── cadastrar.php         # Endpoint RESTful para processamento de leads via POST
│
├── database/
│   └── inscricoes.sql        # Script SQL DDL de criação do banco e tabela com índices
│
├── tests/
│   ├── run_tests.php         # Suíte de testes automatizados (40+ verificações)
│   └── test_api_endpoints.php# Teste funcional de simulação da API
│
├── .env                      # Arquivo de configuração local (ignorado pelo git)
├── .env.example              # Exemplo de configuração de ambiente
├── .gitignore                # Arquivos e diretórios ignorados pelo controle de versão
├── composer.json             # Definição de dependências opcionais
└── README.md                 # Documentação completa do projeto
```

---

## 📋 Pré-requisitos

Para executar a aplicação localmente, certifique-se de possuir:

1. **PHP:** Versão 7.4 ou superior (Recomendado: PHP 8.1, 8.2, 8.3 ou 8.4) com as extensões ativas:
   - `pdo_mysql`
   - `mbstring`
   - `json`
   - `openssl`
2. **MySQL:** Versão 5.7 ou 8.0+ (ou MariaDB 10.3+).
3. **Servidor Web:** Apache, Nginx ou o servidor embutido do próprio PHP.
4. **Navegador Moderno:** Chrome, Firefox, Edge, Safari ou Opera com suporte a ES6.

---

## ⚙️ Instalação e Configuração Passo a Passo

### 1. Clonar ou Acessar o Diretório

```bash
cd c:\dev\landing-page
```

### 2. Configurar o Banco de Dados MySQL

Abra seu cliente MySQL (MySQL Workbench, phpMyAdmin, DBeaver ou terminal) e execute o script SQL fornecido:

```sql
SOURCE database/inscricoes.sql;
```

Ou via linha de comando:

```bash
mysql -u root -p < database/inscricoes.sql
```

Isso criará a base de dados `infra_redes` e a tabela `inscricoes` com a seguinte estrutura:

```sql
CREATE TABLE `inscricoes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(120) NOT NULL,
    `email` VARCHAR(180) NOT NULL,
    `ddi` VARCHAR(8) NOT NULL,
    `telefone` VARCHAR(30) NOT NULL,
    `telefone_e164` VARCHAR(25) NOT NULL,
    `servico_interesse` VARCHAR(120) DEFAULT NULL,
    `mensagem` TEXT DEFAULT NULL,
    `ip_origem` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('novo', 'em_analise', 'contatado', 'convertido') NOT NULL DEFAULT 'novo',
    `data_inscricao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_inscricoes_email` (`email`),
    UNIQUE KEY `uq_inscricoes_telefone_e164` (`telefone_e164`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3. Configurar o Arquivo `.env`

Copie o modelo `.env.example` para `.env`:

```bash
cp .env.example .env
```

Edite o arquivo `.env` com os dados de conexão do seu ambiente MySQL:

```env
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=infra_redes
DB_USER=root
DB_PASS=sua_senha_aqui

SESSION_LIFETIME=3600
CSRF_SECRET_KEY=nexus_infra_sec_key_e8f2a938c4d11234b6789a0
```

### 4. (Opcional) Instalar dependências via Composer

O projeto foi construído para funcionar de maneira **100% autônoma** com dependências zero no PHP. No entanto, se desejar utilizar a biblioteca oficial da Google `libphonenumber-for-php`, execute:

```bash
composer install
```

O sistema detectará automaticamente o `vendor/autoload.php` e utilizará a `libphonenumber` nativamente, mantendo o fallback automático para o validador interno quando o Composer não for utilizado.

---

## 💻 Como Executar Localmente

### Opção A: Servidor Embutido do PHP (Mais Rápido e Simples)

No diretório raiz do projeto (`c:\dev\landing-page`), execute:

```bash
php -S localhost:8000
```

Abra seu navegador em: **`http://localhost:8000`**

### Opção B: WampServer / XAMPP / Laragon

1. Mova ou aponte o VirtualHost para a pasta do projeto `landing-page`.
2. Certifique-se de que os módulos `Apache` e `MySQL` estejam iniciados.
3. Acesse via navegador em `http://localhost/landing-page` (ou o domínio do seu VirtualHost local).

---

## 🔒 Arquitetura de Segurança Implementada

A aplicação foi desenvolvida seguindo as melhores práticas do **OWASP Top 10**:

1. **Prevenção Total contra SQL Injection:**
   - Conexão PDO com `PDO::ATTR_EMULATE_PREPARES => false`.
   - 100% das consultas utilizam *Prepared Statements* com parâmetros nomeados (`:nome`, `:email`, etc.).
2. **Proteção contra Cross-Site Request Forgery (CSRF):**
   - Geração de tokens de 64 caracteres criptograficamente seguros (`random_bytes(32)`).
   - Validação com `hash_equals()` para proteção contra *Timing Attacks*.
3. **Mecanismo Anti-Bot (Honeypot):**
   - Campo oculto com CSS absoluto e sem foco no DOM. Se preenchido por robôs automatizados, o envio é descartado silenciosamente sem onerar o banco de dados.
4. **Proteção contra Cross-Site Scripting (XSS):**
   - Sanitização de entradas com `strip_tags()` e escapamento de saída em todas as renderizações com `htmlspecialchars(..., ENT_QUOTES | ENT_HTML5, 'UTF-8')`.
5. **Mitigação de Ataques de Força Bruta (Rate Limiting):**
   - Limitação de tentativas consecutivas de envio por sessão/IP para prevenir sobrecarga.
6. **Controle Estrito de Duplicidades:**
   - Verificação em duas camadas: checagem lógica via PHP e restrições únicas (`UNIQUE KEY`) a nível de banco de dados para **e-mail** e **telefone E.164**.
7. **Privacidade e LGPD:**
   - Termo explícito de consentimento e finalidade de coleta.
   - Mascaramento de dados sensíveis na tela de confirmação (`sucesso.php`).

---

## 🧪 Suíte de Testes Automatizados

O projeto inclui uma suíte completa de testes automatizados para verificar a integridade da aplicação antes de ir para produção.

Para executar os testes:

```bash
php tests/run_tests.php
```

E para os testes funcionais de endpoint:

```bash
php tests/test_api_endpoints.php
```

### Cenários Cobertos nos Testes:
- [x] Leitura e parse de variáveis de ambiente `.env`.
- [x] Geração e validação de tokens CSRF.
- [x] Detecção de robôs via Honeypot.
- [x] Sanitização de payloads maliciosos de XSS (`<script>`, eventos onload, tags HTML).
- [x] Validação de Nome Completo (exige nome + sobrenome, rejeita números, caracteres especiais e nomes com menos de 3 caracteres).
- [x] Validação de E-mail corporativo e internacional (formato RFC 5322, rejeição de injeções de cabeçalho).
- [x] Validação e formatação de Telefones Internacionais (Brasil +55 com DDDs válidos e 9º dígito em celulares, EUA +1, Portugal +351, formatação canônica E.164).
- [x] Resiliência contra payloads clássicos e avançados de SQL Injection.
- [x] Verificação de constraints de unicidade (e-mail e telefone) com banco relacional in-memory.
- [x] Teste de Rate Limiting e bloqueio contra requisições forjadas.

---

## 📱 Responsividade e Acessibilidade (UI/UX)

- **Design Responsivo:** Adaptado para smartphones (320px+), tablets (768px+) e monitores ultrawide (1920px+).
- **Acessibilidade do Modal:**
  - `role="dialog"`, `aria-modal="true"`, `aria-labelledby="modalTitle"`.
  - Fechamento imediato com a tecla <kbd>ESC</kbd>.
  - Bloqueio de rolagem da página de fundo (`overflow: hidden`) enquanto o modal estiver aberto.
  - Retorno automático de foco para o botão de origem ao fechar.
- **Feedback Visual Claro:**
  - Destaque em vermelho e mensagens explicativas em tempo real para campos com erros.
  - Limpeza automática de erros conforme o usuário digita.
  - Botão de envio com indicador de progresso (*Spinner*) e estado desabilitado contra cliques duplos.

---

## 📄 Licença

Este projeto é disponibilizado sob a licença **MIT**. Sinta-se livre para customizar, expandir e utilizar comercialmente.

