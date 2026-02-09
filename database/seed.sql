-- =============================================
-- BASE250 - Dados Iniciais (Seed)
-- Versão: 2.0.0
-- =============================================

-- =============================================
-- USUÁRIO ADMINISTRADOR
-- Email: admin@base250.com
-- Senha: admin123 (ALTERE APÓS PRIMEIRO ACESSO!)
-- =============================================
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `tipo`, `ativo`) VALUES
('Administrador', 'admin@base250.com', '$2y$10$E8.ZcPl1yXZF0oQ8xM6jZuGQZrZ.V1q/BYZGxjVP/Rd5Y9LnQd2SC', 'admin', 1);
-- Nota: Senha 'admin123' com bcrypt

-- =============================================
-- APARTAMENTOS (BASE250 - Itacorubi)
-- 14 unidades com dados completos do site
-- =============================================

-- Studio 101
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('101', 1, 'Studio', 18, 1, 1, 'disponivel', 1600.00, 1600.00, 'Studio completo com cozinha e quarto integrados',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/DH178Q4k/101-Planta-Humanizada-101.png", "https://i.ibb.co/d4MTFP0X/Foto-3-Quarto.jpg", "https://i.ibb.co/B2SQ7F5C/101-2-BWC.jpg", "https://i.ibb.co/SD206gxs/101-3-Cozinha.jpg", "https://i.ibb.co/0yRhhSs3/Foto-1-Cozinha.jpg"]');

-- Studio 102
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('102', 1, 'Studio', 20, 1, 1, 'disponivel', 1400.00, 1400.00, 'Studio espaçoso com cozinha e quarto',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/sdzYJcDV/102-banheiro-2.jpg", "https://i.ibb.co/8nTySG9C/102-cozinha-1.jpg", "https://i.ibb.co/hRXdYsMN/102-cozinha-2.jpg", "https://i.ibb.co/3mxRSy4k/102-quarto.jpg", "https://i.ibb.co/vx5SNStb/102-quarto.jpg"]');

-- Studio 103
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('103', 1, 'Studio', 18, 1, 1, 'disponivel', 1400.00, 1400.00, 'Studio compacto e funcional',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/mVfP3GMx/103-Planta-humanizada-103.png", "https://i.ibb.co/ymx64bDw/103-1-Banheiro.jpg", "https://i.ibb.co/PvQVnrRt/103-2-Cozinha.jpg", "https://i.ibb.co/vvLpzwW3/103-3-Quarto.jpg", "https://i.ibb.co/2YgrZyd1/103-4-Quarto.jpg"]');

-- Loft 104
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('104', 1, 'Loft', 21, 1, 1, 'disponivel', 1800.00, 1800.00, 'Loft moderno com mezanino',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Mezanino", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/GQpKV9ZH/104-1.jpg", "https://i.ibb.co/twpsZmkN/104-2.jpg", "https://i.ibb.co/W4TDJVSx/104-3.jpg", "https://i.ibb.co/gLWfWhCz/104-4.jpg"]');

-- Loft 105
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('105', 1, 'Loft', 25, 1, 1, 'disponivel', 1800.00, 1800.00, 'Loft amplo com mezanino',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Mezanino", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/Kcvf0pL3/105-3.jpg"]');

-- Loft 106
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('106', 1, 'Loft', 23, 1, 1, 'disponivel', 1800.00, 1800.00, 'Loft com mezanino e sala ampla',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Mezanino", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/rGnMSYr7/106-Banheiro-2.jpg", "https://i.ibb.co/qLJjNH3C/106-1-Cozinha.jpg", "https://i.ibb.co/nNgkzpsg/106-3-Mezanino.jpg", "https://i.ibb.co/xSdgd9S0/106-4-Sala.jpg"]');

-- Apartamento 107
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('107', 1, 'Apartamento', 18, 1, 1, 'disponivel', 1600.00, 1600.00, 'Apartamento compacto e aconchegante',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/cSByB9Yn/107-1.jpg", "https://i.ibb.co/QvKHd6XN/107-2.jpg", "https://i.ibb.co/xqYMc0PY/107-3.jpg"]');

-- Apartamento 108
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('108', 1, 'Apartamento', 18, 1, 1, 'disponivel', 1700.00, 1700.00, 'Apartamento bem localizado',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/0VvxXp5r/108-1-Cozinha.jpg", "https://i.ibb.co/r2tX5KZ4/108-2-Banheiro.jpg", "https://i.ibb.co/4ZNjn8tT/108-3-Quarto.jpg"]');

-- Studio 109
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('109', 1, 'Studio', 18, 1, 1, 'disponivel', 1800.00, 1800.00, 'Studio moderno e renovado',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/Fb4XKj0h/109-1-Banheiro.jpg", "https://i.ibb.co/tpNdHpvJ/109-2-Banheiro.jpg", "https://i.ibb.co/bRb5sT9C/109-3-Cozinha.jpg"]');

-- Apartamento 201
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('201', 2, 'Apartamento', 20, 1, 1, 'disponivel', 1800.00, 1800.00, 'Apartamento no segundo andar',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/8nMtYPYS/201-1-Cozinha.jpg", "https://i.ibb.co/gbys1R08/201-2-Corredor.jpg", "https://i.ibb.co/LDMXL61L/201-3-Banheiro.jpg", "https://i.ibb.co/YFdpSrFC/201-4-Quarto.jpg"]');

-- Studio 202
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('202', 2, 'Studio', 18, 1, 1, 'disponivel', 1400.00, 1400.00, 'Studio no segundo andar',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/vvfQGb5d/202-1.jpg", "https://i.ibb.co/35DjvWmh/202-2.jpg", "https://i.ibb.co/rRgKTBVB/202-3.jpg", "https://i.ibb.co/7tbJMvtd/202-4.jpg"]');

-- Studio 203
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('203', 2, 'Studio', 18, 1, 1, 'disponivel', 1200.00, 1200.00, 'Studio econômico no segundo andar',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/9jggkVP/203-1.jpg", "https://i.ibb.co/WvJ5cFfz/203-2.jpg", "https://i.ibb.co/qfrk0Ry/203-3.jpg", "https://i.ibb.co/jvkjmxVY/203-4.jpg"]');

-- Studio 204
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('204', 2, 'Studio', 18, 1, 1, 'disponivel', 1400.00, 1400.00, 'Studio bem iluminado',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/qFD6D3SV/204-1-Cozinha.jpg", "https://i.ibb.co/C3zq46Tt/204-2-Quarto.jpg", "https://i.ibb.co/VY65SDX8/204-3-Quarto.jpg", "https://i.ibb.co/99vVS4N0/204-4-Banheiro.jpg"]');

-- Apartamento 205
INSERT INTO `apartamentos` (`numero`, `andar`, `tipo`, `metragem`, `quartos`, `banheiros`, `status`, `valor_base`, `preco`, `descricao`, `features`, `galeria_fotos`) VALUES
('205', 2, 'Apartamento', 35, 2, 1, 'disponivel', 2000.00, 2000.00, 'Apartamento amplo com 2 quartos e sacada',
'["Geladeira", "Fogão", "Pia com balcão", "Bancada", "Guarda-roupa", "Cama de casal", "Ar condicionado", "Sacada", "2 Quartos", "Bancada em granito", "Box de vidro", "Lavanderia comunitária"]',
'["https://i.ibb.co/WN1m3SdZ/205-1.jpg", "https://i.ibb.co/1fLk3vtS/205-2.jpg", "https://i.ibb.co/ZzkNRzXn/205-3.jpg", "https://i.ibb.co/ymjXk7j9/205-4.jpg", "https://i.ibb.co/mrGN1zRp/205-5.jpg", "https://i.ibb.co/N6LXF9Xk/205-6.jpg", "https://i.ibb.co/JWDM2Gd9/205-7.jpg"]');

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
