import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import Link from 'next/link';

export default function Tenants() {
  const [tenants, setTenants] = useState([]);
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (!token) {
      router.push('/');
      return;
    }

    fetch(`${process.env.NEXT_PUBLIC_API_URL}/tenants`, {
      headers: { Authorization: `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => setTenants(data))
      .catch(console.error);
  }, [router]);

  return (
    <div>
      <div className="header">
        <div className="container" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h1>Inquilinos</h1>
          <Link href="/dashboard" className="btn btn-secondary">
            Voltar ao Dashboard
          </Link>
        </div>
      </div>

      <div className="container">
        <div className="card">
          <h2 style={{ marginBottom: '20px' }}>Lista de Inquilinos</h2>
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse' }}>
              <thead>
                <tr style={{ borderBottom: '2px solid #e5e7eb' }}>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Nome</th>
                  <th style={{ padding: '10px', textAlign: 'left' }}>CPF</th>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Email</th>
                  <th style={{ padding: '10px', textAlign: 'left' }}>Telefone</th>
                  <th style={{ padding: '10px', textAlign: 'center' }}>Status</th>
                </tr>
              </thead>
              <tbody>
                {tenants.map(tenant => (
                  <tr key={tenant.id} style={{ borderBottom: '1px solid #e5e7eb' }}>
                    <td style={{ padding: '10px' }}>{tenant.name}</td>
                    <td style={{ padding: '10px' }}>{tenant.cpf}</td>
                    <td style={{ padding: '10px' }}>{tenant.email}</td>
                    <td style={{ padding: '10px' }}>{tenant.phone}</td>
                    <td style={{ padding: '10px', textAlign: 'center' }}>
                      <span style={{
                        padding: '4px 8px',
                        borderRadius: '4px',
                        fontSize: '12px',
                        background: '#dcfce7',
                        color: '#166534'
                      }}>
                        {tenant.status === 'active' ? 'Ativo' : 'Inativo'}
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
