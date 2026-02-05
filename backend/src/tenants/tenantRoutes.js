const express = require('express');
const router = express.Router();
const authMiddleware = require('../auth/authMiddleware');

// Mock database for tenants
let tenants = [
  {
    id: 1,
    name: 'João Silva',
    cpf: '123.456.789-00',
    email: 'joao@email.com',
    phone: '(48) 99999-9999',
    apartmentId: 1,
    status: 'active',
    createdAt: new Date('2024-01-01')
  }
];

let nextId = 2;

// Get all tenants
router.get('/', authMiddleware, (req, res) => {
  res.json(tenants);
});

// Get tenant by ID
router.get('/:id', authMiddleware, (req, res) => {
  const tenant = tenants.find(t => t.id === parseInt(req.params.id));
  if (!tenant) {
    return res.status(404).json({ error: 'Tenant not found' });
  }
  res.json(tenant);
});

// Create new tenant
router.post('/', authMiddleware, (req, res) => {
  const { name, cpf, email, phone, apartmentId } = req.body;

  if (!name || !cpf || !email || !phone) {
    return res.status(400).json({ error: 'All fields are required' });
  }

  const newTenant = {
    id: nextId++,
    name,
    cpf,
    email,
    phone,
    apartmentId,
    status: 'active',
    createdAt: new Date()
  };

  tenants.push(newTenant);
  res.status(201).json(newTenant);
});

// Update tenant
router.put('/:id', authMiddleware, (req, res) => {
  const tenantIndex = tenants.findIndex(t => t.id === parseInt(req.params.id));
  
  if (tenantIndex === -1) {
    return res.status(404).json({ error: 'Tenant not found' });
  }

  tenants[tenantIndex] = {
    ...tenants[tenantIndex],
    ...req.body,
    id: tenants[tenantIndex].id,
    updatedAt: new Date()
  };

  res.json(tenants[tenantIndex]);
});

// Delete tenant
router.delete('/:id', authMiddleware, (req, res) => {
  const tenantIndex = tenants.findIndex(t => t.id === parseInt(req.params.id));
  
  if (tenantIndex === -1) {
    return res.status(404).json({ error: 'Tenant not found' });
  }

  tenants.splice(tenantIndex, 1);
  res.json({ message: 'Tenant deleted successfully' });
});

module.exports = router;
