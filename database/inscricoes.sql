-- =====================================================================
-- Estrutura do Banco de Dados: infra_redes
-- Projeto: Landing Page para Divulgação de Serviços de Infraestrutura de Redes
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `infra_redes` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `infra_redes`;

-- Tabela principal de inscrições/leads
CREATE TABLE IF NOT EXISTS `inscricoes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(120) NOT NULL COMMENT 'Nome completo do solicitante',
    `email` VARCHAR(180) NOT NULL COMMENT 'E-mail corporativo ou pessoal',
    `ddi` VARCHAR(8) NOT NULL COMMENT 'Código internacional do país (ex: +55)',
    `telefone` VARCHAR(30) NOT NULL COMMENT 'Telefone formatado ou nacional informado',
    `telefone_e164` VARCHAR(25) NOT NULL COMMENT 'Telefone padronizado no formato internacional E.164 (ex: +5511999998888)',
    `servico_interesse` VARCHAR(120) DEFAULT NULL COMMENT 'Serviço de infraestrutura de interesse',
    `mensagem` TEXT DEFAULT NULL COMMENT 'Observações ou detalhes da infraestrutura atual',
    `ip_origem` VARCHAR(45) DEFAULT NULL COMMENT 'Endereço IP para auditoria e controle de abuso (LGPD compliant)',
    `user_agent` VARCHAR(255) DEFAULT NULL COMMENT 'Identificador de navegador para telemetria de segurança',
    `status` ENUM('novo', 'em_analise', 'contatado', 'convertido') NOT NULL DEFAULT 'novo',
    `data_inscricao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do cadastro',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_inscricoes_email` (`email`),
    UNIQUE KEY `uq_inscricoes_telefone_e164` (`telefone_e164`),
    KEY `idx_data_inscricao` (`data_inscricao`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cadastros e solicitações de orçamento de infraestrutura de redes';
