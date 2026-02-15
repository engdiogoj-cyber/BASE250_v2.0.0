# BASE250 - Documentação da API

## Visão Geral

A API do BASE250 fornece endpoints RESTful para gerenciar apartamentos. Todos os endpoints retornam JSON.

## Base URL

```
http://seu-dominio/backend/api/
```

## Autenticação

Endpoints que requerem autenticação precisam de uma sessão válida de administrador. Faça login através de `/backend/admin/login.php` primeiro.

## Formato de Resposta

Todas as respostas seguem o formato:

```json
{
  "success": boolean,
  "message": "string",
  "data": object|array|null
}
```

## Endpoints

### 1. Listar Apartamentos

Retorna lista de todos os apartamentos cadastrados.

**Endpoint:** `GET /apartamentos.php`

**Autenticação:** Não requer

**Resposta de Sucesso (200):**

```json
{
  "success": true,
  "message": "Apartamentos carregados com sucesso",
  "data": [
    {
      "id": 1,
      "numero": "101",
      "tipo": "Studio",
      "metragem": 18.00,
      "quartos": 1,
      "banheiros": 1,
      "preco": 1600.00,
      "status": "disponivel",
      "descricao": "Studio completo com cozinha e quarto integrados",
      "features": [
        "Geladeira",
        "Fogão",
        "Ar condicionado",
        "Bancada em granito"
      ],
      "galeria_fotos": [
        "https://i.ibb.co/DH178Q4k/101-Planta-Humanizada-101.png",
        "https://i.ibb.co/d4MTFP0X/Foto-3-Quarto.jpg"
      ],
      "andar": 1
    }
  ]
}
```

**Campos:**

- `id` (int): ID único do apartamento
- `numero` (string): Número do apartamento
- `tipo` (string): Tipo do apartamento (Studio, Loft, Apartamento)
- `metragem` (float): Metragem em m²
- `quartos` (int): Número de quartos
- `banheiros` (int): Número de banheiros
- `preco` (float): Preço mensal em reais
- `status` (string): Status atual (disponivel, alugado)
- `descricao` (string): Descrição detalhada
- `features` (array): Lista de características/comodidades
- `galeria_fotos` (array): URLs das fotos
- `andar` (int): Andar do apartamento

---

### 2. Atualizar Status

Altera o status de um apartamento entre disponível e alugado.

**Endpoint:** `POST /update_status.php`

**Autenticação:** ✅ Requer login de admin

**Request Body:**

```json
{
  "id": 1,
  "status": "alugado"
}
```

**Campos Obrigatórios:**

- `id` (int): ID do apartamento
- `status` (string): Novo status ("disponivel" ou "alugado")

**Resposta de Sucesso (200):**

```json
{
  "success": true,
  "message": "Status atualizado com sucesso",
  "data": {
    "id": 1,
    "numero": "101",
    "status": "alugado"
  }
}
```

**Erros Possíveis:**

- `401`: Não autenticado
- `400`: Status inválido ou campos faltando
- `404`: Apartamento não encontrado
- `405`: Método não permitido (apenas POST)

---

### 3. Atualizar Apartamento

Atualiza informações completas de um apartamento.

**Endpoint:** `POST /update_apartamento.php`

**Autenticação:** ✅ Requer login de admin

**Request Body:**

```json
{
  "id": 1,
  "numero": "101",
  "tipo": "Studio",
  "metragem": 18,
  "quartos": 1,
  "banheiros": 1,
  "preco": 1650,
  "descricao": "Studio renovado com novos móveis",
  "features": [
    "Geladeira",
    "Fogão 4 bocas",
    "Ar condicionado split",
    "Bancada em granito",
    "Box de vidro"
  ],
  "galeria_fotos": [
    "https://i.ibb.co/DH178Q4k/101-Planta-Humanizada-101.png",
    "https://i.ibb.co/d4MTFP0X/Foto-3-Quarto.jpg",
    "https://i.ibb.co/B2SQ7F5C/101-2-BWC.jpg"
  ]
}
```

**Campos:**

- `id` (int) *obrigatório*: ID do apartamento
- `numero` (string) *opcional*: Número do apartamento
- `tipo` (string) *opcional*: Tipo (Studio, Loft, Apartamento)
- `metragem` (float) *opcional*: Metragem em m²
- `quartos` (int) *opcional*: Número de quartos
- `banheiros` (int) *opcional*: Número de banheiros
- `preco` (float) *opcional*: Preço mensal
- `descricao` (string) *opcional*: Descrição
- `features` (array) *opcional*: Lista de características
- `galeria_fotos` (array) *opcional*: Lista de URLs de fotos

**Resposta de Sucesso (200):**

```json
{
  "success": true,
  "message": "Apartamento atualizado com sucesso",
  "data": {
    "id": 1,
    "numero": "101",
    "tipo": "Studio",
    "metragem": 18.00,
    "quartos": 1,
    "banheiros": 1,
    "preco": 1650.00,
    "status": "disponivel",
    "descricao": "Studio renovado com novos móveis",
    "features": ["Geladeira", "Fogão 4 bocas", ...],
    "galeria_fotos": ["https://...", ...]
  }
}
```

**Erros Possíveis:**

- `401`: Não autenticado
- `400`: ID faltando ou nenhum campo para atualizar
- `404`: Apartamento não encontrado
- `405`: Método não permitido (apenas POST)

---

## Exemplos de Uso

### JavaScript (Fetch API)

```javascript
// Listar apartamentos
async function listarApartamentos() {
  const response = await fetch('/backend/api/apartamentos.php');
  const data = await response.json();
  
  if (data.success) {
    console.log('Apartamentos:', data.data);
  }
}

// Atualizar status (requer login)
async function alterarStatus(id, novoStatus) {
  const response = await fetch('/backend/api/update_status.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: id,
      status: novoStatus
    })
  });
  
  const data = await response.json();
  
  if (data.success) {
    console.log('Status atualizado:', data.data);
  } else {
    console.error('Erro:', data.message);
  }
}

// Atualizar apartamento completo
async function atualizarApartamento(dados) {
  const response = await fetch('/backend/api/update_apartamento.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(dados)
  });
  
  const result = await response.json();
  return result;
}
```

### PHP

```php
// Listar apartamentos
$response = file_get_contents('http://seu-dominio/backend/api/apartamentos.php');
$data = json_decode($response, true);

if ($data['success']) {
    foreach ($data['data'] as $apartamento) {
        echo $apartamento['numero'] . ' - ' . $apartamento['tipo'] . PHP_EOL;
    }
}
```

### cURL

```bash
# Listar apartamentos
curl http://seu-dominio/backend/api/apartamentos.php

# Atualizar status (após login)
curl -X POST http://seu-dominio/backend/api/update_status.php \
  -H "Content-Type: application/json" \
  -d '{"id": 1, "status": "alugado"}' \
  --cookie "PHPSESSID=seu_session_id"
```

## Códigos de Status HTTP

- `200`: Sucesso
- `400`: Requisição inválida (dados faltando ou incorretos)
- `401`: Não autenticado
- `404`: Recurso não encontrado
- `405`: Método HTTP não permitido
- `500`: Erro interno do servidor

## Segurança

1. **CSRF**: Para produção, implemente tokens CSRF para requisições POST
2. **Rate Limiting**: Considere implementar rate limiting para prevenir abuso
3. **HTTPS**: Sempre use HTTPS em produção
4. **Validação**: Todos os dados são validados e sanitizados no backend

## Suporte

Para dúvidas ou problemas com a API:
- Email: floripamoso@gmail.com
- WhatsApp: (48) 99935-2627
