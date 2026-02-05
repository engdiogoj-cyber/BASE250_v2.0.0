require('dotenv').config();
const express = require('express');
const cors = require('cors');
const authRoutes = require('./auth/authRoutes');
const apartmentRoutes = require('./apartments/apartmentRoutes');
const tenantRoutes = require('./tenants/tenantRoutes');
const contractRoutes = require('./contracts/contractRoutes');
const paymentRoutes = require('./payments/paymentRoutes');
const reportRoutes = require('./reports/reportRoutes');
const notificationRoutes = require('./notifications/notificationRoutes');

const app = express();
const PORT = process.env.PORT || 3001;

// Middleware
app.use(cors({
  origin: process.env.FRONTEND_URL || 'http://localhost:3000',
  credentials: true
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Routes
app.use('/api/auth', authRoutes);
app.use('/api/apartments', apartmentRoutes);
app.use('/api/tenants', tenantRoutes);
app.use('/api/contracts', contractRoutes);
app.use('/api/payments', paymentRoutes);
app.use('/api/reports', reportRoutes);
app.use('/api/notifications', notificationRoutes);

// Health check
app.get('/api/health', (req, res) => {
  res.json({ status: 'ok', message: 'BASE250 API is running' });
});

// Error handler
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ error: 'Something went wrong!' });
});

app.listen(PORT, () => {
  console.log(`🚀 BASE250 Backend running on port ${PORT}`);
});

module.exports = app;
