const express = require('express');
const router = express.Router();
const authMiddleware = require('../auth/authMiddleware');

// Mock Twilio client (in production, use real Twilio SDK)
const sendWhatsAppMessage = async (to, message) => {
  console.log(`[WhatsApp] Sending message to ${to}: ${message}`);
  return { success: true, messageSid: 'mock-sid-' + Date.now() };
};

// Send WhatsApp notification
router.post('/whatsapp/send', authMiddleware, async (req, res) => {
  try {
    const { to, message } = req.body;

    if (!to || !message) {
      return res.status(400).json({ error: 'Phone number and message are required' });
    }

    const result = await sendWhatsAppMessage(to, message);
    res.json({ success: true, result });
  } catch (error) {
    console.error('WhatsApp notification error:', error);
    res.status(500).json({ error: 'Failed to send WhatsApp message' });
  }
});

// Send payment reminder
router.post('/payment-reminder/:tenantId', authMiddleware, async (req, res) => {
  try {
    const { tenantId } = req.params;
    
    // In production, fetch tenant phone from database
    const tenantPhone = '5548999999999';
    const message = `Olá! Este é um lembrete de que seu pagamento de aluguel vence em breve. Por favor, efetue o pagamento até a data prevista. BASE250 - Gestão de Imóveis`;

    const result = await sendWhatsAppMessage(tenantPhone, message);
    res.json({ success: true, message: 'Payment reminder sent', result });
  } catch (error) {
    console.error('Payment reminder error:', error);
    res.status(500).json({ error: 'Failed to send payment reminder' });
  }
});

// Send contract renewal notification
router.post('/contract-renewal/:contractId', authMiddleware, async (req, res) => {
  try {
    const { contractId } = req.params;
    
    const tenantPhone = '5548999999999';
    const message = `Olá! Seu contrato de locação está próximo do vencimento. Entre em contato conosco para discutir a renovação. BASE250 - Gestão de Imóveis`;

    const result = await sendWhatsAppMessage(tenantPhone, message);
    res.json({ success: true, message: 'Contract renewal notification sent', result });
  } catch (error) {
    console.error('Contract renewal notification error:', error);
    res.status(500).json({ error: 'Failed to send contract renewal notification' });
  }
});

// Get notification history
router.get('/history', authMiddleware, (req, res) => {
  // Mock notification history
  const history = [
    {
      id: 1,
      type: 'payment_reminder',
      recipient: '5548999999999',
      status: 'sent',
      sentAt: new Date()
    },
    {
      id: 2,
      type: 'contract_renewal',
      recipient: '5548988888888',
      status: 'sent',
      sentAt: new Date(Date.now() - 86400000)
    }
  ];

  res.json(history);
});

module.exports = router;
