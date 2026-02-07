# 📋 PROMPT COMPLETO - SISTEMA BASE 44
## Sistema de Gestão de Aluguel de 15 Apartamentos

---

## 🔍 ANÁLISE COMPARATIVA DO SEU DOCUMENTO

### ✅ PONTOS FORTES DO SEU PROMPT (já estão muito bons):

1. **Integração Google Forms** - Excelente! Específica e bem detalhada
2. **Modelo de contrato específico** - Você já tem o template definido com campos variáveis
3. **Declaração de residência** - Modelo oficial completo com texto jurídico
4. **Reajuste automático IGPM** - Funcionalidade essencial que eu não tinha detalhado
5. **Emissão de PDFs específicos** - Ficha cadastral, declaração, etc.
6. **Dados do locador fixos** - JUCEMAR JOÃO DA SILVA já definido
7. **Fluxo operacional detalhado** - Etapas bem definidas

### ⚠️ PONTOS QUE PRECISAM SER COMPLEMENTADOS:

1. **Área do Inquilino (Dashboard)** - Falta detalhar a interface completa
2. **Sistema de Notificações** - Não há menção a e-mails automáticos
3. **Controle de Pagamentos Mensal** - Está mencionado mas precisa de mais detalhes
4. **Botão Gov.br** - Precisa ser mais destacado visualmente
5. **Validações de Segurança** - CPF, arquivos, formatos
6. **Relatórios e Exportação** - Falta especificar mais tipos
7. **Histórico e Logs** - Rastreabilidade das ações

---

## 📄 DOCUMENTO CONSOLIDADO

---

## 1. ESTRUTURA DE MÓDULOS

### 1.1. APARTAMENTOS

#### Campos de Cadastro:
```
• Número do apartamento *
• Área útil (m²) *
• Número de quartos *
• Número de banheiros *
• Andar
• Vagas de garagem
• Valor do aluguel *
• Valor do IPTU *
• Valor do condomínio *
• Status: [Disponível / Alugado / Manutenção] *
• Descrição completa *
• Aceita pets: [Sim / Não]
• Mobiliado: [Sim / Não / Semi-mobiliado]
• Data de disponibilidade
• Até 6 fotos *
```

#### Sistema de Fotos:
```
• Ordem de exibição (1ª foto = capa do anúncio)
• Legenda em cada foto (ex: "Sala", "Cozinha", "Quarto")
• Upload: formatos JPG, PNG, WEBP
• Tamanho máximo: 5MB por foto
• Crop/resize automático para padronizar (800x600px)
• Compressão automática
```

#### Página Pública de Divulgação:

**URL compartilhável única:**
```
Formato: https://seudominio.com/imoveis
ou: https://seudominio.com/base44

Recursos:
• Listagem em cards (grid responsivo)
• Filtros: valor, quartos, metragem, aceita pets
• Cada card exibe:
  - Foto principal (1ª da galeria)
  - Número do apto + andar
  - Quartos e banheiros
  - Metragem
  - Valor do aluguel + condomínio + IPTU
  - Badge: "DISPONÍVEL"
  - Botão "QUERO ALUGAR"
```

**Comportamento:**
```
• Mostrar APENAS apartamentos com status "Disponível"
• Quando marcado como "Alugado" → ocultar automaticamente
• Atualização em tempo real
• Responsivo (mobile, tablet, desktop)
```

---

### 1.2. INQUILINOS

#### ETAPA 1 – Cadastro Básico (via Google Forms ou Plataforma)

**Integração Google Forms Existente:**

URL: https://docs.google.com/forms/d/e/1FAIpQLSe5MCN3g-EAgep88ovBmDUa14JrNhetjzGcq6EoaYy-W33_0w/viewform

**Planilha vinculada:** "Informações do locatário (respostas)"

**Regras de Filtragem Automática:**
```
1. Ler todas as respostas da planilha Google Sheets
2. Agrupar por número do apartamento
3. Selecionar a linha com a data mais recente de cada apartamento
4. Ignorar registros:
   - Sem data válida
   - Com status "Saída"
5. Criar automaticamente os registros no sistema
6. Status inicial: "Em análise"
```

**Sincronização:**
```
• Verificar planilha a cada 15 minutos (webhook ou polling)
• Se planilha for substituída ou atualizada → reprocessar tudo
• Alternativa: upload manual de arquivo .xlsx (mesma estrutura)
```

**Campos Principais:**
```
• Nome completo *
• CPF * (validar formato)
• RG *
• E-mail *
• Telefone/WhatsApp *
• Endereço completo *
• Nacionalidade *
• Estado civil *
• Profissão/Ocupação *
• Renda mensal * (R$)
• Apartamento desejado *
• Data de entrada desejada *
• Valor de aluguel proposto
• Valor de caução
```

#### ETAPA 2 – Cadastro Completo (Área do Inquilino)

Após aprovação inicial, inquilino recebe:
- E-mail com login/senha
- Link para área exclusiva

**Área do Inquilino - Dashboard:**

```
┌─────────────────────────────────────────┐
│  👤 Bem-vindo, [NOME DO INQUILINO]      │
│                                         │
│  🏠 Apartamento: [NÚMERO]               │
│  📊 Status: [EM ANÁLISE]                │
│                                         │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
│                                         │
│  📋 MEUS DADOS                          │
│  └─ Visualizar e completar cadastro    │
│                                         │
│  📄 MEU CONTRATO                        │
│  └─ Baixar, assinar e enviar           │
│                                         │
│  📎 MEUS DOCUMENTOS                     │
│  └─ Enviar RG, CPF, comprovantes       │
│                                         │
│  💰 PAGAMENTOS                          │
│  └─ Histórico e boletos                │
│                                         │
│  🔔 NOTIFICAÇÕES (3 novas)             │
│  └─ Mensagens do proprietário          │
│                                         │
│  ⚙️ CONFIGURAÇÕES                       │
│  └─ Alterar senha, dados de contato    │
└─────────────────────────────────────────┘
```

**Campos de Upload (implementar):**
```
• foto_3x4_url (formato: JPG/PNG, máx 2MB)
• documento_frente_url (RG frente)
• documento_verso_url (RG verso)
• comprovante_cpf_url
• comprovante_renda_1_url (últimos 3 meses)
• comprovante_renda_2_url
• comprovante_renda_3_url
• comprovante_residencia_url
• contrato_assinado_url (após assinatura)
```

**Validações de Upload:**
```
• Formatos aceitos: PDF, JPG, PNG
• Tamanho máximo: 10MB por arquivo
• Scan automático de vírus/malware
• Nome do arquivo sanitizado
• Criptografia no armazenamento
```

---

### 1.3. ADMINISTRAÇÃO

#### Painel Principal com Abas:

**ABA 1: APARTAMENTOS**

Visualização em cards ou tabela:

```
┌──────────────────────────────────────────────┐
│  [+ NOVO APARTAMENTO]    [EXPORTAR LISTA]   │
├──────────────────────────────────────────────┤
│                                              │
│  ┌────────────┐  ┌────────────┐            │
│  │ 🏠 Apto 101│  │ 🏠 Apto 102│            │
│  │ 2Q • 65m²  │  │ 3Q • 85m²  │            │
│  │ R$ 1.800   │  │ R$ 2.500   │            │
│  │            │  │            │            │
│  │ ✅ DISPONÍVEL│  │ 🔴 ALUGADO │            │
│  │            │  │ João Silva │            │
│  │ [Editar]   │  │ [Ver Dados]│            │
│  │ [Fotos]    │  │ [Contrato] │            │
│  └────────────┘  └────────────┘            │
└──────────────────────────────────────────────┘
```

**Ações disponíveis:**
- Editar informações
- Gerenciar fotos (arrastar para reordenar)
- Mudar status manualmente
- Ver histórico de inquilinos
- Duplicar apartamento (para facilitar cadastros similares)

---

**ABA 2: INQUILINOS**

Filtros: `[Todos] [Em Análise] [Contrato Liberado] [Assinado] [Ativo] [Encerrado]`

Tabela:
```
┌──────────┬────────────────┬─────────────┬──────────────────┬─────────┐
│ Data     │ Nome           │ Apto Desej. │ Status           │ Ações   │
├──────────┼────────────────┼─────────────┼──────────────────┼─────────┤
│ 07/11/25 │ Maria Santos   │ Apto 301    │ Em análise       │[Analisar]│
│ 06/11/25 │ José Silva     │ Apto 102    │ Contrato liberado│ [Ver]    │
│ 05/11/25 │ Ana Costa      │ Apto 205    │ Ativo            │ [Ver]    │
│ 04/11/25 │ Pedro Lima     │ Apto 101    │ Encerrado        │ [Ver]    │
└──────────┴────────────────┴─────────────┴──────────────────┴─────────┘
```

**Ao clicar em [ANALISAR]:**
```
Painel lateral ou modal com:

DADOS PESSOAIS:
✓ Nome: [nome completo]
✓ CPF: [xxx.xxx.xxx-xx] ← validar formato
✓ RG: [xxxxxxxxx]
✓ Data nasc: [dd/mm/aaaa] - Idade: [XX anos]
✓ Estado civil: [casado/solteiro/etc]
✓ Profissão: [cargo]
✓ Renda mensal: R$ [valor]

DOCUMENTOS ENVIADOS:
□ RG (frente) ← [Visualizar] [Download]
□ RG (verso) ← [Visualizar] [Download]
□ CPF ← [Visualizar] [Download]
□ Comprovante de renda (3 últimos) ← [Download ZIP]
□ Comprovante de residência ← [Visualizar]
□ Foto 3x4 ← [Visualizar]

ANÁLISE DE RENDA:
• Renda informada: R$ [valor]
• Aluguel solicitado: R$ [valor]
• Proporção: [XX%] ← alerta se > 33%
• ⚠️ Renda deve ser mínimo 3x o valor do aluguel

CHECKLIST DE VERIFICAÇÃO:
□ RG verificado e legível
□ CPF validado (verificar dígitos)
□ Renda compatível (mínimo 3x aluguel)
□ Documentos autênticos
□ Referências checadas (se fornecidas)

OBSERVAÇÕES INTERNAS:
[Campo de texto livre para notas do administrador]

[❌ REPROVAR]     [✅ APROVAR E GERAR CONTRATO]
```

---

**ABA 3: CONTRATOS**

Status possíveis:
- 📝 **Aguardando geração**
- ⏳ **Aguardando assinatura**
- ✅ **Assinado** (aguardando validação)
- 🔒 **Ativo**
- 📂 **Encerrado**

Listagem:
```
┌────────────────────────────────────────────────────────────┐
│ Filtro: [Todos] [Aguardando] [Assinados] [Ativos]         │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  📄 Contrato - Apto 301 - Maria Santos                    │
│  Status: ⏳ Aguardando assinatura                          │
│  Gerado em: 06/11/2025 às 14:30                           │
│  Enviado ao inquilino: Sim                                │
│                                                            │
│  [📥 Download PDF]  [📧 Reenviar Email]  [✏️ Editar]      │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                            │
│  📄 Contrato - Apto 102 - José Silva                      │
│  Status: 🔒 Ativo                                          │
│  Assinado em: 01/11/2025                                  │
│  Início: 10/11/2025 | Término: 10/11/2026                 │
│                                                            │
│  [📥 Download Contrato]  [📄 Ver Detalhes]                │
└────────────────────────────────────────────────────────────┘
```

**Processo de Geração de Contrato:**

Ao clicar em "APROVAR E GERAR CONTRATO":

```
1. Sistema abre modal de geração

2. MODELO DE CONTRATO:
   Template: contrato_Ano_Apto_Nome - MODELO-r1.docx
   
3. CAMPOS AUTOMÁTICOS (preenchidos automaticamente):
   {{nome_locatario}} ← Do cadastro
   {{cpf_locatario}} ← Do cadastro
   {{rg_locatario}} ← Do cadastro
   {{endereco_locatario}} ← Do cadastro
   {{numero_apartamento}} ← Do apartamento
   {{data_entrada}} ← Data de início
   {{data_saida}} ← Data entrada + 12 meses
   {{valor_aluguel}} ← Valor numérico
   {{valor_aluguel_extenso}} ← Por extenso
   {{valor_caucao}} ← Valor da caução
   {{valor_caucao_extenso}} ← Por extenso
   {{data_hoje}} ← Data atual
   {{testemunha_1}} ← Campo editável
   {{testemunha_2}} ← Campo editável

4. LOCADOR (FIXO):
   JUCEMAR JOÃO DA SILVA
   CPF: 399.328.349-04
   Endereço: Servidão Joaquim Soares, nº 250
   Bairro: Itacorubi
   Cidade: Florianópolis/SC
   Telefone: (48) 99935-2627

5. Permitir REVISÃO antes de finalizar

6. [CANCELAR]  [GERAR CONTRATO PDF]

7. Após gerar:
   - Salvar PDF no sistema
   - Mudar status para "Aguardando assinatura"
   - Enviar e-mail automático ao inquilino
```

---

**ABA 4: CONTROLE MENSAL** ⭐

Visão: `[Mês Atual ▼] [Novembro 2025]`

**Resumo do Mês:**
```
┌─────────────────────────────────────────────────────────┐
│  📊 RESUMO FINANCEIRO - NOVEMBRO 2025                   │
│                                                         │
│  💰 Total a receber: R$ 27.500,00                      │
│  ✅ Recebido: R$ 18.200,00 (66%)                       │
│  ⏳ Pendente: R$ 7.500,00 (27%)                        │
│  ❌ Atrasado: R$ 1.800,00 (7%) - 2 apartamentos        │
│                                                         │
│  [📊 Ver Gráfico]  [📥 Exportar Excel/PDF]            │
└─────────────────────────────────────────────────────────┘
```

**Tabela de Pagamentos:**
```
┌───────┬─────────────┬───────────┬────────┬────────────┬─────────┐
│ Apto  │ Inquilino   │ Valor     │ Venc.  │ Status     │ Ações   │
├───────┼─────────────┼───────────┼────────┼────────────┼─────────┤
│ 101   │ João Silva  │ R$ 2.150  │ 10/11  │ ✅ PAGO    │ [Ver]   │
│       │             │           │        │ Pago 08/11 │         │
├───────┼─────────────┼───────────┼────────┼────────────┼─────────┤
│ 102   │ Maria Santos│ R$ 2.850  │ 10/11  │ ⏳ PENDENTE│[Registrar]│
│       │             │           │        │            │[Lembrete]│
├───────┼─────────────┼───────────┼────────┼────────────┼─────────┤
│ 103   │ Pedro Costa │ R$ 1.800  │ 05/11  │ ❌ ATRASADO│[Registrar]│
│       │             │           │        │ 4 dias     │[Notificar]│
└───────┴─────────────┴───────────┴────────┴────────────┴─────────┘
```

**Registrar Pagamento:**
```
Modal:
• Data do pagamento: [dd/mm/aaaa]
• Valor pago: R$ [valor]
• Forma: [PIX / Transferência / Dinheiro / Boleto]
• Upload do comprovante (opcional)
• Observações: [campo texto]

[CANCELAR]  [REGISTRAR PAGAMENTO]
```

**Reajuste Anual Automático:**

Tabela de Reajustes:
```
┌───────┬─────────────┬──────────┬────────────┬────────────┬──────────┐
│ Apto  │ Inquilino   │ Valor    │ Data Início│ Reajuste em│ Novo Valor│
├───────┼─────────────┼──────────┼────────────┼────────────┼──────────┤
│ 101   │ João Silva  │ R$ 2.150 │ 10/11/2024 │ 10/11/2025 │ R$ 2.280 │
│       │             │          │            │(⚠️ em 1 dia)│ (+6.05%) │
├───────┼─────────────┼──────────┼────────────┼────────────┼──────────┤
│ 102   │ Maria Santos│ R$ 2.850 │ 15/01/2025 │ 15/01/2026 │ R$ 3.022 │
│       │             │          │            │ (67 dias)  │ (+6.05%) │
└───────┴─────────────┴──────────┴────────────┴────────────┴──────────┘
```

**Regras de Reajuste:**
```
• Cálculo automático com base no IGPM
• Fórmula: novo_valor = valor_atual * (1 + (indice_IGPM / 100))
• Exibir aviso ⚠️ 30 dias antes da data de reajuste
• Gerar notificação automática ao inquilino
• Índice IGPM editável em "Configurações"
```

---

**ABA 5: CONFIGURAÇÕES**

**1. Modelo de Contrato Vigente:**
```
📄 Arquivo atual: contrato_Ano_Apto_Nome - MODELO-r1.docx
📅 Última atualização: 01/10/2025
👤 Por: Administrador

[📤 SUBSTITUIR MODELO (.docx)]

Histórico de versões:
• v1 - 01/10/2025 - Administrador - [Download]
• v0 - 15/08/2025 - Administrador - [Download]
```

**2. Reajuste Anual:**
```
💹 Índice IGPM atual: [____]% 
(usado para cálculo de reajuste automático)

Última atualização: [dd/mm/aaaa]
Fonte: FGV

[SALVAR]
```

**3. Integração Google Forms:**
```
📊 Planilha vinculada:
URL: [https://docs.google.com/spreadsheets/d/...]

Status: ✅ Conectado
Última sincronização: 09/11/2025 14:30

[RECONECTAR] [ATUALIZAR MANUALMENTE]
```

**4. Dados do Locador (fixo):**
```
Nome: JUCEMAR JOÃO DA SILVA
CPF: 399.328.349-04
Endereço: Servidão Joaquim Soares, nº 250
Bairro: Itacorubi
Cidade: Florianópolis
Estado: SC
Telefone: (48) 99935-2627
E-mail: [email@exemplo.com]

[EDITAR]
```

**5. Notificações por E-mail:**
```
□ Enviar e-mail quando novo cadastro chegar
□ Enviar lembrete 5 dias antes do vencimento
□ Enviar notificação no dia do vencimento
□ Enviar alerta de atraso (3 dias após vencimento)

Template de e-mail: [EDITAR TEMPLATES]
```

**6. Backup e Exportação:**
```
📦 Backup automático: [Diário] às [02:00]
Último backup: 09/11/2025 02:00

[🔽 BAIXAR BACKUP COMPLETO (.zip)]
[📤 EXPORTAR DADOS (Excel)]
```

---

## 2. INTEGRAÇÃO COM GOOGLE FORMS

**Formulário Existente:**
URL: https://docs.google.com/forms/d/e/1FAIpQLSe5MCN3g-EAgep88ovBmDUa14JrNhetjzGcq6EoaYy-W33_0w/viewform

**Planilha de Respostas:**
"Informações do locatário (respostas)"

### Regras de Sincronização:

```javascript
ALGORITMO DE FILTRAGEM:

1. Conectar à planilha Google Sheets via API
2. Ler todas as linhas
3. Para cada linha:
   a) Verificar se tem "número do apartamento"
   b) Verificar se tem "data válida"
   c) Verificar se status ≠ "Saída"
4. Agrupar por número do apartamento
5. Para cada grupo:
   a) Ordenar por data (mais recente primeiro)
   b) Selecionar a linha mais recente
6. Criar/atualizar registro no sistema:
   - Status inicial: "Em análise"
   - Enviar e-mail ao candidato (se novo)
7. Salvar log de sincronização

PERIODICIDADE:
• Automático: a cada 15 minutos (webhook ou polling)
• Manual: botão "Sincronizar Agora"

TRATAMENTO DE ERROS:
• Linhas duplicadas: ignorar
• Campos obrigatórios vazios: marcar como "Dados incompletos"
• Data inválida: ignorar registro
```

**Alternativa Offline:**
```
Upload Manual:
1. Administrador faz download da planilha (.xlsx)
2. Clica em "Upload Manual" na plataforma
3. Seleciona arquivo .xlsx
4. Sistema processa com as mesmas regras
5. Exibe relatório de importação:
   - X registros novos criados
   - Y registros atualizados
   - Z registros ignorados (motivo)
```

---

## 3. FLUXO OPERACIONAL COMPLETO

### ETAPA 1 – Divulgação

```
1. Administrador cadastra apartamento
2. Faz upload de até 6 fotos
3. Define status como "Disponível"
4. Apartamento aparece na página pública
5. Link compartilhável: https://seudominio.com/imoveis
```

### ETAPA 2 – Cadastro do Inquilino

**Opção A: Via Google Forms**
```
1. Interessado preenche Google Forms
2. Sistema sincroniza automaticamente
3. Cria registro com status "Em análise"
4. Envia e-mail ao candidato:
   "Recebemos seu interesse! Aguarde contato."
```

**Opção B: Direto na Plataforma**
```
1. Interessado clica "Quero alugar" no site
2. Preenche formulário de cadastro
3. Sistema cria registro "Em análise"
4. Envia e-mail com login/senha
```

### ETAPA 3 – Aprovação e Geração do Contrato

```
1. Administrador recebe notificação de novo cadastro
2. Acessa aba "Inquilinos" → clica em [Analisar]
3. Revisa todos os dados e documentos
4. Faz checklist de verificação
5. Clica em "APROVAR E GERAR CONTRATO"
6. Sistema:
   a) Usa template oficial contrato_Ano_Apto_Nome - MODELO-r1.docx
   b) Preenche campos variáveis automaticamente
   c) Gera PDF
   d) Muda status para "Contrato liberado"
   e) Envia e-mail ao inquilino com link de acesso
```

**Campos Automáticos do Contrato:**
```
{{nome_locatario}} = Nome do inquilino
{{cpf_locatario}} = CPF do inquilino
{{rg_locatario}} = RG do inquilino
{{endereco_locatario}} = Endereço atual
{{numero_apartamento}} = Número do apto
{{data_entrada}} = Data de início
{{data_saida}} = Data entrada + 12 meses
{{valor_aluguel}} = Valor numérico
{{valor_aluguel_extenso}} = Por extenso
{{valor_caucao}} = Valor da caução
{{valor_caucao_extenso}} = Por extenso
{{data_hoje}} = Data de geração
{{testemunha_1}} = Nome testemunha 1
{{testemunha_2}} = Nome testemunha 2
```

**Dados Fixos do Locador:**
```
JUCEMAR JOÃO DA SILVA
CPF: 399.328.349-04
Endereço: Servidão Joaquim Soares, nº 250
Bairro: Itacorubi
Cidade: Florianópolis/SC
Telefone: (48) 99935-2627
```

### ETAPA 4 – Assinatura via Gov.br ⭐

**Na Área do Inquilino:**

```html
┌─────────────────────────────────────────────────────────┐
│               📄 MEU CONTRATO                           │
│                                                         │
│  Status: ⏳ Aguardando sua assinatura                  │
│  Data de geração: 06/11/2025                           │
│                                                         │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                         │
│  PASSO 1: Baixe o contrato                             │
│  📥 [BAIXAR CONTRATO PDF]                              │
│                                                         │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                         │
│  ⚠️ IMPORTANTE: ASSINE SEU CONTRATO DIGITALMENTE      │
│                                                         │
│  ┌───────────────────────────────────────────────────┐│
│  │                                                   ││
│  │    🔐  ASSINAR PELO GOV.BR                       ││
│  │                                                   ││
│  │    ✓ Assinatura digital com validade jurídica   ││
│  │    ✓ Reconhecida nacionalmente                   ││
│  │    ✓ Rápido, seguro e gratuito                   ││
│  │                                                   ││
│  │    👉 [CLIQUE AQUI PARA ASSINAR]                 ││
│  │                                                   ││
│  └───────────────────────────────────────────────────┘│
│                                                         │
│  📌 Como funciona:                                      │
│  1️⃣ Clique no botão verde acima                        │
│  2️⃣ Você será direcionado ao site oficial do Gov.br   │
│  3️⃣ Faça login com sua conta gov.br                   │
│  4️⃣ Assine o documento digitalmente                   │
│  5️⃣ Baixe o PDF assinado                              │
│  6️⃣ Retorne aqui e faça upload                        │
│                                                         │
│  Link direto Gov.br:                                   │
│  🔗 https://www.gov.br/pt-br/servicos/assinatura-eletronica│
│                                                         │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                         │
│  PASSO 2: Envie o contrato assinado                    │
│                                                         │
│  📎 [FAZER UPLOAD DO CONTRATO ASSINADO]               │
│                                                         │
│  Formato aceito: PDF                                   │
│  Tamanho máximo: 10MB                                  │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

**Estilo Visual do Botão (CSS):**
```css
.btn-assinar-govbr {
  background: linear-gradient(135deg, #00A859 0%, #00D166 100%);
  color: white;
  font-size: 22px;
  font-weight: 700;
  padding: 20px 40px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  box-shadow: 0 6px 20px rgba(0, 168, 89, 0.4);
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  width: 100%;
  margin: 20px 0;
  animation: pulse-border 2s infinite;
}

.btn-assinar-govbr:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(0, 168, 89, 0.6);
  background: linear-gradient(135deg, #00C865 0%, #00E672 100%);
}

@keyframes pulse-border {
  0%, 100% {
    box-shadow: 0 6px 20px rgba(0, 168, 89, 0.4);
  }
  50% {
    box-shadow: 0 6px 30px rgba(0, 168, 89, 0.7);
  }
}

.btn-assinar-govbr::before {
  content: "🔐";
  font-size: 28px;
}
```

### ETAPA 5 – Envio de Documentos

Ainda na Área do Inquilino:

```
┌─────────────────────────────────────────────────────────┐
│            📎 ENVIAR MEUS DOCUMENTOS                    │
│                                                         │
│  Por favor, envie os documentos abaixo:                │
│                                                         │
│  ✅ Contrato assinado                                  │
│     [Arquivo enviado: contrato_assinado.pdf]           │
│     Data: 07/11/2025 10:30                             │
│                                                         │
│  📄 RG (frente)                                        │
│     [ESCOLHER ARQUIVO] ou [TIRAR FOTO]                │
│                                                         │
│  📄 RG (verso)                                         │
│     [ESCOLHER ARQUIVO] ou [TIRAR FOTO]                │
│                                                         │
│  📄 CPF                                                │
│     [ESCOLHER ARQUIVO]                                 │
│                                                         │
│  📄 Comprovante de Renda (últimos 3 meses)            │
│     [ESCOLHER ARQUIVOS] (pode enviar múltiplos)       │
│                                                         │
│  📄 Comprovante de Residência                          │
│     [ESCOLHER ARQUIVO]                                 │
│                                                         │
│  📷 Foto 3x4                                           │
│     [ESCOLHER ARQUIVO] ou [TIRAR FOTO]                │
│                                                         │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│                                                         │
│  [ENVIAR TODOS OS DOCUMENTOS]                          │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

**Após envio:**
```
1. Status muda para: "Assinado - aguardando validação"
2. Notificação enviada ao administrador:
   "Maria Santos enviou contrato assinado e documentos"
3. Administrador revisa na aba "Contratos"
4. Se OK → Clica em "VALIDAR E ATIVAR"
5. Status muda para: "Ativo"
6. Apartamento muda para status: "Alugado"
7. Apartamento some da página pública
8. Inquilino recebe e-mail de boas-vindas
```

---

## 4. EMISSÃO DE PDFs

### 4.1. Ficha Cadastral do Inquilino

**Geração automática em PDF:**

Conteúdo:
```
─────────────────────────────────────────
     FICHA CADASTRAL DE LOCAÇÃO
─────────────────────────────────────────

[FOTO 3x4]            DADOS PESSOAIS
                      Nome: [nome completo]
                      CPF: [xxx.xxx.xxx-xx]
                      RG: [xxxxxxxxx]
                      Data Nasc: [dd/mm/aaaa]
                      Naturalidade: [cidade/UF]
                      Nacionalidade: [brasileiro]
                      Estado Civil: [casado/solteiro]

CONTATOS
Telefone: [(XX) XXXXX-XXXX]
E-mail: [email@exemplo.com]
Endereço atual: [rua, número, bairro, cidade/UF, CEP]

DADOS PROFISSIONAIS
Profissão: [cargo/ocupação]
Renda mensal: R$ [X.XXX,XX]

DOCUMENTOS ANEXADOS
□ RG (frente)        [✓ Enviado]
□ RG (verso)         [✓ Enviado]
□ CPF                [✓ Enviado]
□ Comprovante Renda  [✓ Enviado]
□ Comprovante Resid. [✓ Enviado]

─────────────────────────────────────────
Data de emissão: [dd/mm/aaaa]
Sistema Base 44 - Gestão de Aluguel
─────────────────────────────────────────
```

**Layout:**
- Formato A4 (portrait)
- Foto 3x4 no canto superior esquerdo
- Sem campo de assinatura
- Logo/marca d'água opcional

### 4.2. Contrato Assinado

- Download direto do arquivo `contrato_assinado_url`
- Nome do arquivo: `Contrato_AnoApto_Nome.pdf`
- Exemplo: `Contrato_2025301_MariaSantos.pdf`

### 4.3. Declaração de Residência / Aluguel

**Template Oficial:**

```
────────────────────────────────────────────────────────
           DECLARAÇÃO DE RESIDÊNCIA
────────────────────────────────────────────────────────

Eu, abaixo assinado, Sr(a) JUCEMAR JOÃO DA SILVA, 
portador do CPF nº 399.328.349-04, residente na Servidão 
Joaquim Soares, nº 250, bairro Itacorubi, Florianópolis/SC, 
telefone (48) 99935-2627, declaro para os devidos fins de 
direito e sob as penas da Lei que alugo o imóvel sito à 
Servidão Joaquim Soares, nº 250, Apartamento {{numero_apartamento}}, 
bairro Itacorubi, Florianópolis/SC, para {{nome_locatario}}, 
portador(a) do CPF {{cpf_locatario}} e RG {{rg_locatario}}, 
desde {{data_inicio_contrato}}, sendo cobrado o aluguel no 
valor de R$ {{valor_aluguel}} ({{valor_aluguel_extenso}}).

────────────────────────────────────────────────────────

"Art. 299 – Omitir, em documento público ou particular, 
declaração que nele deveria constar, ou nele inserir ou fazer 
inserir declaração falsa ou diversa da que devia ser escrita, 
com o fim de prejudicar direito, criar obrigação ou alterar a 
verdade sobre fato juridicamente relevante.

Pena: reclusão de 1 (um) a 5 (cinco) anos e multa, se o 
documento é público e reclusão de 1 (um) a 3 (três) anos, 
se o documento é particular."

────────────────────────────────────────────────────────

Florianópolis/SC, {{data_hoje}}.


____________________________________
JUCEMAR JOÃO DA SILVA
CPF 399.328.349-04

────────────────────────────────────────────────────────
```

**Funcionalidade:**
- Geração automática a partir dos dados do contrato
- Botão "Gerar Declaração" na área do administrador
- Download imediato em PDF
- Pode ser enviada diretamente ao inquilino por e-mail

---

## 5. CONTROLE MENSAL E REAJUSTE AUTOMÁTICO

### 5.1. Tabela de Controle

**Dados exibidos:**
```
• Contrato (link para detalhes)
• Nome do inquilino
• Apartamento
• Valor aluguel atual (R$)
• Data de início do contrato
• Data do próximo reajuste (1 ano após início)
• Valor reajustado previsto (com base no IGPM)
• Observações
```

### 5.2. Regras de Reajuste Automático

**Cálculo:**
```
Fórmula: novo_valor = valor_atual * (1 + (indice_IGPM / 100))

Exemplo:
• Valor atual: R$ 2.000,00
• IGPM acumulado 12 meses: 6,05%
• Novo valor: R$ 2.000 * (1 + 0,0605) = R$ 2.121,00
```

**Sistema de Avisos:**
```
• 30 dias antes do reajuste:
  ⚠️ Badge laranja: "Reajuste em 30 dias"
  📧 E-mail automático ao inquilino e administrador
  
• 15 dias antes:
  ⚠️ Notificação in-app
  
• 7 dias antes:
  ⚠️ E-mail de lembrete
  
• No dia do reajuste:
  ✅ Valor atualizado automaticamente
  📧 E-mail de confirmação
```

**Campo IGPM nas Configurações:**
```
💹 Índice IGPM acumulado (últimos 12 meses): [___]%

Fonte: FGV
Última atualização: [dd/mm/aaaa]

[SALVAR ÍNDICE]

⚠️ Este índice será usado para calcular todos os reajustes 
   automáticos quando completarem 12 meses de contrato.
```

### 5.3. Exportação de Relatórios

**Formatos disponíveis:**
- Excel (.xlsx)
- PDF

**Tipos de relatório:**
```
1. Relatório de Pagamentos Mensais
   • Filtrar por mês/ano
   • Exibir todos os apartamentos
   • Status: pago/pendente/atrasado
   • Total geral

2. Relatório de Inadimplência
   • Apenas apartamentos com atraso
   • Dias de atraso
   • Valor devido
   • Histórico de atrasos

3. Relatório de Reajustes
   • Próximos reajustes (90 dias)
   • Valores atuais vs novos valores
   • Percentual de aumento

4. Relatório Anual Completo
   • Total recebido no ano
   • Taxa de ocupação média
   • Inadimplência média
   • Gráficos e estatísticas
```

---

## 6. CONFIGURAÇÕES AVANÇADAS

### 6.1. Modelo de Contrato

**Gerenciamento de Versões:**
```
📄 MODELO VIGENTE
Nome: contrato_Ano_Apto_Nome - MODELO-r1.docx
Data upload: 01/10/2025
Tamanho: 245 KB
Usado em: 12 contratos ativos

[📥 DOWNLOAD]  [🔄 SUBSTITUIR]

────────────────────────────────────────
HISTÓRICO DE VERSÕES

v1 (r1) - 01/10/2025
• Usuário: Admin
• Mudanças: Template inicial
• Usado em 12 contratos
[Download]

────────────────────────────────────────

[📤 FAZER UPLOAD DE NOVO MODELO]

⚠️ IMPORTANTE:
• Formato aceito: .docx
• Manter os campos variáveis: {{campo}}
• Backup automático do modelo anterior
```

### 6.2. Campos Variáveis do Contrato

**Lista de tags disponíveis:**
```
LOCATÁRIO:
{{nome_locatario}}
{{cpf_locatario}}
{{rg_locatario}}
{{endereco_locatario}}
{{telefone_locatario}}
{{email_locatario}}

IMÓVEL:
{{numero_apartamento}}
{{endereco_imovel}}
{{metragem}}
{{quartos}}
{{banheiros}}

VALORES:
{{valor_aluguel}}
{{valor_aluguel_extenso}}
{{valor_caucao}}
{{valor_caucao_extenso}}
{{valor_condominio}}
{{valor_iptu}}
{{valor_total}}

DATAS:
{{data_entrada}}
{{data_saida}}
{{data_hoje}}
{{dia_vencimento}}

OUTROS:
{{testemunha_1}}
{{testemunha_2}}

LOCADOR (fixo):
{{nome_locador}} = JUCEMAR JOÃO DA SILVA
{{cpf_locador}} = 399.328.349-04
{{endereco_locador}} = Servidão Joaquim Soares, nº 250
{{bairro_locador}} = Itacorubi
{{cidade_locador}} = Florianópolis/SC
{{telefone_locador}} = (48) 99935-2627
```

### 6.3. Notificações por E-mail

**Templates Editáveis:**

**1. Novo Cadastro Recebido (para Admin)**
```
Assunto: 🏠 Novo interesse - {{nome}} - Apto {{numero}}

Olá!

Você recebeu um novo cadastro de interesse:

Nome: {{nome}}
Apartamento desejado: {{numero}}
Telefone: {{telefone}}
Renda: R$ {{renda}}

Data: {{data}}

Acesse o painel para analisar: {{link_painel}}
```

**2. Cadastro Aprovado (para Inquilino)**
```
Assunto: ✅ Parabéns! Seu cadastro foi aprovado

Olá {{nome}},

Seu cadastro para o Apartamento {{numero}} foi aprovado!

Próximos passos:
1. Acesse sua área: {{link_area_inquilino}}
2. Complete seu cadastro
3. Envie os documentos solicitados

Seus dados de acesso:
Login: {{email}}
Senha: {{senha}}

Qualquer dúvida, entre em contato.
```

**3. Contrato Disponível (para Inquilino)**
```
Assunto: 📄 Seu contrato está pronto!

Olá {{nome}},

Seu contrato de locação foi gerado e está disponível para download e assinatura.

Acesse sua área: {{link_area_inquilino}}

O que fazer:
1. Baixe o contrato (PDF)
2. Assine digitalmente pelo Gov.br
3. Faça upload do contrato assinado

Qualquer dúvida, estamos à disposição.
```

**4. Lembrete de Vencimento**
```
Assunto: 💰 Lembrete: Aluguel vence em 5 dias

Olá {{nome}},

Este é um lembrete de que o aluguel do Apartamento {{numero}} 
vence em 5 dias.

Data de vencimento: {{data_vencimento}}
Valor: R$ {{valor}}

Forma de pagamento: [instruções]

Obrigado!
```

**5. Aluguel Atrasado**
```
Assunto: ⚠️ URGENTE: Aluguel em atraso

Olá {{nome}},

Identificamos que o pagamento do aluguel do Apartamento {{numero}} 
está em atraso há {{dias}} dias.

Valor devido: R$ {{valor}}
Vencimento: {{data_vencimento}}

Por favor, regularize o quanto antes.
Contato: {{telefone_admin}}
```

### 6.4. Backup e Segurança

**Configurações de Backup:**
```
🔒 BACKUP AUTOMÁTICO

Frequência: [Diário ▼] às [02:00 ▼]
Retenção: [30 dias ▼]
Armazenamento: Google Drive / AWS S3 / Local

Último backup: 09/11/2025 02:00 ✅
Tamanho: 156 MB
Status: Sucesso

[🔽 BAIXAR ÚLTIMO BACKUP]
[📅 VER HISTÓRICO]
[⚙️ CONFIGURAR]

────────────────────────────────────────
🔐 SEGURANÇA

• Criptografia de documentos: ✅ Ativada (AES-256)
• Autenticação em 2 fatores: ⚠️ Desativada [Ativar]
• Logs de atividade: ✅ Ativados
• LGPD compliance: ✅ Ativado

[VER LOGS DE ACESSO]
```

---

## 7. DESIGN E COMPORTAMENTO

### 7.1. Layout Geral

**Características:**
- Responsivo (mobile-first)
- Limpo e direto
- Navegação intuitiva
- Uso de ícones visuais
- Cores consistentes

**Paleta de Cores:**
```
Primária (ações principais): #3D57FF (azul)
Secundária: #6B7280 (cinza)
Sucesso: #10B981 (verde)
Alerta: #F59E0B (laranja)
Erro: #EF4444 (vermelho)
Gov.br: #00A859 (verde oficial)

Backgrounds:
- Fundo: #F9FAFB (cinza claro)
- Cards: #FFFFFF (branco)
- Hover: #F3F4F6
```

### 7.2. Status e Badges

**Status Possíveis:**

```
APARTAMENTOS:
• 🟢 Disponível (verde)
• 🔴 Alugado (vermelho)
• 🟡 Manutenção (amarelo)

INQUILINOS:
• 🔵 Em análise (azul)
• 🟡 Contrato liberado (amarelo)
• 🟠 Aguardando assinatura (laranja)
• 🟢 Assinado - aguardando validação (verde claro)
• 🟢 Ativo (verde)
• ⚫ Encerrado (cinza)

PAGAMENTOS:
• ✅ Pago (verde)
• ⏳ Pendente (amarelo)
• ❌ Atrasado (vermelho)
```

**Estilo de Badge:**
```css
.badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}

.badge-disponivel {
  background: #D1FAE5;
  color: #065F46;
}

.badge-alugado {
  background: #FEE2E2;
  color: #991B1B;
}

.badge-ativo {
  background: #D1FAE5;
  color: #065F46;
}

.badge-atrasado {
  background: #FEE2E2;
  color: #991B1B;
  animation: pulse 2s infinite;
}
```

### 7.3. Botões Principais

```css
.btn-primary {
  background: #3D57FF;
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-primary:hover {
  background: #2943DD;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(61, 87, 255, 0.3);
}

.btn-secondary {
  background: #6B7280;
  color: white;
}

.btn-success {
  background: #10B981;
  color: white;
}

.btn-danger {
  background: #EF4444;
  color: white;
}
```

### 7.4. Responsividade

**Breakpoints:**
```
Mobile: < 768px
Tablet: 768px - 1024px
Desktop: > 1024px
```

**Comportamento Mobile:**
- Menu hamburger
- Cards em coluna única
- Tabelas com scroll horizontal
- Botões full-width
- Imagens otimizadas

---

## 8. SISTEMA DE NOTIFICAÇÕES

### 8.1. Eventos que Disparam Notificações

**E-mail + Notificação In-App:**

```
1. NOVO CADASTRO RECEBIDO
   → Para: Administrador
   → Quando: Cadastro criado via Forms ou site
   → Ação: Link para analisar

2. CADASTRO APROVADO
   → Para: Inquilino
   → Quando: Admin aprova cadastro
   → Ação: Login e senha de acesso

3. CONTRATO GERADO
   → Para: Inquilino
   → Quando: Admin gera contrato
   → Ação: Link para baixar e assinar

4. DOCUMENTOS ENVIADOS
   → Para: Administrador
   → Quando: Inquilino faz upload
   → Ação: Link para validar

5. CONTRATO VALIDADO
   → Para: Inquilino
   → Quando: Admin valida contrato
   → Ação: Boas-vindas e instruções

6. LEMBRETE VENCIMENTO (5 dias antes)
   → Para: Inquilino
   → Quando: 5 dias antes do vencimento
   → Ação: Lembrar pagamento

7. VENCIMENTO HOJE
   → Para: Inquilino
   → Quando: Dia do vencimento
   → Ação: Lembrar pagamento

8. PAGAMENTO ATRASADO
   → Para: Inquilino e Admin
   → Quando: 3 dias após vencimento
   → Ação: Alerta de atraso

9. REAJUSTE PRÓXIMO (30 dias)
   → Para: Inquilino e Admin
   → Quando: 30 dias antes do reajuste
   → Ação: Informar novo valor

10. CONTRATO PRÓXIMO DO TÉRMINO (60 dias)
    → Para: Inquilino e Admin
    → Quando: 60 dias antes do fim
    → Ação: Renovação ou encerramento
```

### 8.2. Centro de Notificações

**Na interface do usuário:**
```
🔔 [3] ← Badge com contador

Ao clicar:
┌─────────────────────────────────────────┐
│  🔔 NOTIFICAÇÕES                        │
├─────────────────────────────────────────┤
│  [NOVAS]  [TODAS]  [ARQUIVADAS]        │
├─────────────────────────────────────────┤
│                                         │
│  • Novo cadastro recebido              │
│    Maria Santos - Apto 301             │
│    Há 2 horas                          │
│    [Ver cadastro]                       │
│                                         │
│  • Aluguel pago                        │
│    João Silva - Apto 102               │
│    Há 5 horas                          │
│    [Ver comprovante]                    │
│                                         │
│  • Reajuste em 30 dias                 │
│    Pedro Costa - Apto 205              │
│    Ontem às 14:30                      │
│    [Ver detalhes]                       │
│                                         │
└─────────────────────────────────────────┘
```

---

## 9. VALIDAÇÕES E SEGURANÇA

### 9.1. Validações de Formulário

**CPF:**
```javascript
function validarCPF(cpf) {
  // Remove caracteres não numéricos
  cpf = cpf.replace(/[^\d]/g, '');
  
  // Validar formato
  if (cpf.length !== 11) return false;
  
  // Validar dígitos verificadores
  // [algoritmo completo]
  
  return true;
}
```

**E-mail:**
```javascript
function validarEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}
```

**Telefone:**
```javascript
function validarTelefone(telefone) {
  // Aceitar: (XX) XXXXX-XXXX ou (XX) XXXX-XXXX
  const regex = /^\(\d{2}\)\s?\d{4,5}-\d{4}$/;
  return regex.test(telefone);
}
```

**Renda vs Aluguel:**
```javascript
function validarRenda(renda, aluguel) {
  // Renda deve ser no mínimo 3x o aluguel
  return (renda >= aluguel * 3);
}

// Exibir alerta se não cumprir
if (!validarRenda(renda, aluguel)) {
  alert("⚠️ Renda insuficiente. Recomenda-se renda de pelo menos 3x o valor do aluguel.");
}
```

### 9.2. Upload de Arquivos

**Validações:**
```javascript
Formatos aceitos:
• Documentos: PDF
• Imagens: JPG, JPEG, PNG, WEBP
• Tamanho máximo: 10MB por arquivo

Verificações:
1. Extensão do arquivo
2. MIME type real (não apenas extensão)
3. Tamanho em bytes
4. Scan antivírus (ClamAV ou similar)
5. Sanitizar nome do arquivo

Processamento:
1. Upload em chunks (para arquivos grandes)
2. Gerar nome único: UUID + extensão
3. Armazenar em diretório seguro
4. Salvar URL no banco de dados
5. Criar thumbnail (para imagens)
```

**Exemplo de código:**
```javascript
const allowedFormats = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];
const maxSize = 10 * 1024 * 1024; // 10MB

function validateFile(file) {
  // Validar extensão
  const ext = file.name.split('.').pop().toLowerCase();
  if (!allowedFormats.includes(ext)) {
    return { valid: false, error: 'Formato não permitido' };
  }
  
  // Validar tamanho
  if (file.size > maxSize) {
    return { valid: false, error: 'Arquivo muito grande (máx 10MB)' };
  }
  
  return { valid: true };
}
```

### 9.3. Autenticação e Autorização

**Níveis de Acesso:**
```
1. PÚBLICO (sem login)
   - Ver página de anúncios
   - Preencher formulário de interesse

2. INQUILINO (com login)
   - Ver seus dados
   - Baixar contrato
   - Enviar documentos
   - Ver pagamentos

3. ADMINISTRADOR (com login)
   - Acesso total
   - Gestão de apartamentos
   - Aprovação de cadastros
   - Geração de contratos
   - Controle financeiro
```

**Segurança de Senhas:**
```
• Mínimo 8 caracteres
• Pelo menos 1 letra maiúscula
• Pelo menos 1 número
• Criptografia: bcrypt (custo 12)
• Recuperação via e-mail
• Expiração de token: 1 hora
```

**Sessões:**
```
• Token JWT
• Expiração: 24 horas
• Renovação automática
• Logout automático após inatividade (30 min)
```

### 9.4. LGPD e Privacidade

**Consentimento:**
```
Ao se cadastrar, inquilino deve concordar com:

□ Li e concordo com os Termos de Uso
□ Li e concordo com a Política de Privacidade
□ Autorizo o tratamento de meus dados pessoais 
  para fins de locação
□ Autorizo o armazenamento de documentos
```

**Direitos do Titular:**
```
O inquilino pode solicitar:
• Acesso aos seus dados
• Correção de dados incorretos
• Exclusão de dados (direito ao esquecimento)
• Portabilidade dos dados
• Revogação do consentimento

Prazo de resposta: 15 dias úteis
```

**Logs e Rastreabilidade:**
```
Registrar:
• Quem acessou quais dados
• Quando
• De qual IP
• Que ações foram realizadas
• Alterações em cadastros

Retenção dos logs: 6 meses
```

---

## 10. TECNOLOGIAS SUGERIDAS

### 10.1. Stack Recomendada (Desenvolvimento Custom)

**Frontend:**
```
• Framework: Next.js 14 (React)
• Styling: TailwindCSS
• Gerenciamento de estado: Zustand ou Context API
• Forms: React Hook Form + Zod (validação)
• Upload: react-dropzone
• Notificações: react-toastify
• PDF: react-pdf, jsPDF
```

**Backend:**
```
• Node.js + Express ou Fastify
• TypeScript
• Autenticação: JWT + bcrypt
• Validação: Zod ou Joi
• E-mail: Nodemailer ou SendGrid
• Agendamento: node-cron (para notificações)
```

**Banco de Dados:**
```
• PostgreSQL (relacional)
• Prisma ORM
• Redis (cache e sessões)

Estrutura:
- apartments (apartamentos)
- tenants (inquilinos)
- contracts (contratos)
- payments (pagamentos)
- documents (documentos)
- notifications (notificações)
- audit_logs (logs de auditoria)
```

**Armazenamento de Arquivos:**
```
• AWS S3 ou Google Cloud Storage
• Cloudinary (otimização de imagens)
```

**APIs Externas:**
```
• Google Sheets API (para integração Forms)
• SendGrid ou AWS SES (e-mails)
• WhatsApp Business API (opcional)
```

### 10.2. Alternativa No-Code/Low-Code

**Plataformas Recomendadas:**

**1. Bubble.io** (mais completo)
```
Prós:
✓ Muito flexível
✓ Banco de dados nativo
✓ Workflows visuais
✓ Plugins para integração
✓ Upload de arquivos nativo

Contras:
✗ Curva de aprendizado
✗ Performance em scale
```

**2. Glide** (mais simples)
```
Prós:
✓ Baseado em Google Sheets
✓ Muito fácil de usar
✓ PWA automático

Contras:
✗ Limitações em lógica complexa
✗ Customização limitada
```

**3. Retool** (para admin)
```
Prós:
✓ Ideal para painéis administrativos
✓ Conecta facilmente com DBs
✓ Componentes prontos

Contras:
✗ Foco em internal tools
✗ Área pública limitada
```

---

## 11. CHECKLIST DE IMPLEMENTAÇÃO

### FASE 1 - MVP Básico (4-6 semanas)

```
APARTAMENTOS:
□ Cadastro de apartamentos
□ Upload de até 6 fotos
□ Gerenciamento de status
□ Página pública de listagem
□ Link compartilhável

INQUILINOS:
□ Cadastro básico (formulário)
□ Integração com Google Forms
□ Sistema de status
□ Validação de campos

ADMINISTRAÇÃO:
□ Login de administrador
□ Dashboard básico
□ Lista de apartamentos
□ Lista de inquilinos
□ Aprovação manual
```

### FASE 2 - Contratos e Documentos (4-6 semanas)

```
CONTRATOS:
□ Template .docx com campos variáveis
□ Geração automática de PDF
□ Preenchimento de campos
□ Área do inquilino (login)
□ Download de contrato

DOCUMENTOS:
□ Upload de RG, CPF, etc
□ Validação de formatos
□ Armazenamento seguro
□ Visualização no admin

PDFS:
□ Ficha cadastral
□ Declaração de residência
□ Contrato assinado
```

### FASE 3 - Automações (3-4 semanas)

```
ASSINATURA DIGITAL:
□ Botão destacado Gov.br
□ Instruções passo a passo
□ Upload de contrato assinado
□ Validação pelo admin

NOTIFICAÇÕES:
□ Sistema de e-mails automáticos
□ Templates editáveis
□ Notificações in-app
□ Centro de notificações

CONTROLE MENSAL:
□ Tabela de pagamentos
□ Registro de pagamentos
□ Status (pago/pendente/atrasado)
□ Lembretes automáticos
```

### FASE 4 - Refinamentos (2-3 semanas)

```
REAJUSTE:
□ Cálculo automático IGPM
□ Avisos 30 dias antes
□ Atualização de valores

RELATÓRIOS:
□ Exportação Excel/PDF
□ Dashboard com gráficos
□ Relatório de inadimplência
□ Relatório anual

SEGURANÇA:
□ LGPD compliance
□ Backup automático
□ Logs de auditoria
□ Validações completas
```

### FASE 5 - Otimizações (contínuo)

```
PERFORMANCE:
□ Otimização de imagens
□ Cache de consultas
□ Loading states
□ Lazy loading

UX/UI:
□ Animações
□ Feedback visual
□ Responsividade
□ Acessibilidade (WCAG)

FUTURO:
□ App mobile nativo
□ WhatsApp integration
□ Pagamento integrado (PIX)
□ Chat interno
```

---

## 12. ESTIMATIVA DE CUSTOS

### Desenvolvimento Custom (Freelancer/Agência)

```
FASE 1 (MVP): R$ 15.000 - R$ 25.000
FASE 2 (Contratos): R$ 12.000 - R$ 20.000
FASE 3 (Automações): R$ 10.000 - R$ 15.000
FASE 4 (Refinamentos): R$ 8.000 - R$ 12.000

TOTAL: R$ 45.000 - R$ 72.000
Prazo: 4-6 meses
```

### No-Code (Bubble.io)

```
Desenvolvimento: R$ 8.000 - R$ 15.000
(com desenvolvedor Bubble)

Plano Bubble:
• Starter: $29/mês
• Growth: $119/mês
• Team: $349/mês (recomendado)

Prazo: 2-3 meses
```

### Infraestrutura Mensal

```
HOSPEDAGEM:
• VPS (AWS/DigitalOcean): $20-50/mês
• Domínio: $10-20/ano
• SSL: Grátis (Let's Encrypt)

ARMAZENAMENTO:
• S3/Cloud Storage: $5-15/mês

E-MAILS:
• SendGrid: $15-50/mês (até 100k emails)

BACKUP:
• Automático: $10-20/mês

TOTAL MENSAL: $50-150/mês (~R$ 250-750)
```

---

## 13. CONSIDERAÇÕES FINAIS

### ✅ Diferenciais do Sistema

1. **Integração Google Forms** - Única, permite aproveitar formulário existente
2. **Template de Contrato Personalizado** - Mantém formatação oficial
3. **Declaração de Residência Automática** - Economiza tempo
4. **Reajuste IGPM Automático** - Controle financeiro preciso
5. **Botão Gov.br Destacado** - Facilita assinatura digital
6. **Multi-PDF** - Ficha, contrato, declaração
7. **Controle Mensal Completo** - Visão financeira total

### ⚠️ Pontos de Atenção

1. **Manutenção do Template**: Sempre testar campos variáveis após atualização
2. **LGPD**: Manter consentimento explícito e logs de acesso
3. **Backup**: Configurar desde o início, testar restauração
4. **Segurança**: SSL obrigatório, validações rigorosas
5. **UX Mobile**: Maioria dos inquilinos acessará por celular

### 🚀 Próximos Passos

1. **Definir plataforma**: Custom ou No-Code?
2. **Contratar desenvolvedor** (se custom)
3. **Preparar materiais**:
   - Template de contrato (.docx)
   - Textos de e-mails
   - Fotos dos apartamentos
   - Dados cadastrais
4. **Iniciar desenvolvimento** por fases
5. **Testar intensamente** antes do lançamento
6. **Treinar administrador**
7. **Lançamento gradual** (5 aptos primeiro)

---

## 📎 ANEXOS

### Template de Contrato - Campos Variáveis

Ver seção 3 - ETAPA 3

### Modelo de E-mails

Ver seção 6.3

### Fluxogramas

```
FLUXO DO INQUILINO:
Interesse → Cadastro → Análise → Aprovação → 
Contrato → Assinatura → Upload → Validação → Ativo

FLUXO DO PAGAMENTO:
Vencimento → Lembrete (-5d) → Vencimento (0d) → 
Pagamento ou Atraso (+3d) → Registro → Confirmação
```

---

**FIM DO DOCUMENTO CONSOLIDADO**

Data: 09/11/2025
Versão: 1.0 (Consolidada)
Base: Prompt original + Complementos

---
