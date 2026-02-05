import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import Link from 'next/link';

export default function Payments() {
  const [payments, setPayments] = useState([]);
  const [stats, setStats] = useState(null);
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (!token) {
      router.push('/');
      return;
    }

    fetch(`${process.env.NEXT_PUBLIC_API_URL}/payments`, {
      headers: { Authorization: `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => setPayments(data))
      .catch(console.error);

    fetch(`${process.env.NEXT_PUBLIC_API_URL}/payments/stats/summary`, {
      headers: { Authorization: `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => setStats(data))
      .catch(console.error);
  }, [router]);

  return (
    <div>
      <div className="header">
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h1>Controle de Pagamentos</h1>
          <Link href="/dashboard" className="btn btn-secondary">
            Voltar ao Dashboard
          </Link>
        </div>
      </div>

      <div className="container">
        {stats && (
          <div className="grid">
            <div className="stat-card">
              <h3>Total Recebido</h3>
              <div className="value" style={{ color: '#059669' }}>
                R$ {stats.paid.toFixed(2).replace('.', ',')}
              </div>
            </div>
            <div className="stat-card">
              <h3>Pendente</h3>
              <div className="value" style={{ color: '#ea580c' }}>
                R$ {stats.pending.toFixed(2).replace('.', ',')}
              </div>
            </div>
            <div className="stat-card">
              <h3>Total de Pagamentos</h3>
              <div className="value">{stats.count.total}</div>
            </div>
          </div>
        )}

        <div className="card">
          <h2 style={{ marginBottom: '20px' }}>Histórico de Pagamentos</h2>
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse' }}>
              <thead>
                <tr style={{ borderBottom: '2px solid #e5e7eb' }}>
                  <th style={{ padding: '10px', textAlign: 'left' }}>ID</th>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Inquilino</th>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Vencimento</th>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Pagamento</th>
                  <th style={{ padding: '10px', textAlign: 'right' }}>Valor</th>
                  <th style={{ padding: '10px', textAlign: 'center' }}>Status</th>
                </tr>
              </thead>
              <tbody>
                {payments.map(payment => (
                  <tr key={payment.id} style={{ borderBottom: '1px solid #e5e7eb' }}>
                    <td style={{ padding: '10px' }}>{payment.id}</td>
                    <td style={{ padding: '10px' }}>Inquilino #{payment.tenantId}</td>
                    <td style={{ padding: '10px' }}>
                      {new Date(payment.dueDate).toLocaleDateString('pt-BR')}
                    </td>
                    <td style={{ padding: '10px' }}>
                      {payment.paidDate ? new Date(payment.paidDate).toLocaleDateString('pt-BR') : '-'}
                    </td>
                    <td style={{ padding: '10px', textAlign: 'right' }}>
                      R$ {payment.amount.toFixed(2).replace('.', ',')}
                    </td>
                    <td style={{ padding: '10px', textAlign: 'center' }}>
                      <span style={{
                        padding: '4px 8px',
                        borderRadius: '4px',
                        fontSize: '12px',
                        background: payment.status === 'paid' ? '#dcfce7' : '#fef3c7',
                        color: payment.status === 'paid' ? '#166534' : '#92400e'
                      }}>
                        {payment.status === 'paid' ? 'Pago' : 'Pendente'}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
}
