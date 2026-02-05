import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import Link from 'next/link';

export default function Contracts() {
  const [contracts, setContracts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [downloadingId, setDownloadingId] = useState(null);
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (!token) {
      router.push('/');
      return;
    }

    fetch(`${process.env.NEXT_PUBLIC_API_URL}/contracts`, {
      headers: { Authorization: `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => {
        setContracts(data);
        setLoading(false);
      })
      .catch(err => {
        console.error(err);
        setLoading(false);
      });
  }, [router]);

  const downloadPDF = async (contractId) => {
    const token = localStorage.getItem('token');
    setDownloadingId(contractId);

    try {
      const response = await fetch(
        `${process.env.NEXT_PUBLIC_API_URL}/contracts/${contractId}/pdf`,
        {
          headers: { Authorization: `Bearer ${token}` }
        }
      );

      if (!response.ok) {
        throw new Error('Failed to download PDF');
      }

      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `contrato-${contractId}.pdf`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);
    } catch (error) {
      console.error('PDF download error:', error);
      alert('Erro ao baixar PDF. Tente novamente.');
    } finally {
      setDownloadingId(null);
    }
  };

  if (loading) {
    return (
      <div style={{ 
        minHeight: '100vh', 
        display: 'flex', 
        alignItems: 'center', 
        justifyContent: 'center' 
      }}>
        Carregando...
      </div>
    );
  }

  return (
    <div>
      <div className="header">
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h1>Contratos</h1>
          <Link href="/dashboard" className="btn btn-secondary">
            Voltar ao Dashboard
          </Link>
        </div>
      </div>

      <div className="container">
        <div className="card">
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h2>Lista de Contratos</h2>
            <button className="btn btn-primary">+ Novo Contrato</button>
          </div>

          {contracts.length === 0 ? (
            <p style={{ textAlign: 'center', color: '#6b7280', padding: '40px' }}>
              Nenhum contrato encontrado
            </p>
          ) : (
            <div style={{ overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                <thead>
                  <tr style={{ borderBottom: '2px solid #e5e7eb' }}>
                    <th style={{ padding: '10px', textAlign: 'left' }}>ID</th>
                    <th style={{ padding: '10px', textAlign: 'left' }}>Inquilino</th>
                    <th style={{ padding: '10px', textAlign: 'left' }}>Apartamento</th>
                    <th style={{ padding: '10px', textAlign: 'left' }}>Início</th>
                    <th style={{ padding: '10px', textAlign: 'left' }}>Término</th>
                    <th style={{ padding: '10px', textAlign: 'right' }}>Valor</th>
                    <th style={{ padding: '10px', textAlign: 'center' }}>Status</th>
                    <th style={{ padding: '10px', textAlign: 'center' }}>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  {contracts.map(contract => (
                    <tr key={contract.id} style={{ borderBottom: '1px solid #e5e7eb' }}>
                      <td style={{ padding: '10px' }}>{contract.id}</td>
                      <td style={{ padding: '10px' }}>Inquilino #{contract.tenantId}</td>
                      <td style={{ padding: '10px' }}>Apt {contract.apartmentId}</td>
                      <td style={{ padding: '10px' }}>
                        {new Date(contract.startDate).toLocaleDateString('pt-BR')}
                      </td>
                      <td style={{ padding: '10px' }}>
                        {new Date(contract.endDate).toLocaleDateString('pt-BR')}
                      </td>
                      <td style={{ padding: '10px', textAlign: 'right' }}>
                        R$ {contract.monthlyRent.toFixed(2).replace('.', ',')}
                      </td>
                      <td style={{ padding: '10px', textAlign: 'center' }}>
                        <span style={{
                          padding: '4px 8px',
                          borderRadius: '4px',
                          fontSize: '12px',
                          background: contract.status === 'active' ? '#dcfce7' : '#fee2e2',
                          color: contract.status === 'active' ? '#166534' : '#991b1b'
                        }}>
                          {contract.status === 'active' ? 'Ativo' : 'Inativo'}
                        </span>
                      </td>
                      <td style={{ padding: '10px', textAlign: 'center' }}>
                        <button
                          onClick={() => downloadPDF(contract.id)}
                          disabled={downloadingId === contract.id}
                          className="btn btn-primary"
                          style={{ 
                            fontSize: '14px', 
                            padding: '6px 12px',
                            opacity: downloadingId === contract.id ? 0.6 : 1
                          }}
                        >
                          {downloadingId === contract.id ? '⏳ Gerando...' : '📄 Baixar PDF'}
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </div>

        <div className="card" style={{ marginTop: '20px', background: '#f0f9ff', border: '1px solid #bae6fd' }}>
          <h3 style={{ marginBottom: '10px', color: '#0369a1' }}>✓ Geração de PDF Implementada</h3>
          <p style={{ color: '#0c4a6e' }}>
            Clique no botão "Baixar PDF" para gerar e baixar o contrato em formato PDF.
            O documento inclui todas as informações do contrato, dados do inquilino e do imóvel.
          </p>
        </div>
      </div>
    </div>
  );
}
