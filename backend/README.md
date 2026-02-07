# Backend BASE250 (Placeholder-safe)

## Endpoints (esqueleto)
- POST /cadastro-basico
- GET /sync (polling)
- POST /webhook/google-forms (se ativar)

## Variáveis (.env)
- LOCADOR_NOME
- LOCADOR_CPF
- ADMIN_EMAIL (com base250.br)
- SMTP_HOST / SMTP_USER / SMTP_PASS

## Segurança
- Nunca subir `.env` nem `docs_private` para o GitHub.