// Dashboard Moderna - JavaScript
class DashboardModerna {
    constructor(data) {
      this.data = data;
      this.charts = {};
      this.init();
    }
  
    init() {
      this.animateNumbers();
      this.initializeCharts();
      this.setupEventListeners();
      this.animateCards();
    }
  
    // Animar números dos cards
    animateNumbers() {
      const numbers = document.querySelectorAll('.stat-number[data-target]');
      
      numbers.forEach(number => {
        const target = parseInt(number.getAttribute('data-target'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
  
        const timer = setInterval(() => {
          current += increment;
          if (current >= target) {
            number.textContent = target;
            clearInterval(timer);
          } else {
            number.textContent = Math.floor(current);
          }
        }, 16);
      });
    }
  
    // Inicializar gráficos
    initializeCharts() {
      this.createMiniCharts();
      this.createPetsDistributionChart();
    }
  
    // Criar mini gráficos nos cards de estatísticas
    createMiniCharts() {
      // Gráfico de visualizações
      const viewsCtx = document.getElementById('viewsChart');
      if (viewsCtx) {
        this.charts.views = new Chart(viewsCtx, {
          type: 'line',
          data: {
            labels: ['', '', '', '', '', '', ''],
            datasets: [{
              data: [12, 19, 15, 25, 22, 30, 28],
              borderColor: '#3b82f6',
              backgroundColor: 'rgba(59, 130, 246, 0.1)',
              borderWidth: 2,
              fill: true,
              tension: 0.4,
              pointRadius: 0
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: { display: false },
              y: { display: false }
            },
            elements: { point: { radius: 0 } }
          }
        });
      }
  
      // Gráfico de curtidas
      const likesCtx = document.getElementById('likesChart');
      if (likesCtx) {
        this.charts.likes = new Chart(likesCtx, {
          type: 'bar',
          data: {
            labels: ['', '', '', '', '', '', ''],
            datasets: [{
              data: [8, 12, 6, 15, 10, 18, 14],
              backgroundColor: '#10b981',
              borderRadius: 2
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: { display: false },
              y: { display: false }
            }
          }
        });
      }
  
      // Gráfico de comentários
      const commentsCtx = document.getElementById('commentsChart');
      if (commentsCtx) {
        this.charts.comments = new Chart(commentsCtx, {
          type: 'line',
          data: {
            labels: ['', '', '', '', '', '', ''],
            datasets: [{
              data: [5, 8, 4, 6, 3, 7, 4],
              borderColor: '#06b6d4',
              backgroundColor: 'rgba(6, 182, 212, 0.1)',
              borderWidth: 2,
              fill: true,
              tension: 0.4,
              pointRadius: 0
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: { display: false },
              y: { display: false }
            }
          }
        });
      }
  
      // Gráfico de animais
      const animalsCtx = document.getElementById('animalsChart');
      if (animalsCtx) {
        this.charts.animals = new Chart(animalsCtx, {
          type: 'doughnut',
          data: {
            datasets: [{
              data: [this.data.animaisAdocao, this.data.animaisAdotados, this.data.animaisAnalise],
              backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
              borderWidth: 0
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } }
          }
        });
      }
    }
  
    // Criar gráfico principal de distribuição de pets
    createPetsDistributionChart() {
      const ctx = document.getElementById('petsDistributionChart');
      if (!ctx) return;
  
      this.charts.petsDistribution = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Disponíveis para Adoção', 'Já Adotados', 'Em Análise'],
          datasets: [{
            data: [this.data.animaisAdocao, this.data.animaisAdotados, this.data.animaisAnalise],
            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
            borderColor: ['#2563eb', '#059669', '#d97706'],
            borderWidth: 2,
            hoverOffset: 8,
            hoverBorderWidth: 3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '60%',
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: 'rgba(30, 41, 59, 0.95)',
              titleColor: '#fff',
              bodyColor: '#fff',
              borderColor: '#e2e8f0',
              borderWidth: 1,
              cornerRadius: 12,
              displayColors: true,
              padding: 12,
              titleFont: { size: 14, weight: '600' },
              bodyFont: { size: 13 },
              callbacks: {
                label: function(context) {
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = total > 0 ? ((context.parsed * 100) / total).toFixed(1) : 0;
                  return `${context.label}: ${context.parsed} (${percentage}%)`;
                }
              }
            }
          },
          animation: {
            animateRotate: true,
            duration: 1500,
            easing: 'easeOutQuart'
          },
          elements: {
            arc: {
              borderJoinStyle: 'round'
            }
          }
        }
      });
    }
  
    // Configurar event listeners
    setupEventListeners() {
      // Botão de refresh
      const refreshBtn = document.querySelector('.btn-refresh');
      if (refreshBtn) {
        refreshBtn.addEventListener('click', () => this.refreshDashboard());
      }
  
      // Filtro do gráfico
      const chartFilter = document.querySelector('.chart-filter');
      if (chartFilter) {
        chartFilter.addEventListener('change', (e) => this.filterChart(e.target.value));
      }
  
      // Ações rápidas
      const quickActions = document.querySelectorAll('.quick-action-btn');
      quickActions.forEach(btn => {
        btn.addEventListener('click', (e) => this.handleQuickAction(e.currentTarget));
      });
  
      // Hover effects nos cards
      const cards = document.querySelectorAll('.dashboard-card, .stat-card');
      cards.forEach(card => {
        card.addEventListener('mouseenter', () => this.cardHoverIn(card));
        card.addEventListener('mouseleave', () => this.cardHoverOut(card));
      });
    }
  
    // Animar cards na entrada
    animateCards() {
      const cards = document.querySelectorAll('.stat-card, .dashboard-card');
      cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
          card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, index * 100);
      });
    }
  
    // Refresh dashboard
    refreshDashboard() {
      const refreshBtn = document.querySelector('.btn-refresh');
      const icon = refreshBtn.querySelector('i');
      
      // Animar ícone
      icon.style.transform = 'rotate(360deg)';
      refreshBtn.disabled = true;
      
      // Simular carregamento
      setTimeout(() => {
        icon.style.transform = 'rotate(0deg)';
        refreshBtn.disabled = false;
        this.showNotification('Dashboard atualizada com sucesso!', 'success');
      }, 1000);
    }
  
    // Filtrar gráfico
    filterChart(period) {
      // Aqui você implementaria a lógica de filtro
      console.log('Filtrando por período:', period);
      this.showNotification(`Filtro aplicado: ${period}`, 'info');
    }
  
    // Lidar com ações rápidas
    handleQuickAction(button) {
      const text = button.querySelector('span').textContent;
      
      // Adicionar efeito de clique
      button.style.transform = 'scale(0.95)';
      setTimeout(() => {
        button.style.transform = '';
      }, 150);
      
      this.showNotification(`Ação "${text}" será implementada em breve`, 'info');
    }
  
    // Efeitos de hover nos cards
    cardHoverIn(card) {
      const icon = card.querySelector('.stat-icon, .card-title i');
      if (icon) {
        icon.style.transform = 'scale(1.1) rotate(5deg)';
      }
    }
  
    cardHoverOut(card) {
      const icon = card.querySelector('.stat-icon, .card-title i');
      if (icon) {
        icon.style.transform = 'scale(1) rotate(0deg)';
      }
    }
  
    // Mostrar notificação
    showNotification(message, type = 'info') {
      // Criar elemento de notificação
      const notification = document.createElement('div');
      notification.className = `notification notification-${type}`;
      notification.innerHTML = `
        <div class="notification-content">
          <i class="fas fa-${this.getNotificationIcon(type)}"></i>
          <span>${message}</span>
        </div>
        <button class="notification-close">
          <i class="fas fa-times"></i>
        </button>
      `;
  
      // Adicionar estilos
      Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        background: this.getNotificationColor(type),
        color: 'white',
        padding: '1rem 1.5rem',
        borderRadius: '12px',
        boxShadow: '0 10px 25px rgba(0, 0, 0, 0.15)',
        zIndex: '9999',
        display: 'flex',
        alignItems: 'center',
        gap: '1rem',
        minWidth: '300px',
        transform: 'translateX(100%)',
        transition: 'transform 0.3s ease'
      });
  
      // Adicionar ao DOM
      document.body.appendChild(notification);
  
      // Animar entrada
      setTimeout(() => {
        notification.style.transform = 'translateX(0)';
      }, 100);
  
      // Configurar botão de fechar
      const closeBtn = notification.querySelector('.notification-close');
      closeBtn.style.background = 'none';
      closeBtn.style.border = 'none';
      closeBtn.style.color = 'white';
      closeBtn.style.cursor = 'pointer';
      closeBtn.style.padding = '0.25rem';
      closeBtn.style.borderRadius = '4px';
  
      closeBtn.addEventListener('click', () => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
      });
  
      // Auto remover após 5 segundos
      setTimeout(() => {
        if (notification.parentNode) {
          notification.style.transform = 'translateX(100%)';
          setTimeout(() => notification.remove(), 300);
        }
      }, 5000);
    }
  
    getNotificationIcon(type) {
      const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
      };
      return icons[type] || 'info-circle';
    }
  
    getNotificationColor(type) {
      const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
      };
      return colors[type] || '#3b82f6';
    }
  
    // Atualizar dados
    updateData(newData) {
      this.data = { ...this.data, ...newData };
      
      // Atualizar gráficos
      if (this.charts.petsDistribution) {
        this.charts.petsDistribution.data.datasets[0].data = [
          this.data.animaisAdocao,
          this.data.animaisAdotados,
          this.data.animaisAnalise
        ];
        this.charts.petsDistribution.update();
      }
  
      if (this.charts.animals) {
        this.charts.animals.data.datasets[0].data = [
          this.data.animaisAdocao,
          this.data.animaisAdotados,
          this.data.animaisAnalise
        ];
        this.charts.animals.update();
      }
    }
  
    // Destruir instância
    destroy() {
      Object.values(this.charts).forEach(chart => {
        if (chart && typeof chart.destroy === 'function') {
          chart.destroy();
        }
      });
    }
  }
  
  // Função global para inicializar dashboard
  function initializeDashboard(data) {
    window.dashboardInstance = new DashboardModerna(data);
  }
  
  // Função global para refresh
  function refreshDashboard() {
    if (window.dashboardInstance) {
      window.dashboardInstance.refreshDashboard();
    }
  }
  
  // Utilitários globais
  window.DashboardUtils = {
    formatNumber: (num) => {
      if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
      } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
      }
      return num.toString();
    },
  
    formatDate: (date) => {
      return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      }).format(new Date(date));
    },
  
    formatTime: (date) => {
      return new Intl.DateTimeFormat('pt-BR', {
        hour: '2-digit',
        minute: '2-digit'
      }).format(new Date(date));
    }
  };
  
  // Configurações globais do Chart.js
  Chart.defaults.font.family = 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
  Chart.defaults.color = '#64748b';
  Chart.defaults.borderColor = '#e2e8f0';
  
  // Registrar service worker para PWA (opcional)
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js')
        .then(registration => {
          console.log('SW registered: ', registration);
        })
        .catch(registrationError => {
          console.log('SW registration failed: ', registrationError);
        });
    });
  }
  
  