-- =============================================
-- BASE250 - Schema do Banco de Dados
-- Versão: 1.0.0
-- Data: Fevereiro 2025
-- =============================================

-- Configurações
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =============================================
-- TABELA: usuarios
-- Usuários do sistema (admin, inquilinos)
-- =============================================
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `tipo` ENUM('admin', 'inquilino', 'porteiro') DEFAULT 'inquilino',
    `telefone` VARCHAR(20),
    `ativo` TINYINT(1) DEFAULT 1,
    `ultimo_acesso` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_email` (`email`),
    INDEX `idx_tipo` (`tipo`),
    INDEX `idx_ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABELA: apartamentos
-- Cadastro de unidades do edifício
-- =============================================
CREATE TABLE IF NOT EXISTS `apartamentos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `numero` VARCHAR(10) NOT NULL UNIQUE,
    `andar` INT,
    `tipo` VARCHAR(50) NOT NULL DEFAULT 'Studio', -- 'Studio', 'Loft', 'Apartamento'
    `metragem` DECIMAL(6,2),
    `quartos` INT DEFAULT 1,
    `banheiros` INT DEFAULT 1,
    `vagas_garagem` INT DEFAULT 0,
    `status` ENUM('disponivel', 'alugado', 'ocupado', 'manutencao', 'reservado') DEFAULT 'disponivel',
    `valor_base` DECIMAL(10,2),
    `preco` DECIMAL(10,2), -- Preço público de listagem
    `descricao` TEXT,
    `features` JSON, -- ['Geladeira', 'Ar condicionado', etc]
    `galeria_fotos` JSON, -- ['url1', 'url2', etc]
    `observacoes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_numero` (`numero`),
    INDEX `idx_status` (`status`),
    INDEX `idx_tipo` (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABELA: inquilinos
-- Dados pessoais dos inquilinos
-- =============================================
CREATE TABLE IF NOT EXISTS `inquilinos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT UNSIGNED,
    `nome` VARCHAR(100) NOT NULL,
    `cpf` VARCHAR(14) UNIQUE,
    `rg` VARCHAR(20),
    `data_nascimento` DATE,
    `genero` ENUM('M', 'F', 'O'),
    `estado_civil` ENUM('solteiro', 'casado', 'divorciado', 'viuvo', 'outro'),
    `profissao` VARCHAR(100),
    `nacionalidade` VARCHAR(50) DEFAULT 'Brasileira',
    `telefone` VARCHAR(20),
    `telefone2` VARCHAR(20),
    `email` VARCHAR(100),
    `endereco_anterior` TEXT,
    `observacoes` TEXT,
    `ativo` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
    INDEX `idx_cpf` (`cpf`),
    INDEX `idx_nome` (`nome`),
    INDEX `idx_ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABELA: contratos
-- Contratos de locação
-- =============================================
CREATE TABLE IF NOT EXISTS `contratos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `apartamento_id` INT UNSIGNED NOT NULL,
    `inquilino_id` INT UNSIGNED NOT NULL,
    `data_inicio` DATE NOT NULL,
    `data_fim` DATE NOT NULL,
    `duracao_meses` INT DEFAULT 12,
    `valor_aluguel` DECIMAL(10,2) NOT NULL,
    `valor_caucao` DECIMAL(10,2),
    `dia_vencimento` INT DEFAULT 10,
    `indice_reajuste` ENUM('IGPM', 'IPCA', 'INPC') DEFAULT 'IGPM',
    `status` ENUM('ativo', 'encerrado', 'cancelado', 'pendente') DEFAULT 'pendente',
    `arquivo_contrato` VARCHAR(255),
    `observacoes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`apartamento_id`) REFERENCES `apartamentos`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`inquilino_id`) REFERENCES `inquilinos`(`id`) ON DELETE RESTRICT,
    INDEX `idx_apartamento` (`apartamento_id`),
    INDEX `idx_inquilino` (`inquilino_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_datas` (`data_inicio`, `data_fim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABELA: pagamentos
-- Histórico de pagamentos
-- =============================================
CREATE TABLE IF NOT EXISTS `pagamentos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `contrato_id` INT UNSIGNED NOT NULL,
    `mes_referencia` DATE NOT NULL,
    `valor` DECIMAL(10,2) NOT NULL,
    `valor_pago` DECIMAL(10,2),
    `data_vencimento` DATE NOT NULL,
    `data_pagamento` DATE,
    `status` ENUM('pendente', 'pago', 'atrasado', 'cancelado') DEFAULT 'pendente',
    `forma_pagamento` ENUM('pix', 'transferencia', 'boleto', 'dinheiro', 'outro'),
    `comprovante` VARCHAR(255),
    `observacoes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`contrato_id`) REFERENCES `contratos`(`id`) ON DELETE CASCADE,
    INDEX `idx_contrato` (`contrato_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_mes` (`mes_referencia`),
    INDEX `idx_vencimento` (`data_vencimento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABELA: documentos
-- Arquivos anexados (contratos, comprovantes, etc)
-- =============================================
CREATE TABLE IF NOT EXISTS `documentos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `inquilino_id` INT UNSIGNED,
    `contrato_id` INT UNSIGNED,
    `tipo` ENUM('contrato', 'comprovante', 'documento_pessoal', 'laudo', 'outro') DEFAULT 'outro',
    `nome_original` VARCHAR(255) NOT NULL,
    `nome_arquivo` VARCHAR(255) NOT NULL,
    `caminho` VARCHAR(500) NOT NULL,
    `tamanho` INT,
    `mime_type` VARCHAR(100),
    `observacoes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`inquilino_id`) REFERENCES `inquilinos`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`contrato_id`) REFERENCES `contratos`(`id`) ON DELETE CASCADE,
    INDEX `idx_inquilino` (`inquilino_id`),
    INDEX `idx_contrato` (`contrato_id`),
    INDEX `idx_tipo` (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABELA: concessionarias
-- Dados de luz e água por apartamento
-- =============================================
CREATE TABLE IF NOT EXISTS `concessionarias` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `apartamento_id` INT UNSIGNED NOT NULL,
    `tipo` ENUM('luz', 'agua') NOT NULL,
    `fornecedor` VARCHAR(100),
    `numero_instalacao` VARCHAR(50),
    `numero_medidor` VARCHAR(50),
    `titular` VARCHAR(100),
    `observacoes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`apartamento_id`) REFERENCES `apartamentos`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_apto_tipo` (`apartamento_id`, `tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABELA: notificacoes
-- Sistema de alertas e avisos
-- =============================================
CREATE TABLE IF NOT EXISTS `notificacoes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT UNSIGNED,
    `tipo` ENUM('pagamento', 'contrato', 'documento', 'sistema', 'outro') DEFAULT 'sistema',
    `titulo` VARCHAR(200) NOT NULL,
    `mensagem` TEXT,
    `link` VARCHAR(500),
    `lida` TINYINT(1) DEFAULT 0,
    `data_leitura` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
    INDEX `idx_usuario` (`usuario_id`),
    INDEX `idx_lida` (`lida`),
    INDEX `idx_tipo` (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABELA: logs
-- Auditoria de ações no sistema
-- =============================================
CREATE TABLE IF NOT EXISTS `logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT UNSIGNED,
    `acao` VARCHAR(100) NOT NULL,
    `detalhes` TEXT,
    `ip` VARCHAR(45),
    `user_agent` VARCHAR(500),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
    INDEX `idx_usuario` (`usuario_id`),
    INDEX `idx_acao` (`acao`),
    INDEX `idx_data` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABELA: configuracoes
-- Parâmetros do sistema
-- =============================================
CREATE TABLE IF NOT EXISTS `configuracoes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `chave` VARCHAR(100) NOT NULL UNIQUE,
    `valor` TEXT,
    `tipo` ENUM('texto', 'numero', 'json', 'booleano') DEFAULT 'texto',
    `descricao` VARCHAR(255),
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- RESTAURAR FOREIGN KEYS
-- =============================================
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- FIM DO SCHEMA
-- =============================================
