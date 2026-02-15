<?php
/**
 * Dashboard Controller
 * Controla a página principal do sistema
 * 
 * @package BASE250
 * @version 2.0.0
 * @author Eng. Diogo
 */

declare(strict_types=1);

namespace BASE250\Controllers;

class DashboardController extends BaseController
{
    /**
     * Página principal do dashboard
     * 
     * @return void
     */
    public function index(): void
    {
        $data = [
            'pageTitle' => 'Dashboard - BASE250',
            'totalTenants' => 18,
            'totalPayments' => 15,
            'pendingApprovals' => 3,
            'overduePayments' => 2,
            'recentNotifications' => $this->getRecentNotifications()
        ];
        
        $this->render('dashboard/index', $data);
    }
    
    /**
     * Página de status do sistema
     * 
     * @return void
     */
    public function statusSistema(): void
    {
        $data = [
            'pageTitle' => 'Status do Sistema - BASE250',
            'systemStatus' => 'operational',
            'uptime' => '99.9%',
            'lastBackup' => '2025-02-07 03:00:00'
        ];
        
        $this->render('dashboard/status', $data);
    }
    
    /**
     * Busca notificações recentes
     * 
     * @return array
     */
    private function getRecentNotifications(): array
    {
        return [
            [
                'type' => 'error',
                'title' => 'Pagamento atrasado',
                'message' => 'Apto 105 - Carlos Eduardo está com 5 dias de atraso.'
            ],
            [
                'type' => 'warning',
                'title' => 'Cadastro pendente',
                'message' => '3 novos cadastros aguardando validação de documentos.'
            ],
            [
                'type' => 'success',
                'title' => 'Pagamento confirmado',
                'message' => 'Apto 201 - Pagamento de fevereiro confirmado.'
            ]
        ];
    }
}
