const express = require('express');
const router = express.Router();
const authMiddleware = require('../auth/authMiddleware');

// Mock database for payments
let payments = [
  {
    id: 1,
    contractId: 1,
    tenantId: 1,
    amount: 2500.00,
    dueDate: '2024-01-10',
    paidDate: '2024-01-08',
    status: 'paid',
    method: 'bank_transfer',
    createdAt: new Date('2024-01-01')
  },
  {
    id: 2,
    contractId: 1,
    tenantId: 1,
    amount: 2500.00,
    dueDate: '2024-02-10',
    paidDate: null,
    status: 'pending',
    method: null,
    createdAt: new Date('2024-02-01')
  }
];

let nextId = 3;

// Get all payments
router.get('/', authMiddleware, (req, res) => {
  const { status, tenantId } = req.query;
  let filteredPayments = payments;

  if (status) {
    filteredPayments = filteredPayments.filter(p => p.status === status);
  }
  if (tenantId) {
    filteredPayments = filteredPayments.filter(p => p.tenantId === parseInt(tenantId));
  }

  res.json(filteredPayments);
});

// Get payment by ID
router.get('/:id', authMiddleware, (req, res) => {
  const payment = payments.find(p => p.id === parseInt(req.params.id));
  if (!payment) {
    return res.status(404).json({ error: 'Payment not found' });
  }
  res.json(payment);
});

// Create new payment
router.post('/', authMiddleware, (req, res) => {
  const { contractId, tenantId, amount, dueDate } = req.body;

  if (!contractId || !tenantId || !amount || !dueDate) {
    return res.status(400).json({ error: 'All fields are required' });
  }

  const newPayment = {
    id: nextId++,
    contractId,
    tenantId,
    amount,
    dueDate,
    paidDate: null,
    status: 'pending',
    method: null,
    createdAt: new Date()
  };

  payments.push(newPayment);
  res.status(201).json(newPayment);
});

// Update payment (mark as paid)
router.put('/:id', authMiddleware, (req, res) => {
  const paymentIndex = payments.findIndex(p => p.id === parseInt(req.params.id));
  
  if (paymentIndex === -1) {
    return res.status(404).json({ error: 'Payment not found' });
  }

  payments[paymentIndex] = {
    ...payments[paymentIndex],
    ...req.body,
    id: payments[paymentIndex].id,
    updatedAt: new Date()
  };

  res.json(payments[paymentIndex]);
});

// Get payment statistics
router.get('/stats/summary', authMiddleware, (req, res) => {
  const stats = {
    total: payments.reduce((sum, p) => sum + p.amount, 0),
    paid: payments.filter(p => p.status === 'paid').reduce((sum, p) => sum + p.amount, 0),
    pending: payments.filter(p => p.status === 'pending').reduce((sum, p) => sum + p.amount, 0),
    overdue: payments.filter(p => p.status === 'overdue').reduce((sum, p) => sum + p.amount, 0),
    count: {
      total: payments.length,
      paid: payments.filter(p => p.status === 'paid').length,
      pending: payments.filter(p => p.status === 'pending').length,
      overdue: payments.filter(p => p.status === 'overdue').length
    }
  };
  res.json(stats);
});

module.exports = router;
