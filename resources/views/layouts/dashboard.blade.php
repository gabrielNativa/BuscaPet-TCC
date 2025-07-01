<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard - BuscaPet</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  @include('componentes.sidebar')
  
  <!-- Main Content -->
  <div class="main-content">
    <!-- Header -->
    <header class="dashboard-header">
      <div class="header-content">
        <div class="header-title">
          <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
          <p>Bem-vindo de volta! Aqui está um resumo das suas atividades.</p>
        </div>
        <div class="header-actions">
          <!-- Ícone de Interesses -->
          <div class="notification-icon-wrapper">
            <button class="btn-notification" id="interestsButton">
              <i class="fas fa-heart"></i>
              @if($interesses->count() > 0)
                <span class="notification-counter">{{ $interesses->count() }}</span>
              @endif
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Quick Stats -->
    <section class="quick-stats">
      <!-- Total de Animais Cadastrados -->
      <div class="stat-card stat-warning">
        <div class="stat-icon">
          <i class="fas fa-paw"></i>
        </div>
        <div class="stat-content">
          <h3>Animais Cadastrados</h3>
          <div class="stat-number">{{ ($animaisAdocao ?? 0) + ($animaisAdotados ?? 0) + ($animaisAnalise ?? 0) }}</div>
          <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>Total de animais</span>
          </div>
        </div>
      </div>

      <!-- Adoções em Andamento -->
      <div class="stat-card stat-primary">
        <div class="stat-icon">
          <i class="fas fa-search"></i>
        </div>
        <div class="stat-content">
          <h3>Adoções em Andamento</h3>
          <div class="stat-number">{{ $animaisAdocao ?? 0 }}</div>
          <div class="stat-change positive">
            <i class="fas fa-paw"></i>
            <span>Buscando lar</span>
          </div>
        </div>
      </div>

      <!-- Adoções Concluídas -->
      <div class="stat-card stat-success">
        <div class="stat-icon">
          <i class="fas fa-home"></i>
        </div>
        <div class="stat-content">
          <h3>Adoções Concluídas</h3>
          <div class="stat-number">{{ $animaisAdotados ?? 0 }}</div>
          <div class="stat-change positive">
            <i class="fas fa-heart"></i>
            <span>Encontraram lar</span>
          </div>
        </div>
      </div>

      <!-- Curtidas -->
      <div class="stat-card stat-info">
        <div class="stat-icon">
          <i class="fas fa-heart"></i>
        </div>
        <div class="stat-content">
          <h3>Curtidas</h3>
          <div class="stat-number">{{ $totalLikes ?? 0 }}</div>
          <div class="stat-change positive">
            <i class="fas fa-thumbs-up"></i>
            <span>Engajamento</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Dashboard Grid -->
    <section class="dashboard-grid">
      <!-- Posts por Categoria -->
      <div class="dashboard-card campaigns-card">
        <div class="card-header">
          <div class="card-title">
            <i class="fas fa-bullhorn"></i>
            <h3>Posts por Categoria</h3>
          </div>
        </div>
        <div class="card-content">
          @if(count($categoriesWithCount) > 0)
            <div class="chart-container" style="height: 300px;">
              <canvas id="postsByCategoryChart"></canvas>
            </div>
            <div class="chart-legend">
              <div class="legend-grid">
                @foreach($categoriesWithCount as $category)
                <div class="legend-item" style="--legend-color: {{ $category['color'] }};" title="{{ $category['name'] }}">
                  <div class="legend-color" style="background: {{ $category['color'] }};"></div>
                  <span class="legend-text">{{ $category['name'] }}</span>
                  <strong class="legend-count">{{ $category['count'] }}</strong>
                </div>
                @endforeach
              </div>
            </div>
          @else
            <p class="text-center py-4">Nenhum post cadastrado ainda.</p>
          @endif
        </div>
        <div class="card-footer">
          <a href="{{ route('posts.index') }}" class="btn-primary">
            <span>Gerenciar Posts</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Distribuição de Pets -->
      <div class="dashboard-card chart-card">
        <div class="card-header">
          <div class="card-title">
            <i class="fas fa-chart-pie"></i>
            <h3>Distribuição de Pets</h3>
          </div>
        </div>
        <div class="card-content">
          <div class="chart-container" style="height: 300px;">
            <canvas id="petsDistributionChart"></canvas>
          </div>
          <div class="chart-legend">
            <div class="legend-item">
              <div class="legend-color" style="background: #3b82f6;"></div>
              <span>Adoção</span>
              <strong>{{ $animaisAdocao ?? 0 }}</strong>
            </div>
            <div class="legend-item">
              <div class="legend-color" style="background: #10b981;"></div>
              <span>Adotados</span>
              <strong>{{ $animaisAdotados ?? 0 }}</strong>
            </div>
            <div class="legend-item">
              <div class="legend-color" style="background: #f59e0b;"></div>
              <span>Em Análise</span>
              <strong>{{ $animaisAnalise ?? 0 }}</strong>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Modal de Interesses -->
  <div class="modal" id="interestsModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-heart"></i> Interesses em Animais</h3>
        <button class="btn-close-modal" onclick="fecharModal()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        @if($interesses->count() > 0)
          <div class="interests-list">
            @foreach($interesses as $interesse)
              <div class="interest-item">
                <div class="interest-user">
                  <div class="user-avatar">
                    @if($interesse->usuario->imgUser)
                      <img src="{{ asset($interesse->usuario->imgUser) }}" alt="{{ $interesse->usuario->nomeUser }}">
                    @else
                      <i class="fas fa-user-circle"></i>
                    @endif
                  </div>
                  <div class="user-info">
                    <strong>{{ $interesse->usuario->nomeUser }}</strong>
                    <span>{{ $interesse->usuario->emailUser }}</span>
                    <small id="tel-{{ $interesse->usuario->idUser }}">{{ $interesse->usuario->telUser }}</small>
                  </div>
                </div>
                <div class="interest-animal">
                  @if($interesse->animal->imgPrincipal)
                    <img src="{{ asset('img/imgAnimal/' . $interesse->animal->imgPrincipal) }}" alt="{{ $interesse->animal->nomeAnimal }}" class="animal-image">
                  @else
                    <i class="fas fa-paw"></i>
                  @endif
                  <div class="animal-info">
                    <strong>{{ $interesse->animal->nomeAnimal }}</strong>
                    <span>{{ $interesse->animal->raca->nomeRaca }}</span>
                    <small>{{ $interesse->created_at->format('d/m/Y H:i') }}</small>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="empty-state">
            <i class="fas fa-heart"></i>
            <p>Nenhum interesse registrado</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Dados para os gráficos
      const categoriesData = {
        labels: @json(collect($categoriesWithCount)->pluck('name')),
        datasets: [{
          data: @json(collect($categoriesWithCount)->pluck('count')),
          backgroundColor: @json(collect($categoriesWithCount)->pluck('color')),
          borderWidth: 1
        }]
      };

      const petsData = {
        labels: ['Adoção', 'Adotados', 'Em Análise'],
        datasets: [{
          data: [
            {{ $animaisAdocao ?? 0 }},
            {{ $animaisAdotados ?? 0 }},
            {{ $animaisAnalise ?? 0 }}
          ],
          backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
          borderWidth: 1
        }]
      };

      // Gráfico de Posts por Categoria
      if (document.getElementById('postsByCategoryChart') && categoriesData.labels.length > 0) {
        new Chart(
          document.getElementById('postsByCategoryChart'),
          {
            type: 'doughnut',
            data: categoriesData,
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false
                }
              }
            }
          }
        );
      }

      // Gráfico de Distribuição de Pets
      if (document.getElementById('petsDistributionChart')) {
        new Chart(
          document.getElementById('petsDistributionChart'),
          {
            type: 'pie',
            data: petsData,
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false
                }
              }
            }
          }
        );
      }

      // Configuração do modal de interesses
      const interestsButton = document.getElementById('interestsButton');
      const interestsModal = document.getElementById('interestsModal');

      interestsButton.addEventListener('click', function() {
        interestsModal.style.display = 'flex';
      });

      // Animar números dos cards
      function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
          if (!startTimestamp) startTimestamp = timestamp;
          const progress = Math.min((timestamp - startTimestamp) / duration, 1);
          element.innerHTML = Math.floor(progress * (end - start) + start);
          if (progress < 1) {
            window.requestAnimationFrame(step);
          }
        };
        window.requestAnimationFrame(step);
      }

      document.querySelectorAll('.stat-number').forEach(el => {
        const target = parseInt(el.textContent);
        el.textContent = '0';
        animateValue(el, 0, target, 1000);
      });

      // Aplicar máscara nos telefones
      document.querySelectorAll('[id^="tel-"]').forEach(function(element) {
        const phone = element.textContent.replace(/\D/g, '');
        if (phone.length === 11) {
          element.textContent = `(${phone.substring(0, 2)}) ${phone.substring(2, 7)}-${phone.substring(7)}`;
        } else if (phone.length === 10) {
          element.textContent = `(${phone.substring(0, 2)}) ${phone.substring(2, 6)}-${phone.substring(6)}`;
        }
      });
    });

    function fecharModal() {
      document.getElementById('interestsModal').style.display = 'none';
    }

    // Fechar modal ao clicar fora do conteúdo
    window.addEventListener('click', function(event) {
      const modal = document.getElementById('interestsModal');
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
  </script>

  <style>
    /* Estilos para o ícone de interesses no header */
    .notification-icon-wrapper {
      position: relative;
    }

    .btn-notification {
      background: none;
      border: none;
      font-size: 18px;
      color: #555;
      cursor: pointer;
      padding: 8px;
      border-radius: 50%;
      transition: all 0.3s;
      position: relative;
    }

    .btn-notification:hover {
      background: #f0f0f0;
      color: #333;
    }

    .notification-counter {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #e74c3c;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: bold;
    }

    /* Melhorias na legenda do gráfico */
    .chart-legend {
      margin-top: 20px;
      padding: 16px;
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .legend-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 12px;
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      background: white;
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .legend-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-color: #cbd5e1;
    }

    .legend-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: var(--legend-color);
      transition: width 0.3s ease;
    }

    .legend-item:hover::before {
      width: 6px;
    }

    /* Tooltip para texto completo */
    .legend-item::after {
      content: attr(data-tooltip);
      position: absolute;
      bottom: 100%;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(0, 0, 0, 0.9);
      color: white;
      padding: 8px 12px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 1000;
      pointer-events: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      margin-bottom: 8px;
    }

    .legend-item:hover::after {
      opacity: 1;
      visibility: visible;
    }

    .legend-color {
      width: 16px;
      height: 16px;
      border-radius: 50%;
      flex-shrink: 0;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border: 2px solid white;
      position: relative;
    }

    .legend-color::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 8px;
      height: 8px;
      background: inherit;
      border-radius: 50%;
      opacity: 0.3;
    }

    .legend-text {
      flex: 1;
      font-size: 14px;
      font-weight: 500;
      color: #475569;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .legend-count {
      font-size: 16px;
      font-weight: 700;
      color: #1e293b;
      background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
      padding: 4px 12px;
      border-radius: 20px;
      border: 1px solid #cbd5e1;
      min-width: 40px;
      text-align: center;
    }

    .legend-item:hover .legend-count {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
      border-color: #2563eb;
    }

    .legend-item:hover .legend-text {
      color: #1e293b;
      font-weight: 600;
    }

    /* Estilos para o modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: white;
      border-radius: 8px;
      width: 90%;
      max-width: 800px;
      max-height: 80vh;
      display: flex;
      flex-direction: column;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
      padding: 16px 20px;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-header h3 {
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .btn-close-modal {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #777;
      padding: 5px;
      border-radius: 50%;
      transition: all 0.2s;
    }

    .btn-close-modal:hover {
      background: #f5f5f5;
      color: #333;
    }

    .modal-body {
      padding: 0;
      overflow-y: auto;
      flex: 1;
    }

    /* Estilos para a lista de interesses */
    .interests-list {
      max-height: 65vh;
      overflow-y: auto;
      padding: 0 10px;
    }

    .interest-item {
      display: flex;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #eee;
    }

    .interest-user {
      display: flex;
      align-items: center;
      flex: 1;
      min-width: 250px;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      overflow: hidden;
      margin-right: 12px;
      background-color: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .user-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .user-avatar i {
      font-size: 24px;
      color: #999;
    }

    .user-info {
      display: flex;
      flex-direction: column;
    }

    .user-info strong {
      font-weight: 600;
      font-size: 14px;
    }

    .user-info span, .user-info small {
      font-size: 12px;
      color: #666;
    }

    .interest-animal {
      display: flex;
      align-items: center;
      flex: 1;
      min-width: 250px;
    }

    .animal-image {
      width: 40px;
      height: 40px;
      border-radius: 4px;
      object-fit: cover;
      margin-right: 12px;
    }

    .animal-info {
      display: flex;
      flex-direction: column;
    }

    .animal-info strong {
      font-weight: 600;
      font-size: 14px;
    }

    .animal-info span {
      font-size: 12px;
      color: #666;
    }

    .animal-info small {
      font-size: 11px;
      color: #999;
      margin-top: 2px;
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #666;
    }

    .empty-state i {
      font-size: 48px;
      margin-bottom: 16px;
      opacity: 0.5;
    }
  </style>
</body>
</html>