# BASE250 – Gestão de Imóveis (Pacote completo para GitHub, com placeholders)

Este pacote segue a linha do “arquivo Base44”, porém **sanitizado para GitHub**, sem dados pessoais reais.

> Domínio: `base250.com`  
> E-mail oficial: `contato@base250.br`  
> Hosting: Hostinger (hpanel) ou GitHub Pages (frontend)  
> Ferramentas: ChatGPT, Claude, GitHub Copilot

## Regras de privacidade (importante)
- Tudo que é sensível fica em:
  - `docs_private/` (não subir para GitHub)
  - `.env` (não subir para GitHub)
- No GitHub ficarão apenas arquivos com placeholders.

## Estrutura
```
BASE250_GitHub_Package_PUBLIC/
├─ README.md
├─ LICENSE
├─ CONTRIBUTING.md
├─ .gitignore
├─ docs/
│  ├─ GUIA_PRATICO_IMPLEMENTACAO_PLACEHOLDER.md
│  └─ PROMPT_COMPLETO_BASE250_PLACEHOLDER.md
├─ docs_private/ (IGNORADO, pode armazenar PDF/template com dados reais)
├─ assets/images/
│  ├─ logo.png
│  └─ logo_pet.png
├─ frontend/
│  ├─ site_publico/index.html
│  └─ painel/
│     ├─ BASE250_SISTEMA_COMPLETO2.html
│     └─ base250-painel-v2_1.html
├─ backend/
│  ├─ app.js
│  ├─ package.json
│  ├─ .env.example
│  ├─ README.md
│  ├─ models/
│  ├─ services/
│  └─ migrations/
├─ scripts/
│  └─ sync_from_google_sheet.md
└─ .github/workflows/ci.yml
```

## Como rodar (backend)
```bash
cd backend
npm install
cp .env.example .env
node app.js
```

## Pipeline
- `ci.yml`: garante existência do frontend
- Próximo passo: adicionar lint/format/test (opcional)