import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import Link from 'next/link';

export default function Reports() {
  const [financialReport, setFinancialReport] = useState(null);
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (!token) {
      router.push('/');
      return;
    }

    fetch(`${process.env.NEXT_PUBLIC_API_URL}/reports/financial`, {
      headers: { Authorization: `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => setFinancialReport(data))
      .catch(console.error);
  }, [router]);

  if (!financialReport) return <div>Carregando...</div>;

  return (
    <div>
      <div className="header">
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h1>Relatórios Financeiros</h1>
          <Link href="/dashboard" className="btn btn-secondary">
            Voltar ao Dashboard
          </Link>
        </div>
      </div>

      <div className="container">
        <div className="grid">
          <div className="stat-card">
            <h3>Receita Total</h3>
            <div className="value" style={{ color: '#059669' }}>
              R$ {financialReport.revenue.total.toFixed(2).replace('.', ',')}
            </div>
          </div>
          <div className="stat-card">
            <h3>Despesas</h3>
            <div className="value" style={{ color: '#dc2626' }}>
              R$ {financialReport.expenses.total.toFixed(2).replace('.', ',')}
            </div>
          </div>
          <div className="stat-card">
            <h3>Lucro Líquido</h3>
            <div className="value" style={{ color: '#2563eb' }}>
              R$ {financialReport.netIncome.toFixed(2).replace('.', ',')}
            </div>
          </div>
          <div className="stat-card">
            <h3>Taxa de Ocupação</h3>
            <div className="value">
              {(financialReport.occupancyRate * 100).toFixed(0)}%
            </div>
          </div>
        </div>

        <div className="card">
          <h2 style={{ marginBottom: '20px' }}>Detalhamento da Receita</h2>
          <div style={{ display: 'grid', gap: '15px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', padding: '10px', background: '#f9fafb', borderRadius: '6px' }}>
              <span>Receita Recebida</span>
              <strong style={{ color: '#059669' }}>
                R$ {financialReport.revenue.received.toFixed(2).replace('.', ',')}
              </strong>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between', padding: '10px', background: '#f9fafb', borderRadius: '6px' }}>
              <span>Receita Pendente</span>
              <strong style={{ color: '#ea580c' }}>
                R$ {financialReport.revenue.pending.toFixed(2).replace('.', ',')}
              </strong>
            </div>
          </div>
        </div>

        <div className="card">
          <h2 style={{ marginBottom: '20px' }}>Detalhamento de Despesas</h2>
          <div style={{ display: 'grid', gap: '15px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', padding: '10px', background: '#f9fafb', borderRadius: '6px' }}>
              <span>Manutenção</span>
              <strong>R$ {financialReport.expenses.maintenance.toFixed(2).replace('.', ',')}</strong>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between', padding: '10px', background: '#f9fafb', borderRadius: '6px' }}>
              <span>Utilidades</span>
              <strong>R$ {financialReport.expenses.utilities.toFixed(2).replace('.', ',')}</strong>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between', padding: '10px', background: '#f9fafb', borderRadius: '6px' }}>
              <span>Administração</span>
              <strong>R$ {financialReport.expenses.administration.toFixed(2).replace('.', ',')}</strong>
            </div>
          </div>
        </div>

        <div className="card">
          <h2 style={{ marginBottom: '20px' }}>Imóveis</h2>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '15px' }}>
            <div style={{ padding: '15px', background: '#f0f9ff', borderRadius: '6px', textAlign: 'center' }}>
              <div style={{ fontSize: '24px', fontWeight: 'bold', color: '#0369a1' }}>
                {financialReport.properties.total}
              </div>
              <div style={{ fontSize: '14px', color: '#075985' }}>Total</div>
            </div>
            <div style={{ padding: '15px', background: '#dcfce7', borderRadius: '6px', textAlign: 'center' }}>
              <div style={{ fontSize: '24px', fontWeight: 'bold', color: '#166534' }}>
                {financialReport.properties.occupied}
              </div>
              <div style={{ fontSize: '14px', color: '#166534' }}>Ocupados</div>
            </div>
            <div style={{ padding: '15px', background: '#fef3c7', borderRadius: '6px', textAlign: 'center' }}>
              <div style={{ fontSize: '24px', fontWeight: 'bold', color: '#92400e' }}>
                {financialReport.properties.available}
              </div>
              <div style={{ fontSize: '14px', color: '#92400e' }}>Disponíveis</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
