-- =============================================
-- BASE250 - Dados Iniciais (Seed)
-- Versão: 1.0.0
-- =============================================

-- =============================================
-- USUÁRIO ADMINISTRADOR
-- Email: admin@base250.com
-- Senha: Admin@250 (ALTERE APÓS PRIMEIRO ACESSO!)
-- =============================================
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `tipo`, `ativo`) VALUES
('Administrador', 'admin@base250.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);
-- Nota: A senha acima é 'password' com hash. Para usar 'Admin@250', gere um novo hash.

-- =============================================
-- APARTAMENTOS (Edifício Alto do Itacorubi)
-- 14 unidades
-- =============================================
INSERT INTO `apartamentos` (`numero`, `andar`, `status`, `valor_base`) VALUES
('101', 1, 'disponivel', 1200.00),
('102', 1, 'disponivel', 1200.00),
('103', 1, 'disponivel', 1250.00),
('104', 1, 'disponivel', 1250.00),
('201', 2, 'disponivel', 1300.00),
('202', 2, 'disponivel', 1300.00),
('203', 2, 'disponivel', 1350.00),
('204', 2, 'disponivel', 1350.00),
('301', 3, 'disponivel', 1400.00),
('302', 3, 'disponivel', 1400.00),
('303', 3, 'disponivel', 1450.00),
('304', 3, 'disponivel', 1450.00),
('401', 4, 'disponivel', 1500.00),
('402', 4, 'disponivel', 1500.00);

-- =============================================
-- CONFIGURAÇÕES INICIAIS
-- =============================================
INSERT INTO `configuracoes` (`chave`, `valor`, `tipo`, `descricao`) VALUES
('nome_edificio', 'Edifício Alto do Itacorubi', 'texto', 'Nome do edifício'),
('endereco', 'Rua Exemplo, 250 - Itacorubi, Florianópolis/SC', 'texto', 'Endereço completo'),
('telefone', '(48) 99999-0000', 'texto', 'Telefone de contato'),
('email', 'contato@base250.com', 'texto', 'E-mail de contato'),
('dias_aviso_vencimento', '5', 'numero', 'Dias antes do vencimento para avisar'),
('dias_atraso_notificacao', '3', 'numero', 'Dias após vencimento para notificar atraso'),
('indice_reajuste_padrao', 'IGPM', 'texto', 'Índice padrão para reajuste de aluguel'),
('dia_vencimento_padrao', '10', 'numero', 'Dia de vencimento padrão'),
('duracao_contrato_padrao', '12', 'numero', 'Duração padrão do contrato em meses');

-- =============================================
-- DADOS DE EXEMPLO (OPCIONAL)
-- Descomente se quiser dados de teste
-- =============================================

/*
-- Inquilino de exemplo
INSERT INTO `inquilinos` (`nome`, `cpf`, `telefone`, `email`, `ativo`) VALUES
('Maria Silva Santos', '123.456.789-00', '(48) 99999-1234', 'maria@email.com', 1),
('João Pedro Oliveira', '987.654.321-00', '(48) 98888-5678', 'joao@email.com', 1);

-- Contrato de exemplo
INSERT INTO `contratos` (`apartamento_id`, `inquilino_id`, `data_inicio`, `data_fim`, `valor_aluguel`, `status`) VALUES
(1, 1, '2025-02-01', '2026-01-31', 1200.00, 'ativo');

-- Atualizar status do apartamento
UPDATE `apartamentos` SET `status` = 'ocupado' WHERE `id` = 1;
*/

-- =============================================
-- FIM DO SEED
-- =============================================
