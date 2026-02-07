import express from "express";

const app = express();
app.use(express.json());

app.post("/cadastro-basico", (req, res) => {
  const { nome, telefone, email } = req.body || {};
  if (!nome || !telefone || !email) return res.status(400).json({ error: "campos obrigatórios" });
  // TODO: criar registro, status "Em análise", e-mail de notificação
  return res.json({ status: "ok", message: "cadastro criado (placeholder)" });
});

// TODO: sync/polling planilha => banco, grupo por apartamento

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`BASE250 backend rodando na porta ${PORT}`));