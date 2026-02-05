const PDFDocument = require('pdfkit');

/**
 * Generate a professional contract PDF
 * @param {Object} contract - Contract data
 * @param {Object} tenant - Tenant data
 * @param {Object} apartment - Apartment data
 * @returns {Promise<Buffer>} PDF buffer
 */
async function generateContractPDF(contract, tenant, apartment) {
  return new Promise((resolve, reject) => {
    try {
      const doc = new PDFDocument({
        size: 'A4',
        margins: { top: 50, bottom: 50, left: 50, right: 50 }
      });

      const chunks = [];
      
      doc.on('data', chunk => chunks.push(chunk));
      doc.on('end', () => resolve(Buffer.concat(chunks)));
      doc.on('error', reject);

      // Header
      doc.fontSize(20)
         .font('Helvetica-Bold')
         .text('CONTRATO DE LOCAÇÃO DE IMÓVEL', { align: 'center' })
         .moveDown();

      // Contract details
      doc.fontSize(12)
         .font('Helvetica')
         .text(`Contrato Nº: ${contract.id}`, { align: 'right' })
         .text(`Data de Emissão: ${new Date().toLocaleDateString('pt-BR')}`, { align: 'right' })
         .moveDown(2);

      // Parties section
      doc.fontSize(14)
         .font('Helvetica-Bold')
         .text('1. PARTES CONTRATANTES')
         .moveDown(0.5);

      doc.fontSize(11)
         .font('Helvetica-Bold')
         .text('LOCADOR:')
         .font('Helvetica')
         .text('BASE250 Gestão de Imóveis')
         .text('CNPJ: 00.000.000/0001-00')
         .text('Endereço: Alto do Itacorubi, Florianópolis/SC')
         .moveDown();

      doc.font('Helvetica-Bold')
         .text('LOCATÁRIO:')
         .font('Helvetica')
         .text(`Nome: ${tenant.name}`)
         .text(`CPF: ${tenant.cpf}`)
         .text(`E-mail: ${tenant.email}`)
         .text(`Telefone: ${tenant.phone}`)
         .moveDown(2);

      // Property section
      doc.fontSize(14)
         .font('Helvetica-Bold')
         .text('2. DO IMÓVEL')
         .moveDown(0.5);

      doc.fontSize(11)
         .font('Helvetica')
         .text(`Apartamento: ${apartment.number}`)
         .text(`Andar: ${apartment.floor}`)
         .text(`Endereço: ${apartment.address}`)
         .moveDown(2);

      // Terms section
      doc.fontSize(14)
         .font('Helvetica-Bold')
         .text('3. DO PRAZO E VALOR')
         .moveDown(0.5);

      doc.fontSize(11)
         .font('Helvetica')
         .text(`Data de Início: ${new Date(contract.startDate).toLocaleDateString('pt-BR')}`)
         .text(`Data de Término: ${new Date(contract.endDate).toLocaleDateString('pt-BR')}`)
         .text(`Valor Mensal: R$ ${contract.monthlyRent.toFixed(2).replace('.', ',')}`)
         .moveDown(2);

      // Conditions
      doc.fontSize(14)
         .font('Helvetica-Bold')
         .text('4. DAS CONDIÇÕES GERAIS')
         .moveDown(0.5);

      doc.fontSize(11)
         .font('Helvetica')
         .text('4.1. O pagamento do aluguel deverá ser efetuado até o dia 10 de cada mês.')
         .moveDown(0.3)
         .text('4.2. O LOCATÁRIO se compromete a manter o imóvel em bom estado de conservação.')
         .moveDown(0.3)
         .text('4.3. Eventuais reparos necessários deverão ser comunicados ao LOCADOR.')
         .moveDown(0.3)
         .text('4.4. O LOCATÁRIO não poderá sublocar ou ceder o imóvel sem autorização prévia.')
         .moveDown(0.3)
         .text('4.5. As contas de consumo (água, luz, gás) são de responsabilidade do LOCATÁRIO.')
         .moveDown(2);

      // Termination
      doc.fontSize(14)
         .font('Helvetica-Bold')
         .text('5. DA RESCISÃO')
         .moveDown(0.5);

      doc.fontSize(11)
         .font('Helvetica')
         .text('5.1. Qualquer das partes poderá rescindir o contrato com aviso prévio de 30 dias.')
         .moveDown(0.3)
         .text('5.2. Em caso de inadimplência, o contrato poderá ser rescindido imediatamente.')
         .moveDown(3);

      // Signatures
      doc.fontSize(11)
         .text(`Florianópolis, ${new Date().toLocaleDateString('pt-BR')}`)
         .moveDown(3);

      doc.text('_'.repeat(40), 50, doc.y)
         .text('LOCADOR', 50, doc.y + 5)
         .text('BASE250 Gestão de Imóveis', 50, doc.y + 5);

      doc.text('_'.repeat(40), 300, doc.y - 30)
         .text('LOCATÁRIO', 300, doc.y + 5)
         .text(tenant.name, 300, doc.y + 5);

      // Footer
      doc.fontSize(9)
         .font('Helvetica')
         .text(
           'Documento gerado automaticamente pelo sistema BASE250 v2.0.0',
           50,
           doc.page.height - 50,
           { align: 'center' }
         );

      doc.end();
    } catch (error) {
      reject(error);
    }
  });
}

module.exports = { generateContractPDF };
