/**
 * BASE250 - App JavaScript
 * Sistema de Gestão de Imóveis
 * 
 * @package BASE250
 * @version 2.0.0
 * @author Eng. Diogo
 * @license MIT
 */

class BASE250App {
    constructor() {
        this.init();
    }
    
    init() {
        this.updateDateTime();
        this.setupEventListeners();
        setInterval(() => this.updateDateTime(), 60000);
    }
    
    /**
     * Toggle de seção do menu accordion
     */
    toggleSection(sectionName) {
        const section = document.querySelector(`[data-section="${sectionName}"]`);
        const wasOpen = section.classList.contains('open');
        
        // Fechar todas as seções
        document.querySelectorAll('.nav-section').forEach(s => {
            s.classList.remove('open');
        });
        
        // Se não estava aberta, abrir
        if (!wasOpen) {
            section.classList.add('open');
        }
    }
    
    /**
     * Navegação entre páginas
     */
    navigateTo(page, element) {
        // Esconder todas as páginas
        document.querySelectorAll('.page').forEach(p => {
            p.classList.remove('active');
        });

        // Mostrar página selecionada
        const pageEl = document.getElementById('page-' + page);
        if (pageEl) {
            pageEl.classList.add('active');
        }

        // Remover active de todos os nav-items
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
        });

        // Adicionar active ao item clicado
        if (element) {
            element.classList.add('active');
        }

        // Atualizar seção ativa
        const section = element ? element.closest('.nav-section') : null;
        document.querySelectorAll('.nav-section').forEach(s => {
            s.classList.remove('active');
        });
        if (section) {
            section.classList.add('active');
        }

        // Fechar sidebar em mobile
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.remove('open');
        }
    }
    
    /**
     * Atualizar data e hora do header
     */
    updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        const formatted = now.toLocaleDateString('pt-BR', options);
        const el = document.getElementById('datetime');
        if (el) el.textContent = formatted;
    }
    
    /**
     * Toggle sidebar para mobile
     */
    toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('open');
    }
    
    /**
     * Exibir notificação toast
     */
    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <span>${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideInUp 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    /**
     * Configurar event listeners
     */
    setupEventListeners() {
        // Fechar sidebar ao redimensionar
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                document.getElementById('sidebar')?.classList.remove('open');
            }
        });
    }
}

// Inicializar aplicação
document.addEventListener('DOMContentLoaded', () => {
    window.BASE250 = new BASE250App();
});

// Funções globais para compatibilidade
function toggleSection(sectionName) {
    window.BASE250?.toggleSection(sectionName);
}

function navigateTo(page, element) {
    window.BASE250?.navigateTo(page, element);
}

function toggleSidebar() {
    window.BASE250?.toggleSidebar();
}

function showToast(message, type) {
    window.BASE250?.showToast(message, type);
}
