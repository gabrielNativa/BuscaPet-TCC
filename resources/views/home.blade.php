<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscaPet - Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
     :root {
    --color-primary: #e1b882;
    --color-danger: #ff7782;
    --color-success: #41f1b6;
    --color-warning: #ffbb55;
    --color-white: #fff;
    --color-info-dark: #7d8da1;
    --color-info-light: #dce1eb;
    --color-dark: #363949;
    --color-light: rgba(132, 139, 200, 0.18);
    --color-primary-variant: #5f3c0f;
    --color-dark-variant: #677483;
    --color-background: #f6f6f9;
    --card-border-radius: 2rem;
    --border-radius-1: 0.4rem;
    --border-radius-2: 0.8rem;
    --border-radius-3: 1.2rem;

    --card-padding: 1.8rem;
    --padding-1: 1.2rem;

    --box-shadow: 0 2rem 3rem var(--color-light);
}

.dark-mode {
    --color-background: #181a1e;
    --color-white: #202528;
    --color-dark: #edeffd;
    --color-dark-variant: #a3bdcc;
    --color-light: rgba(0, 0, 0, 0.4);

    --box-shadow: 0 2rem 3rem var(--color-light);
}

body.dark-mode {
    --color-background: #181a1e;
    --color-white: #202528;
    --color-dark: #edeffd;
    --color-dark-variant: #4F4F4F;
    --color-light: rgba(0, 0, 0, 0.4);

    --box-shadow: 0 2rem 3rem var(--color-light);
}

       
        .chart-container.small-chart {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 30px;
            min-height: 300px;
            position: relative;
        }
        
        #categoryChart, #weeklyChart {
            max-height: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .view-all-button {
            display: flex;
            align-items: center;
            background-color: #e1b882;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .view-all-button:hover {
            background-color: #d0a871;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .view-all-button i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }

        /* Responsividade para telas menores */
        @media screen and (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
            
            .chart-card {
                height: 400px !important;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .view-all-button {
                margin-top: 0.5rem;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        @include('componentes.menuAdm')
        <main>
            <h1>Dashboard</h1>
            
            <div class="insights">
                <div class="sales">
                    <span class="material-icons-sharp">pets</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Adoções Disponíveis</h3>
                            <h1>{{ $totalAdocao }}</h1>
                        </div>
                    </div>
                </div>

                <div class="expenses">
                    <span class="material-icons-sharp">person</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Usuários Cadastrados</h3>
                            <h1>{{ $totalUsuarios }}</h1>
                        </div>
                    </div>
                </div>

                <div class="income">
                    <span class="material-icons-sharp">search</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Animais Perdidos</h3>
                            <h1>{{ $totalAnimaisPerdidos }}</h1>
                        </div>
                    </div>
                </div>

                <div class="warning">
                    <span class="material-icons-sharp">favorite</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Animais Encontrados</h3>
                            <h1>{{ $totalAnimaisEncontrados }}</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="charts-container">
                <div class="chart-card">
                    <h2>
                        <span class="material-icons-sharp">pie_chart</span>
                        Distribuição por Categoria
                    </h2>
                    <div class="chart-container small-chart">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                
                <div class="chart-card">
                    <h2>
                        <span class="material-icons-sharp">trending_up</span>
                        Evolução Semanal de Registros
                    </h2>
                    <div class="chart-container small-chart">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="recent-ongs-section">
                <div class="section-header">
                    <h2>
                        <i class="material-icons-sharp">groups</i>
                        ONGs Recentes
                    </h2>
                    <a href="{{ route('ong.index') }}" class="view-all-button">
                        <i class="material-icons-sharp">visibility</i>
                        Ver todas as ONGs
                    </a>
                </div>

                @if(isset($recentOngs) && $recentOngs->isNotEmpty())
                <div class="ongs-list">
                    @foreach($recentOngs as $ong)
                    <div class="ong-card">
                        <div class="ong-content">
                            <div class="ong-main-info">
                                <h3 class="ong-name">{{ $ong->nomeOng ?? 'Nome não disponível' }}</h3>
                                <p class="ong-email">
                                    <i class="material-icons-sharp">email</i>
                                    {{ $ong->emailOng ?? 'Email não disponível' }}
                                </p>
                            </div>
                            <div class="ong-meta">
                                <span class="status-badge {{ $ong->status ?? 'pendente' }}">
                                    {{ ucfirst($ong->status ?? 'pendente') }}
                                </span>
                                @if(isset($ong->cnpjOng))
                                <span class="ong-cnpj">
                                    {{ $ong->cnpjOng }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="material-icons-sharp">info</i>
                    <p>Nenhuma ONG cadastrada recentemente</p>
                </div>
                @endif
            </div>
        </main>

        @include('componentes.headerAdm')
    </div>

    <script>
        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            const toggleButtons = document.querySelectorAll('.theme-toggler span');
            toggleButtons.forEach(button => {
                button.classList.toggle('active');
            });
        }

        function animateCards() {
            const cards = document.querySelectorAll('.insights > div, .chart-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        window.addEventListener('load', () => {
            animateCards();
        });

        const animalData = {
            labels: ['Cães', 'Gatos'],
            datasets: [{
                data: [{{ $totalCachorros }}, {{ $totalGatos }}],
                backgroundColor: [
                    '#FF6B6B', 
                    '#4ECDC4'
                ],
                borderColor: '#fff',
                borderWidth: 3,
                hoverBackgroundColor: [
                    '#FF5252',
                    '#26A69A'
                ],
                hoverBorderWidth: 4,
                hoverOffset: 8
            }]
        };

        const pieConfig = {
            type: 'doughnut', 
            data: animalData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 10
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribuição de Animais',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        color: '#2c3e50',
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            font: {
                                size: 13,
                                weight: '500'
                            },
                            color: '#2c3e50',
                            generateLabels: function(chart) {
                                const data = chart.data;
                                const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    
                                    return {
                                        text: `${label} (${percentage}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].backgroundColor[i],
                                        lineWidth: 0,
                                        pointStyle: 'circle',
                                        index: i
                                    };
                                });
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(44, 62, 80, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#bdc3c7',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.parsed} animais (${percentage}%)`;
                            },
                            afterLabel: function(context) {
                                const labels = ['🐕', '🐱'];
                                return labels[context.dataIndex] || '';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                elements: {
                    arc: {
                        borderRadius: 6
                    }
                },
                cutout: '40%', 
                interaction: {
                    intersect: true
                }
            }
        };

        const animalPieChart = new Chart(
            document.getElementById('categoryChart'),
            pieConfig
        );

        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw: function(chart) {
                if (chart.config.type === 'doughnut') {
                    const ctx = chart.ctx;
                    const centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                    const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                    
                    const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    
                    ctx.font = 'bold 24px Arial';
                    ctx.fillStyle = '#2c3e50';
                    ctx.fillText(total, centerX, centerY - 8);
                    
                    ctx.font = '14px Arial';
                    ctx.fillStyle = '#7f8c8d';
                    ctx.fillText('Total', centerX, centerY + 15);
                    
                    ctx.restore();
                }
            }
        };

        Chart.register(centerTextPlugin);

        const semanas = @json(array_map(function($item) {
            return $item['semana'];
        }, $dadosSemanais));
            
        const dadosAdocoes = @json(array_map(function($item) {
            return $item['adocoes'];
        }, $dadosSemanais));
            
        const dadosPerdidos = @json(array_map(function($item) {
            return $item['perdidos'];
        }, $dadosSemanais));
            
        const dadosEncontrados = @json(array_map(function($item) {
            return $item['encontrados'];
        }, $dadosSemanais));

        const weeklyData = {
            labels: semanas,
            datasets: [
                {
                    label: 'Adoções',
                    data: dadosAdocoes,
                    borderColor: '#4361EE',
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    borderWidth: 3,
                    color: 'var(--color-dark)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4361EE',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                },
                {
                    label: 'Perdidos',
                    data: dadosPerdidos,
                    borderColor: '#F72585',
                    backgroundColor: 'rgba(247, 37, 133, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#F72585',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                },
                {
                    label: 'Encontrados',
                    data: dadosEncontrados,
                    borderColor: '#4CC9F0',
                    backgroundColor: 'rgba(76, 201, 240, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4CC9F0',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }
            ]
        };

        const weeklyConfig = {
            type: 'line',
            data: weeklyData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        bottom: 30,
                        left: 15,
                        right: 15,
                        top: 15
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 10,
                            padding: 15,
                            color: '#2c3e50',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(44, 62, 80, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#bdc3c7',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y} registros`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#2c3e50',
                            padding: 5,
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 10
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(189, 195, 199, 0.2)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#2c3e50',
                            padding: 10
                        },
                        grace: '5%'
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                },
                elements: {
                    line: {
                        tension: 0.4
                    }
                }
            }
        };

        const weeklyChart = new Chart(
            document.getElementById('weeklyChart'),
            weeklyConfig
        );
    </script>
</body>
</html>