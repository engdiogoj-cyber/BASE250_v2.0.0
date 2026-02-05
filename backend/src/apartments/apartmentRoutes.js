const express = require('express');
const router = express.Router();
const authMiddleware = require('../auth/authMiddleware');

// Mock database for apartments
let apartments = [
  {
    id: 1,
    number: '250',
    floor: 2,
    bedrooms: 3,
    bathrooms: 2,
    area: 85.5,
    status: 'occupied',
    monthlyRent: 2500.00,
    address: 'Alto do Itacorubi Building, Florianópolis/SC'
  },
  {
    id: 2,
    number: '251',
    floor: 2,
    bedrooms: 2,
    bathrooms: 1,
    area: 65.0,
    status: 'available',
    monthlyRent: 2000.00,
    address: 'Alto do Itacorubi Building, Florianópolis/SC'
  }
];

// Get all apartments
router.get('/', authMiddleware, (req, res) => {
  res.json(apartments);
});

// Get apartment by ID
router.get('/:id', authMiddleware, (req, res) => {
  const apartment = apartments.find(a => a.id === parseInt(req.params.id));
  if (!apartment) {
    return res.status(404).json({ error: 'Apartment not found' });
  }
  res.json(apartment);
});

// Get dashboard statistics
router.get('/dashboard/stats', authMiddleware, (req, res) => {
  const stats = {
    total: apartments.length,
    occupied: apartments.filter(a => a.status === 'occupied').length,
    available: apartments.filter(a => a.status === 'available').length,
    maintenance: apartments.filter(a => a.status === 'maintenance').length,
    totalRevenue: apartments
      .filter(a => a.status === 'occupied')
      .reduce((sum, a) => sum + a.monthlyRent, 0)
  };
  res.json(stats);
});

module.exports = router;
