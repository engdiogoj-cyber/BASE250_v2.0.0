const express = require('express');
const router = express.Router();
const authMiddleware = require('../auth/authMiddleware');
const { generateContractPDF } = require('./pdfGenerator');

// Mock database for contracts
let contracts = [
  {
    id: 1,
    tenantId: 1,
    apartmentId: 1,
    startDate: '2024-01-01',
    endDate: '2024-12-31',
    monthlyRent: 2500.00,
    status: 'active',
    createdAt: new Date('2024-01-01')
  }
];

let nextId = 2;

// Get all contracts
router.get('/', authMiddleware, (req, res) => {
  res.json(contracts);
});

// Get contract by ID
router.get('/:id', authMiddleware, (req, res) => {
  const contract = contracts.find(c => c.id === parseInt(req.params.id));
  if (!contract) {
    return res.status(404).json({ error: 'Contract not found' });
  }
  res.json(contract);
});

// Create new contract
router.post('/', authMiddleware, (req, res) => {
  const { tenantId, apartmentId, startDate, endDate, monthlyRent } = req.body;

  if (!tenantId || !apartmentId || !startDate || !endDate || !monthlyRent) {
    return res.status(400).json({ error: 'All fields are required' });
  }

  const newContract = {
    id: nextId++,
    tenantId,
    apartmentId,
    startDate,
    endDate,
    monthlyRent,
    status: 'active',
    createdAt: new Date()
  };

  contracts.push(newContract);
  res.status(201).json(newContract);
});

// Update contract
router.put('/:id', authMiddleware, (req, res) => {
  const contractIndex = contracts.findIndex(c => c.id === parseInt(req.params.id));
  
  if (contractIndex === -1) {
    return res.status(404).json({ error: 'Contract not found' });
  }

  contracts[contractIndex] = {
    ...contracts[contractIndex],
    ...req.body,
    id: contracts[contractIndex].id,
    updatedAt: new Date()
  };

  res.json(contracts[contractIndex]);
});

// Delete contract
router.delete('/:id', authMiddleware, (req, res) => {
  const contractIndex = contracts.findIndex(c => c.id === parseInt(req.params.id));
  
  if (contractIndex === -1) {
    return res.status(404).json({ error: 'Contract not found' });
  }

  contracts.splice(contractIndex, 1);
  res.json({ message: 'Contract deleted successfully' });
});

// Generate PDF for contract
router.get('/:id/pdf', authMiddleware, async (req, res) => {
  try {
    const contract = contracts.find(c => c.id === parseInt(req.params.id));
    
    if (!contract) {
      return res.status(404).json({ error: 'Contract not found' });
    }

    // Get related data (in production, fetch from database)
    const tenant = {
      id: contract.tenantId,
      name: 'João Silva',
      cpf: '123.456.789-00',
      email: 'joao@email.com',
      phone: '(48) 99999-9999'
    };

    const apartment = {
      id: contract.apartmentId,
      number: '250',
      floor: 2,
      address: 'Alto do Itacorubi Building, Florianópolis/SC'
    };

    const pdfBuffer = await generateContractPDF(contract, tenant, apartment);

    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', `attachment; filename=contrato-${contract.id}.pdf`);
    res.send(pdfBuffer);
  } catch (error) {
    console.error('PDF generation error:', error);
    res.status(500).json({ error: 'Failed to generate PDF' });
  }
});

module.exports = router;
