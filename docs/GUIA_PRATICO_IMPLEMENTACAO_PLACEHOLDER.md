# 🎯 GUIA PRÁTICO DE IMPLEMENTAÇÃO
## Sistema BASE250 - Passo a Passo

---

## 📋 ANTES DE COMEÇAR

### Materiais Necessários:
- [ ] Template do contrato (.docx) com campos {{variáveis}}
- [ ] Dados completos dos 15 apartamentos
- [ ] Fotos de qualidade dos apartamentos (6 por apto = 90 fotos)
- [ ] Link do Google Forms + acesso à planilha
- [ ] Dados do locador (Jucemar) completos
- [ ] E-mail para envio de notificações
- [ ] Definir hospedagem/plataforma

---

## DECISÃO 1: CUSTOM OU NO-CODE?

### Opção A: Desenvolvimento Custom

**✅ Escolher se:**
- Você tem orçamento (R$ 45k-72k)
- Quer controle total do sistema
- Planeja escalar (+ de 30 apartamentos futuramente)
- Precisa de customizações específicas

**Próximo passo:** Ir para ROTEIRO CUSTOM

---

### Opção B: No-Code (Bubble.io)

**✅ Escolher se:**
- Orçamento limitado (R$ 8k-15k)
- Quer lançar rápido (2-3 meses)
- Não tem conhecimento técnico
- 15 apartamentos são suficientes

**Próximo passo:** Ir para ROTEIRO NO-CODE

---

## 🛠️ ROTEIRO CUSTOM (Desenvolvimento Próprio)

### SEMANA 1-2: Preparação

**Dia 1-3: Buscar Desenvolvedor**
```
Onde buscar:
• Workana (https://www.workana.com)
• 99freelas (https://www.99freelas.com.br)
• LinkedIn (buscar "desenvolvedor full stack react")
• Indicações de conhecidos

Perguntas ao candidato:
1. Tem experiência com React/Next.js?
2. Já trabalhou com PDFs automáticos?
3. Já integrou com Google Sheets API?
4. Tem portfólio de sistemas similares?
5. Qual prazo e valor estimado?

Solicitar:
- Proposta escrita
- Cronograma detalhado
- Forma de pagamento (parcelado)
```

**Dia 4-7: Preparar Materiais**
```
APARTAMENTOS:
□ Planilha Excel com dados dos 15 aptos
  Colunas: número, quartos, metragem, aluguel, 
           condomínio, IPTU, descrição
□ Fotos organizadas em pastas
  Exemplo: /Apto101/, /Apto102/, etc
  6 fotos por pasta, nomeadas: 01_sala.jpg, 
  02_cozinha.jpg, etc

CONTRATO:
□ Template .docx revisado
□ Conferir todos os campos {{variáveis}}
□ Testar preenchimento manual

TEXTOS:
□ Descrição de cada apartamento
□ Textos dos e-mails (usar templates do doc)
□ Termos de uso e política de privacidade
```

**Dia 8-14: Kickoff com Desenvolvedor**
```
Reunião inicial (presencial ou videochamada):
1. Apresentar documento consolidado completo
2. Revisar prioridades:
   - FASE 1 é essencial (MVP)
   - FASE 2 é importante
   - FASE 3-4 podem ser depois
3. Definir:
   - Meio de comunicação (WhatsApp, Slack, etc)
   - Reuniões semanais (dia e hora)
   - Forma de homologação (você testa e aprova)
4. Pagar sinal (geralmente 30-50%)
```

### SEMANA 3-8: FASE 1 - MVP

**Entregas esperadas:**
```
□ Painel administrativo com login
□ Cadastro de apartamentos funcionando
□ Upload de fotos (máximo 6)
□ Página pública exibindo apartamentos
□ Formulário de cadastro de interessados
□ Integração básica Google Forms
□ Lista de inquilinos no admin
```

**Seus testes:**
```
TESTAR:
1. Cadastrar os 15 apartamentos
2. Fazer upload de todas as fotos
3. Verificar se página pública mostra só disponíveis
4. Preencher formulário de teste
5. Ver se aparece no admin
6. Mudar status e ver se oculta da página pública

REPORTAR BUGS:
- Tirar print do erro
- Descrever o que fez
- Enviar ao desenvolvedor
```

### SEMANA 9-14: FASE 2 - Contratos

**Entregas esperadas:**
```
□ Área do inquilino com login
□ Upload de documentos funcionando
□ Geração automática do contrato PDF
□ Campos variáveis preenchidos corretamente
□ Download do contrato
□ Ficha cadastral em PDF
```

**Seus testes:**
```
TESTAR:
1. Criar login de teste para inquilino
2. Fazer upload de documentos de teste
3. Aprovar cadastro e gerar contrato
4. Baixar PDF e conferir se campos estão certos
5. Testar declaração de residência
```

### SEMANA 15-18: FASE 3 - Automações

**Entregas esperadas:**
```
□ Sistema de e-mails funcionando
□ Notificações automáticas
□ Botão Gov.br na área do inquilino
□ Controle mensal de pagamentos
□ Lembretes de vencimento
```

**Seus testes:**
```
TESTAR:
1. Verificar recebimento de e-mails
2. Conferir textos dos e-mails
3. Simular pagamento e ver se atualiza
4. Verificar lembretes automáticos
```

### SEMANA 19-22: FASE 4 - Finalização

**Entregas esperadas:**
```
□ Reajuste IGPM configurado
□ Relatórios funcionando
□ Exportação Excel/PDF
□ Backup automático
□ Ajustes finais
```

### SEMANA 23-24: Lançamento

```
□ Migrar para servidor de produção
□ Configurar domínio (ex: BASE250.com.br)
□ Configurar SSL (https)
□ Fazer backup de tudo
□ Treinar se necessário
□ Cadastrar primeiros 5 apartamentos reais
□ Divulgar link
□ Acompanhar primeiros cadastros
```

---

## 🎨 ROTEIRO NO-CODE (Bubble.io)

### SEMANA 1: Setup

**Dia 1: Criar Conta Bubble**
```
1. Acessar https://bubble.io
2. Criar conta (pode começar free)
3. Criar novo app: "BASE250"
4. Escolher blank template
```

**Dia 2-3: Aprender Básico**
```
Assistir tutoriais:
• Bubble Academy (oficial)
• YouTube: "Bubble tutorial português"
• Específico: "Bubble upload files"
• Específico: "Bubble database"

Tempo: 3-4 horas
```

**Dia 4-7: Estrutura do Banco de Dados**
```
Criar Data Types:

1. Apartment (Apartamento)
   - numero (text)
   - quartos (number)
   - metragem (number)
   - valor_aluguel (number)
   - valor_condominio (number)
   - valor_iptu (number)
   - status (text) - Options: Disponível, Alugado
   - descricao (text)
   - fotos (list of images)

2. Tenant (Inquilino)
   - nome (text)
   - cpf (text)
   - email (email)
   - telefone (text)
   - renda (number)
   - status (text)
   - apartamento (Apartment)
   - documentos (list of files)

3. Contract (Contrato)
   - tenant (Tenant)
   - apartment (Apartment)
   - data_inicio (date)
   - valor (number)
   - pdf_contrato (file)
```

### SEMANA 2-3: Interface Admin

**Páginas a criar:**
```
1. /admin (Login)
   - Input: email
   - Input: senha
   - Botão: Entrar

2. /admin-dashboard
   - Repeating Group de apartamentos
   - Botões: Novo, Editar, Ver

3. /admin-apartamento
   - Form com campos
   - Picture Uploader (multi: yes, max: 6)
   - Botão: Salvar

4. /admin-inquilinos
   - Repeating Group de inquilinos
   - Botão: Analisar
```

### SEMANA 4: Página Pública

**Criar página:**
```
1. /imoveis (ou index)
   - Repeating Group
   - Filtro: status = "Disponível"
   - Mostrar fotos em carousel
   - Botão: "Quero alugar"
   
2. /cadastro
   - Form com campos do inquilino
   - Validações
   - Botão: Enviar
```

### SEMANA 5-6: Integração Google Forms

**Usar Plugin:**
```
1. Instalar plugin "Google Sheets"
2. Conectar sua conta Google
3. Selecionar planilha
4. Criar workflow:
   - Trigger: Every 15 minutes
   - Action: Get data from Google Sheets
   - Action: Create/Update Tenant
```

### SEMANA 7-8: Contratos e PDFs

**Usar Plugins:**
```
1. Plugin "PDF Conjurer" ou "PDF Generator"
2. Criar template HTML do contrato
3. Mapear campos {{variáveis}}
4. Testar geração
```

### SEMANA 9-10: E-mails

**Usar Sendgrid:**
```
1. Criar conta Sendgrid (free: 100/dia)
2. Conectar no Bubble
3. Criar workflows de e-mail:
   - Novo cadastro
   - Aprovação
   - Contrato pronto
   - Lembretes
```

### SEMANA 11-12: Finalizações

```
□ Controle de pagamentos
□ Relatórios básicos
□ Testes completos
□ Ajustes de design
□ Mobile responsive
```

### SEMANA 13: Lançamento

```
□ Upgrade plano Bubble (mínimo Starter: $29/mês)
□ Conectar domínio próprio
□ SSL automático (Bubble faz)
□ Migrar dados reais
□ Lançar!
```

---

## 📊 CRONOGRAMA COMPARATIVO

| Fase | Custom | Bubble.io |
|------|--------|-----------|
| Preparação | 2 sem | 1 sem |
| MVP | 6 sem | 3 sem |
| Contratos | 6 sem | 2 sem |
| Automações | 4 sem | 4 sem |
| Finalização | 4 sem | 3 sem |
| **TOTAL** | **22 sem (5.5 meses)** | **13 sem (3 meses)** |

---

## 💰 ORÇAMENTO DETALHADO

### Desenvolvimento Custom

```
INVESTIMENTO INICIAL:
Desenvolvimento FASE 1-4: R$ 45.000 - R$ 72.000
  • Sinal (30%): R$ 13.500 - R$ 21.600
  • Parcelas mensais: R$ 7.500 - R$ 12.000/mês

CUSTOS MENSAIS:
Hospedagem (VPS): R$ 100 - R$ 250/mês
Domínio (.com.br): R$ 40/ano
E-mail (SendGrid): R$ 75 - R$ 250/mês
Armazenamento S3: R$ 25 - R$ 75/mês
Backup: R$ 50/mês

Total mensal após lançamento: R$ 250 - R$ 625/mês

PRIMEIRO ANO:
Desenvolvimento: R$ 45.000 - R$ 72.000
Infraestrutura (12 meses): R$ 3.000 - R$ 7.500
Domínio: R$ 40

TOTAL ANO 1: R$ 48.040 - R$ 79.540
```

### Bubble.io (No-Code)

```
INVESTIMENTO INICIAL:
Desenvolvedor Bubble: R$ 8.000 - R$ 15.000
  • Sinal (50%): R$ 4.000 - R$ 7.500
  • Saldo na entrega: R$ 4.000 - R$ 7.500

CUSTOS MENSAIS:
Plano Bubble Team: $349/mês ≈ R$ 1.745/mês
Domínio (.com.br): R$ 40/ano
E-mail (SendGrid): R$ 75/mês

Total mensal: R$ 1.820/mês

PRIMEIRO ANO:
Desenvolvimento: R$ 8.000 - R$ 15.000
Plano Bubble (12 meses): R$ 20.940
Domínio: R$ 40
E-mail (12 meses): R$ 900

TOTAL ANO 1: R$ 29.880 - R$ 36.880
```

### Comparação:

| Item | Custom | Bubble | Diferença |
|------|--------|--------|-----------|
| Ano 1 | R$ 48k-79k | R$ 30k-37k | -40% |
| Mensais (após) | R$ 250-625 | R$ 1.820 | +190% |
| 5 anos total | R$ 63k-87k | R$ 139k | +60% |

**Conclusão:** 
- Bubble é mais barato no primeiro ano
- Custom compensa em 3+ anos
- Escolha depende do horizonte de uso

---

## ✅ CHECKLIST DE LANÇAMENTO

### Técnico
- [ ] Todas as funcionalidades testadas
- [ ] Backups configurados e testados
- [ ] SSL/HTTPS funcionando
- [ ] E-mails sendo enviados
- [ ] Links compartilháveis funcionando
- [ ] Mobile responsivo testado
- [ ] Validações funcionando (CPF, e-mail)
- [ ] Upload de arquivos ok
- [ ] Geração de PDF ok

### Conteúdo
- [ ] 15 apartamentos cadastrados
- [ ] Todas as fotos (90) com boa qualidade
- [ ] Descrições completas
- [ ] Valores corretos
- [ ] Template contrato configurado
- [ ] Textos de e-mail revisados
- [ ] Termos de uso e privacidade

### Operacional
- [ ] Você sabe usar o sistema
- [ ] Testou aprovar um cadastro
- [ ] Testou gerar contrato
- [ ] Sabe registrar pagamento
- [ ] Sabe exportar relatórios
- [ ] Tem suporte do desenvolvedor

### Marketing
- [ ] Link curto e fácil (ex: BASE250.com.br)
- [ ] Fotos profissionais
- [ ] Descrições atrativas
- [ ] Compartilhar em:
  - [ ] Redes sociais
  - [ ] Grupos de WhatsApp
  - [ ] OLX/ZAP Imóveis
  - [ ] Corretores parceiros

---

## 🆘 TROUBLESHOOTING COMUM

### Problema: E-mails não chegam
**Solução:**
1. Verificar spam
2. Conferir configuração SendGrid
3. Testar e-mail manual
4. Ver logs do sistema

### Problema: Upload de foto não funciona
**Solução:**
1. Verificar tamanho (máx 5MB)
2. Verificar formato (JPG, PNG)
3. Testar com foto menor
4. Ver console do navegador (F12)

### Problema: Contrato PDF com campos errados
**Solução:**
1. Revisar template .docx
2. Conferir nome exato dos campos {{variavel}}
3. Testar geração com dados fictícios
4. Ver logs de erro

### Problema: Google Forms não sincroniza
**Solução:**
1. Verificar permissões da planilha
2. Re-conectar API
3. Rodar sincronização manual
4. Verificar formato dos dados

---

## 📞 PRÓXIMOS PASSOS IMEDIATOS

### Esta Semana:
1. [ ] Decidir: Custom ou Bubble?
2. [ ] Se custom: buscar 3 orçamentos de desenvolvedores
3. [ ] Se Bubble: criar conta e assistir 1 tutorial
4. [ ] Organizar fotos dos apartamentos
5. [ ] Revisar template do contrato

### Próximo Mês:
1. [ ] Contratar desenvolvedor ou iniciar no Bubble
2. [ ] Preparar todos os materiais
3. [ ] Fazer kickoff
4. [ ] Acompanhar primeira entrega (MVP)

### Em 3 Meses:
1. [ ] Sistema funcionando (FASE 1 e 2)
2. [ ] Primeiros 5 apartamentos no ar
3. [ ] Primeiros cadastros recebidos
4. [ ] Ajustes baseados no uso real

---

## 💡 DICAS DE OURO

### Para Economizar:
1. Comece com MVP (FASE 1-2), depois adiciona resto
2. Use SendGrid free (100 e-mails/dia) no início
3. Hospedagem compartilhada é mais barata
4. Negocie pagamento parcelado com desenvolvedor

### Para Acelerar:
1. Tenha TODOS os materiais prontos antes
2. Responda rápido as dúvidas do desenvolvedor
3. Teste logo após cada entrega
4. Tenha alguém técnico para tirar dúvidas

### Para Não Errar:
1. Teste TUDO antes de lançar para o público
2. Comece com poucos aptos (5) para testar
3. Peça feedback de amigos/familiares
4. Tenha backup antes de qualquer mudança
5. Documente senhas e acessos

---

## 📚 RECURSOS ÚTEIS

### Aprender Bubble:
- https://bubble.io/academy
- YouTube: "Bubble.io tutorial português"
- Grupo Facebook: "Bubble Developers Brasil"

### Encontrar Desenvolvedores:
- https://www.workana.com
- https://www.99freelas.com.br
- LinkedIn
- Comunidades de dev no Discord

### Ferramentas:
- Compressor de imagens: https://tinypng.com
- Gerador de texto: ChatGPT para descrições
- Verificador CPF: https://www.gerardorcpf.com.br/validar-cpf.html

### Inspiração:
- Airbnb (UX de listagem)
- QuintoAndar (fluxo de cadastro)
- imovelweb (filtros e busca)

---

**Criado em:** 09/11/2025  
**Versão:** 1.0  
**Para:** Implementação Sistema BASE250

Boa sorte com o projeto! 🚀
