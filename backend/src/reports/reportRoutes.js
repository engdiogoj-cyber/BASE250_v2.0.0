const express = require('express');
const router = express.Router();
const authMiddleware = require('../auth/authMiddleware');

// Get financial report
router.get('/financial', authMiddleware, (req, res) => {
  const { startDate, endDate } = req.query;

  // Mock financial data
  const report = {
    period: {
      start: startDate || new Date().toISOString().split('T')[0],
      end: endDate || new Date().toISOString().split('T')[0]
    },
    revenue: {
      total: 27500.00,
      received: 25000.00,
      pending: 2500.00
    },
    expenses: {
      total: 5000.00,
      maintenance: 2000.00,
      utilities: 1500.00,
      administration: 1500.00
    },
    netIncome: 20000.00,
    occupancyRate: 0.85,
    properties: {
      total: 10,
      occupied: 8,
      available: 2
    }
  };

  res.json(report);
});

// Get occupancy report
router.get('/occupancy', authMiddleware, (req, res) => {
  const report = {
    currentMonth: {
      occupied: 8,
      available: 2,
      rate: 0.80
    },
    lastMonth: {
      occupied: 7,
      available: 3,
      rate: 0.70
    },
    trend: 'up',
    averageStayDuration: 18 // months
  };

  res.json(report);
});

// Get payment report
router.get('/payments', authMiddleware, (req, res) => {
  const report = {
    onTime: 6,
    late: 1,
    pending: 1,
    defaultRate: 0.125,
    averagePaymentDelay: 2 // days
  };

  res.json(report);
});

module.exports = router;
