# BASE250 - Guia de Administração

## Acesso ao Sistema

### 1. Login
- URL: `http://seu-dominio/backend/admin/login.php`
- Credenciais padrão:
  - Email: `admin@base250.com`
  - Senha: `admin123`

⚠️ **IMPORTANTE**: Altere a senha após o primeiro acesso!

## Dashboard

### Visão Geral
O dashboard mostra:
- Total de apartamentos cadastrados
- Número de apartamentos disponíveis
- Número de apartamentos alugados
- Cards com todos os apartamentos

### Cartões de Apartamento
Cada card mostra:
- Foto principal
- Badge de status (verde = disponível, vermelho = alugado)
- Tipo e número do apartamento
- Metragem, quartos e banheiros
- Preço mensal
- Botões de ação

## Gerenciar Apartamentos

### Alterar Status (Disponível ↔ Alugado)

1. No dashboard, localize o apartamento
2. Clique no botão "Marcar Alugado" ou "Marcar Disponível"
3. Confirme a alteração
4. O status será atualizado imediatamente

**Efeito no site público:**
- Apartamentos **alugados** aparecem mas sem preço e sem botão de aluguel
- Ficam visualmente "desabilitados" (opacidade reduzida)

### Editar Apartamento

1. No dashboard, clique em "Editar" no card do apartamento
2. Você verá um formulário completo com:

#### Informações Básicas
- **Número**: Identificação do apartamento (ex: 101, 102, 201)
- **Tipo**: Studio, Loft ou Apartamento
- **Metragem**: Área em m²
- **Quartos**: Número de quartos
- **Banheiros**: Número de banheiros
- **Preço**: Valor mensal em reais
- **Descrição**: Texto descritivo do apartamento

#### Características (Features)
Lista de comodidades e características:
- Geladeira
- Ar condicionado
- Fogão
- Guarda-roupa
- etc.

**Para adicionar uma característica:**
1. Clique em "Adicionar Característica"
2. Digite a característica
3. Repita para adicionar mais

**Para remover:**
- Clique no botão "×" ao lado da característica

#### Galeria de Fotos
Lista de URLs das fotos do apartamento.

**Para adicionar uma foto:**
1. Clique em "Adicionar Foto"
2. Cole a URL completa da imagem (ex: `https://i.ibb.co/...`)
3. Repita para adicionar mais fotos

**Para remover:**
- Clique no botão "×" ao lado da URL

**Dicas:**
- Use serviços como ImgBB, Imgur ou similar para hospedar imagens
- A primeira foto será usada como capa no card
- Mantenha a ordem desejada (primeira será exibida primeiro)

### Salvar Alterações

1. Revise todas as informações
2. Clique em "Salvar Alterações"
3. Aguarde confirmação
4. As mudanças aparecem imediatamente no site público

### Cancelar Edição

- Clique em "Cancelar" ou "Voltar" para retornar ao dashboard sem salvar

## Fluxo de Trabalho Típico

### Quando um apartamento é alugado:
1. Acesse o dashboard
2. Localize o apartamento
3. Clique em "Marcar Alugado"
4. ✓ O apartamento agora aparece no site sem preço/botão

### Quando um apartamento fica disponível:
1. Acesse o dashboard
2. Localize o apartamento
3. Clique em "Marcar Disponível"
4. ✓ O apartamento volta a aparecer com preço e botão

### Para atualizar preço:
1. Clique em "Editar" no apartamento
2. Altere o campo "Preço"
3. Clique em "Salvar Alterações"
4. ✓ Novo preço aparece imediatamente no site

### Para adicionar fotos novas:
1. Faça upload das fotos para um serviço de hospedagem (ImgBB, Imgur)
2. Copie as URLs das imagens
3. Acesse "Editar" no apartamento
4. Role até "Galeria de Fotos"
5. Clique em "Adicionar Foto" e cole cada URL
6. Salve as alterações
7. ✓ Fotos aparecem na galeria do site

## Logout

- Clique no botão "Sair" no canto superior direito
- Você será redirecionado para a tela de login

## Segurança

### Boas Práticas:
1. **Sempre faça logout** após usar o sistema
2. **Não compartilhe** suas credenciais
3. **Use uma senha forte** (mínimo 8 caracteres, letras, números e símbolos)
4. **Altere a senha periodicamente** (a cada 3-6 meses)
5. **Acesse apenas de dispositivos confiáveis**

### Alterar Senha:
Atualmente a alteração de senha deve ser feita diretamente no banco de dados.

Para gerar um hash bcrypt para nova senha:
```php
<?php
echo password_hash('sua_nova_senha', PASSWORD_BCRYPT);
?>
```

Depois atualize no banco:
```sql
UPDATE usuarios 
SET senha = 'hash_gerado_aqui' 
WHERE email = 'admin@base250.com';
```

## Suporte Técnico

Se encontrar problemas:

1. **Erro ao fazer login**
   - Verifique usuário e senha
   - Limpe o cache do navegador
   - Tente em modo anônimo/privado

2. **Apartamento não atualiza**
   - Recarregue a página (F5)
   - Limpe o cache do navegador
   - Verifique se salvou as alterações

3. **Fotos não aparecem**
   - Verifique se a URL está correta
   - Teste abrir a URL diretamente no navegador
   - Certifique-se de que o serviço de hospedagem está ativo

4. **Outros problemas**
   - Email: floripamoso@gmail.com
   - WhatsApp: (48) 99935-2627

## Dicas Úteis

✓ **Salve frequentemente** - Ao fazer muitas alterações, salve periodicamente

✓ **Teste no site** - Após grandes mudanças, abra o site público e verifique

✓ **Backup de fotos** - Mantenha cópias locais das fotos em caso de perda de URLs

✓ **Padrão de nomenclatura** - Mantenha um padrão ao nomear características

✓ **Descrições claras** - Use descrições que ajudem os visitantes a entender o apartamento

✓ **Ordem das fotos** - Coloque as melhores fotos primeiro (planta, sala, quarto)
