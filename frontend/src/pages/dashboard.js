import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import Link from 'next/link';

export default function Dashboard() {
  const [stats, setStats] = useState(null);
  const [apartments, setApartments] = useState([]);
  const [user, setUser] = useState(null);
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem('token');
    const userData = localStorage.getItem('user');

    if (!token) {
      router.push('/');
      return;
    }

    setUser(JSON.parse(userData));

    // Fetch dashboard stats
    fetch(`${process.env.NEXT_PUBLIC_API_URL}/apartments/dashboard/stats`, {
      headers: { Authorization: `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => setStats(data))
      .catch(console.error);

    // Fetch apartments
    fetch(`${process.env.NEXT_PUBLIC_API_URL}/apartments`, {
      headers: { Authorization: `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => setApartments(data))
      .catch(console.error);
  }, [router]);

  const handleLogout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    router.push('/');
  };

  if (!user) return <div>Loading...</div>;

  return (
    <div>
      <div className="header">
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div>
            <h1>BASE250 Dashboard</h1>
            <p>Bem-vindo, {user.name}</p>
          </div>
          <button onClick={handleLogout} className="btn btn-secondary">
            Sair
          </button>
        </div>
      </div>

      <div className="container">
        <div className="grid">
          <div className="stat-card">
            <h3>Total de Apartamentos</h3>
            <div className="value">{stats?.total || 0}</div>
          </div>
          <div className="stat-card">
            <h3>Ocupados</h3>
            <div className="value" style={{ color: '#059669' }}>{stats?.occupied || 0}</div>
          </div>
          <div className="stat-card">
            <h3>Disponíveis</h3>
            <div className="value" style={{ color: '#2563eb' }}>{stats?.available || 0}</div>
          </div>
          <div className="stat-card">
            <h3>Receita Mensal</h3>
            <div className="value" style={{ color: '#059669' }}>
              R$ {stats?.totalRevenue?.toFixed(2).replace('.', ',') || '0,00'}
            </div>
          </div>
        </div>

        <div style={{ display: 'flex', gap: '10px', marginBottom: '20px', flexWrap: 'wrap' }}>
          <Link href="/contracts" className="btn btn-primary">
            Contratos
          </Link>
          <Link href="/tenants" className="btn btn-primary">
            Inquilinos
          </Link>
          <Link href="/payments" className="btn btn-primary">
            Pagamentos
          </Link>
          <Link href="/reports" className="btn btn-primary">
            Relatórios
          </Link>
        </div>

        <div className="card">
          <h2 style={{ marginBottom: '20px' }}>Apartamentos</h2>
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse' }}>
              <thead>
                <tr style={{ borderBottom: '2px solid #e5e7eb' }}>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Número</th>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Andar</th>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Quartos</th>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Status</th>
                  <th style={{ padding: '10px', textAlign: 'right' }}>Aluguel</th>
                </tr>
              </thead>
              <tbody>
                {apartments.map(apt => (
                  <tr key={apt.id} style={{ borderBottom: '1px solid #e5e7eb' }}>
                    <td style={{ padding: '10px' }}>{apt.number}</td>
                    <td style={{ padding: '10px' }}>{apt.floor}</td>
                    <td style={{ padding: '10px' }}>{apt.bedrooms}</td>
                    <td style={{ padding: '10px' }}>
                      <span style={{
                        padding: '4px 8px',
                        borderRadius: '4px',
                        fontSize: '12px',
                        background: apt.status === 'occupied' ? '#dcfce7' : '#dbeafe',
                        color: apt.status === 'occupied' ? '#166534' : '#1e40af'
                      }}>
                        {apt.status === 'occupied' ? 'Ocupado' : 'Disponível'}
                      </span>
                    </td>
                    <td style={{ padding: '10px', textAlign: 'right' }}>
                      R$ {apt.monthlyRent.toFixed(2).replace('.', ',')}
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
