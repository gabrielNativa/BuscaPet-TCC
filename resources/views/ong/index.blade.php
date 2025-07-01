<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscaPet - Todas as ONGs</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Estilos específicos para a página de ONGs */
        .ongs-container {
            padding: 1.8rem;
            margin-top: 1.4rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            padding-bottom: 1rem;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--color-primary), transparent);
        }

        .page-header h1 {
            font-size: 2rem;
            color: var(--color-dark);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            position: relative;
        }

        .page-header h1 i {
            font-size: 2.5rem;
            color: var(--color-primary);
            padding: 0.8rem;
            border-radius: 50%;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .back-button {
            display: flex;
            align-items: center;
            background: var(--color-light);
            color: var(--color-dark);
            padding: 0.7rem 1.2rem;
            border-radius: var(--border-radius-1);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 0.3rem 0.6rem rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .back-button:hover::before {
            width: 100%;
        }

        .back-button i {
            margin-right: 0.5rem;
            transition: transform 0.3s ease;
        }

        /* Filtros */
        .filters-container {
            background: var(--color-white);
            border-radius: var(--card-border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--box-shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .filters-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--color-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filters-title i {
            color: var(--color-primary);
        }

        .reset-filters {
            background: var(--color-light);
            color: var(--color-dark-variant);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-1);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .reset-filters:hover {
            background: var(--color-primary-light);
            color: var(--color-primary);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .filter-group {
            margin-bottom: 0.5rem;
        }

        .filter-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--color-dark-variant);
            font-weight: 500;
        }

        .filter-select, .filter-input {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: var(--border-radius-1);
            border: 1px solid var(--color-light);
            background: var(--color-light);
            color: var(--color-dark);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .filter-select:focus, .filter-input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(var(--color-primary-rgb), 0.1);
            outline: none;
        }

        .filter-select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            background-size: 1rem;
        }

        .apply-filters {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
        }

        .apply-filters-btn {
            background: var(--color-primary);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius-1);
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .apply-filters-btn:hover {
            background: var(--color-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(var(--color-primary-rgb), 0.2);
        }

        /* Contador de resultados */
        .results-count {
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            color: var(--color-dark-variant);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .results-count i {
            color: var(--color-primary);
        }

        .results-count strong {
            color: var(--color-dark);
            font-weight: 600;
        }

        /* ONGs Grid */
        .ongs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.8rem;
        }

        .ong-card {
            background: var(--color-white);
            border-radius: var(--card-border-radius);
            padding: var(--card-padding);
            box-shadow: var(--box-shadow);
            transition: all 0.4s #e4e4e4;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .ong-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--color-primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .ong-card:hover::before {
            transform: scaleX(1);
        }

        .ong-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .ong-logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--color-light);
            background: var(--color-light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .ong-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .ong-logo .placeholder {
            font-size: 2rem;
            color: var(--color-dark-variant);
        }

        .ong-title {
            flex: 1;
        }

        .ong-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: var(--color-dark);
            transition: all 0.3s ease;
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.aprovado {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .status-badge.pendente {
            background: rgba(255, 152, 0, 0.1);
            color: #FF9800;
            border: 1px solid rgba(255, 152, 0, 0.3);
        }

        .status-badge.rejeitado {
            background: rgba(244, 67, 54, 0.1);
            color: #F44336;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        .ong-info {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
            flex: 1;
        }

        .ong-info-item {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            color: var(--color-dark-variant);
            transition: all 0.3s ease;
        }

        .ong-card:hover .ong-info-item {
            transform: translateX(5px);
        }

        .ong-info-item i {
            font-size: 1.2rem;
            margin-right: 0.8rem;
            color: var(--color-primary);
            transition: all 0.3s ease;
        }

        .ong-card:hover .ong-info-item i {
            transform: scale(1.2);
        }

        .view-details {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem;
            background: var(--color-light);
            border-radius: var(--border-radius-1);
            color: var(--color-dark);
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: auto;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .view-details::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: var(--color-primary);
            transition: all 0.3s ease;
            z-index: -1;
        }

        .view-details:hover {
            color: white;
        }

        .view-details:hover::before {
            width: 100%;
        }

        .view-details i {
            margin-left: 0.5rem;
            transition: transform 0.3s ease;
        }

        .view-details:hover i {
            transform: translateX(5px);
        }

        /* Modal personalizado */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .modal-overlay.active {
            display: block;
            opacity: 1;
        }

        .modal-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            background: var(--color-white);
            border-radius: var(--card-border-radius);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            z-index: 1001;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .modal-overlay.active .modal-container {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .modal-header {
            background: var(--color-primary);
            color: white;
            padding: 1.5rem;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-close i {
            color: white;
            font-size: 1.2rem;
        }

        .modal-body {
            padding: 2rem;
            overflow-y: auto;
            max-height: calc(90vh - 130px);
        }

        .modal-footer {
            padding: 1.5rem;
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid var(--color-light);
        }

        .modal-btn {
            padding: 0.8rem 1.5rem;
            background: var(--color-dark-variant);
            color: white;
            border: none;
            border-radius: var(--border-radius-1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-btn:hover {
            background: var(--color-dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Conteúdo do modal */
        .ong-detail-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--color-light);
        }

        .ong-detail-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1.5rem;
            border: 4px solid var(--color-primary);
            background: var(--color-light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .ong-detail-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ong-detail-logo .placeholder {
            font-size: 2.5rem;
            color: var(--color-dark-variant);
        }

        .ong-detail-info h3 {
            font-size: 1.8rem;
            margin-bottom: 0.8rem;
            color: var(--color-dark);
        }

        .ong-detail-info .status-badge {
            margin-bottom: 0.8rem;
        }

        .ong-detail-contact {
            margin-bottom: 2rem;
        }

        .ong-detail-contact h4 {
            font-size: 1.3rem;
            margin-bottom: 1.2rem;
            color: var(--color-dark);
            position: relative;
            padding-bottom: 0.8rem;
        }

        .ong-detail-contact h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--color-primary);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: var(--color-light);
            border-radius: var(--border-radius-1);
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .contact-item i {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: var(--color-primary);
        }

        .contact-item span {
            font-size: 0.95rem;
            color: var(--color-dark-variant);
        }

        .ong-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: var(--color-light);
            border-radius: var(--border-radius-2);
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--color-primary), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover::before {
            opacity: 0.1;
        }

        .stat-card i {
            font-size: 2.5rem;
            color: var(--color-primary);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover i {
            transform: scale(1.2);
        }

        .stat-card h5 {
            font-size: 1rem;
            color: var(--color-dark-variant);
            margin-bottom: 0.8rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-dark);
        }

        .section-title {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            color: var(--color-dark);
            position: relative;
            padding-bottom: 0.8rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--color-primary);
        }

        /* Estilos para o grid de campanhas */
        .campaigns-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        @media screen and (max-width: 768px) {
            .campaigns-list {
                grid-template-columns: 1fr;
            }
        }

        .campaign-card {
            background: var(--color-light);
            border-radius: var(--border-radius-2);
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .campaign-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .campaign-image {
            height: 180px;
            overflow: hidden;
            position: relative;
        }

        .campaign-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), transparent);
        }

        .campaign-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .campaign-card:hover .campaign-image img {
            transform: scale(1.1);
        }

        .campaign-content {
            padding: 1.5rem;
        }

        .campaign-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: var(--color-dark);
        }

        .campaign-date {
            font-size: 0.85rem;
            color: var(--color-dark-variant);
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
        }

        .campaign-date i {
            font-size: 1rem;
            margin-right: 0.5rem;
            color: var(--color-primary);
        }

        .campaign-excerpt {
            font-size: 0.95rem;
            color: var(--color-dark-variant);
            margin-bottom: 0.8rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: var(--color-light);
            border-radius: var(--border-radius-2);
            margin-bottom: 2.5rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--color-dark-variant);
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1.1rem;
            color: var(--color-dark-variant);
            max-width: 500px;
            margin: 0 auto;
        }

        /* Animações */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ong-card {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }

        /* Responsividade */
        @media screen and (max-width: 1200px) {
            .ongs-container {
                padding: 1.5rem;
            }
        }

        @media screen and (max-width: 768px) {
            .ongs-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .ongs-grid {
                grid-template-columns: 1fr;
            }

            .ong-detail-header {
                flex-direction: column;
                text-align: center;
            }

            .ong-detail-logo {
                margin-right: 0;
                margin-bottom: 1.5rem;
            }

            .ong-detail-contact h4::after,
            .section-title::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .contact-grid, .ong-stats, .campaigns-list {
                grid-template-columns: 1fr;
            }

            .modal-container {
                width: 95%;
                height: 95vh;
                max-height: 95vh;
            }

            .modal-body {
                max-height: calc(95vh - 130px);
            }
        }

        /* Dark mode */
        .dark-mode .ong-card,
        .dark-mode .modal-container,
        .dark-mode .filters-container {
            background-color: var(--color-dark-variant);
            color: var(--color-white);
        }

        .dark-mode .ong-name,
        .dark-mode .ong-detail-info h3,
        .dark-mode .stat-card .stat-value,
        .dark-mode .campaign-title,
        .dark-mode .section-title,
        .dark-mode .ong-detail-contact h4,
        .dark-mode .filters-title {
            color: var(--color-white);
        }

        .dark-mode .ong-info-item,
        .dark-mode .contact-item span,
        .dark-mode .campaign-excerpt,
        .dark-mode .campaign-date,
        .dark-mode .filter-label {
            color: var(--color-light);
        }

        .dark-mode .stat-card,
        .dark-mode .campaign-card,
        .dark-mode .empty-state,
        .dark-mode .contact-item {
            background-color: var(--color-dark);
        }

        .dark-mode .back-button,
        .dark-mode .reset-filters {
            background-color: var(--color-dark);
            color: var(--color-light);
        }

        .dark-mode .modal-btn {
            background-color: var(--color-dark);
        }

        .dark-mode .modal-btn:hover {
            background-color: var(--color-dark-variant);
        }

        .dark-mode .filter-select,
        .dark-mode .filter-input {
            background-color: var(--color-dark);
            color: var(--color-white);
            border-color: var(--color-dark-variant);
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        @include('componentes.menuAdm')

        <!-- Main Content -->
        <main>
            <div class="ongs-container">
                <div class="page-header">
                    <h1>
                        <i class="material-icons-sharp">groups</i>
                        Todas as ONGs
                    </h1>
                    <a href="{{ route('home') }}" class="back-button">
                        <i class="material-icons-sharp">arrow_back</i>
                        Voltar para Dashboard
                    </a>
                </div>

                <!-- Filtros -->
                <div class="filters-container">
                    <div class="filters-header">
                        <h3 class="filters-title">
                            <i class="material-icons-sharp">filter_alt</i>
                            Filtros
                        </h3>
                        <button class="reset-filters" id="resetFilters">
                            <i class="material-icons-sharp">restart_alt</i>
                            Limpar Filtros
                        </button>
                    </div>
                    
                    <form id="filterForm" method="GET" action="{{ route('ong.index') }}">
                        <div class="filters-grid">
                            <!-- Filtro por Status -->
                            <div class="filter-group">
                                <label for="status" class="filter-label">Status</label>
                                <select id="status" name="status" class="filter-select">
                                    <option value="">Todos os status</option>
                                    <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                    <option value="rejeitado" {{ request('status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por Data de Cadastro -->
                            <div class="filter-group">
                                <label for="date_from" class="filter-label">Data de Cadastro (De)</label>
                                <input type="date" id="date_from" name="date_from" class="filter-input" value="{{ request('date_from') }}">
                            </div>
                            
                            <div class="filter-group">
                                <label for="date_to" class="filter-label">Data de Cadastro (Até)</label>
                                <input type="date" id="date_to" name="date_to" class="filter-input" value="{{ request('date_to') }}">
                            </div>
                            
                            <!-- Filtro por Nome/CNPJ -->
                            <div class="filter-group">
                                <label for="search" class="filter-label">Buscar por Nome/CNPJ</label>
                                <input type="text" id="search" name="search" class="filter-input" placeholder="Digite nome ou CNPJ" value="{{ request('search') }}">
                            </div>
                            
                            <!-- Filtro por Animais para Adoção -->
                            <div class="filter-group">
                                <label for="has_animals" class="filter-label">Possui animais para adoção</label>
                                <select id="has_animals" name="has_animals" class="filter-select">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('has_animals') == '1' ? 'selected' : '' }}>Sim</option>
                                    <option value="0" {{ request('has_animals') == '0' ? 'selected' : '' }}>Não</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por Campanhas Ativas -->
                            <div class="filter-group">
                                <label for="has_campaigns" class="filter-label">Possui campanhas ativas</label>
                                <select id="has_campaigns" name="has_campaigns" class="filter-select">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('has_campaigns') == '1' ? 'selected' : '' }}>Sim</option>
                                    <option value="0" {{ request('has_campaigns') == '0' ? 'selected' : '' }}>Não</option>
                                </select>
                            </div>
                            
                            <div class="apply-filters">
                                <button type="submit" class="apply-filters-btn">
                                    <i class="material-icons-sharp">search</i>
                                    Aplicar Filtros
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Contador de resultados -->
                <div class="results-count">
                    <i class="material-icons-sharp">filter_list</i>
                    Exibindo <strong>{{ $ongs->count() }}</strong> de <strong>{{ $totalOngs }}</strong> ONGs encontradas
                </div>

                @if(isset($ongs) && $ongs->isNotEmpty())
                <div class="ongs-grid">
                    @foreach($ongs as $index => $ong)
                    <div class="ong-card" style="animation-delay: {{ $index * 0.1 }}s" onclick="openModal('ongModal{{ $ong->idOng }}')">
                        <div class="ong-header">
                            <div class="ong-logo">
                                @if(isset($ong->fotoOng) && !empty($ong->fotoOng))
                                <img src="{{ $ong->fotoOng }}" alt="{{ $ong->nomeOng }}">
                                @else
                                <span class="material-icons-sharp placeholder">business</span>
                                @endif
                            </div>
                            <div class="ong-title">
                                <h3 class="ong-name">{{ $ong->nomeOng ?? 'Nome não disponível' }}</h3>
                                <span class="status-badge {{ $ong->status ?? 'pendente' }}">
                                    {{ ucfirst($ong->status ?? 'pendente') }}
                                </span>
                            </div>
                        </div>
                        <div class="ong-info">
                            <div class="ong-info-item">
                                <i class="material-icons-sharp">email</i>
                                <span>{{ $ong->emailOng ?? 'Email não disponível' }}</span>
                            </div>
                            <div class="ong-info-item">
                                <i class="material-icons-sharp">phone</i>
                                <span>{{ $ong->telOng ?? 'Telefone não disponível' }}</span>
                            </div>
                            @if(isset($ong->lograOng))
                            <div class="ong-info-item">
                                <i class="material-icons-sharp">location_on</i>
                                <span>{{ $ong->lograOng }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="view-details">
                            Ver detalhes
                            <i class="material-icons-sharp">arrow_forward</i>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="material-icons-sharp">info</i>
                    <p>Nenhuma ONG encontrada com os filtros aplicados</p>
                </div>
                @endif
            </div>

            <!-- Modais para cada ONG -->
            @if(isset($ongs) && $ongs->isNotEmpty())
                @foreach($ongs as $ong)
                <div id="ongModal{{ $ong->idOng }}" class="modal-overlay">
                    <div class="modal-container">
                        <div class="modal-header">
                            <div class="modal-title">Detalhes da ONG</div>
                            <button class="modal-close" onclick="closeModal('ongModal{{ $ong->idOng }}')">
                                <i class="material-icons-sharp">close</i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="ong-detail-header">
                                <div class="ong-detail-logo">
                                    @if(isset($ong->fotoOng) && !empty($ong->fotoOng))
                                    <img src="{{ $ong->fotoOng }}" alt="{{ $ong->nomeOng }}">
                                    @else
                                    <span class="material-icons-sharp placeholder">business</span>
                                    @endif
                                </div>
                                <div class="ong-detail-info">
                                    <h3>{{ $ong->nomeOng ?? 'Nome não disponível' }}</h3>
                                    <span class="status-badge {{ $ong->status ?? 'pendente' }}">
                                        {{ ucfirst($ong->status ?? 'pendente') }}
                                    </span>
                                    @if(isset($ong->cnpjOng))
                                    <p>CNPJ: {{ $ong->cnpjOng }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="ong-detail-contact">
                                <h4>Informações de Contato</h4>
                                <div class="contact-grid">
                                    <div class="contact-item">
                                        <i class="material-icons-sharp">email</i>
                                        <span>{{ $ong->emailOng ?? 'Email não disponível' }}</span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="material-icons-sharp">phone</i>
                                        <span>{{ $ong->telOng ?? 'Telefone não disponível' }}</span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="material-icons-sharp">location_on</i>
                                        <span>{{ $ong->lograOng ?? 'Endereço não disponível' }}</span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="material-icons-sharp">markunread_mailbox</i>
                                        <span>CEP: {{ $ong->cepOng ?? 'CEP não disponível' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="ong-stats">
                                <div class="stat-card">
                                    <i class="material-icons-sharp">pets</i>
                                    <h5>Animais para Adoção</h5>
                                    <div class="stat-value">{{ $ong->animaisCount ?? 0 }}</div>
                                </div>
                                <div class="stat-card">
                                    <i class="material-icons-sharp">campaign</i>
                                    <h5>Campanhas</h5>
                                    <div class="stat-value">{{ $ong->postsCount ?? 0 }}</div>
                                </div>
                                <div class="stat-card">
                                    <i class="material-icons-sharp">calendar_today</i>
                                    <h5>Desde</h5>
                                    <div class="stat-value">{{ isset($ong->created_at) ? date('d/m/Y', strtotime($ong->created_at)) : 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- Seção de Campanhas -->
                            <h4 class="section-title">Campanhas Recentes</h4>
                            @if($ong->posts->isNotEmpty())
                            <div class="campaigns-list">
                                @foreach($ong->posts as $post)
                                <div class="campaign-card">
                                    <div class="campaign-image">
                                        @if(isset($post->image) && !empty($post->image))
                                        <img src="{{ $post->image }}" alt="{{ $post->title }}">
                                        @else
                                        <img src="{{ asset('images/campaign-placeholder.jpg') }}" alt="Imagem padrão">
                                        @endif
                                    </div>
                                    <div class="campaign-content">
                                        <h5 class="campaign-title">{{ $post->title ?? 'Sem título' }}</h5>
                                        <div class="campaign-date">
                                            <i class="material-icons-sharp">event</i>
                                            {{ isset($post->created_at) ? date('d/m/Y', strtotime($post->created_at)) : 'Data não disponível' }}
                                        </div>
                                        <p class="campaign-excerpt">{{ $post->description ?? 'Sem descrição' }}</p>

                                     <div class="campaign-interactions" style="display: flex; gap: 1rem; margin-top: 1rem; font-size: 0.9rem; color: var(--color-dark-variant);">
                                         <span style="display: flex; align-items: center;">
                                             <i class="material-icons-sharp" style="font-size: 1.1rem; margin-right: 0.3rem; color: var(--color-danger);">favorite</i>
                                             {{ $post->likesCount ?? 0 }} Curtidas
                                         </span>
                                         <span style="display: flex; align-items: center;">
                                             <i class="material-icons-sharp" style="font-size: 1.1rem; margin-right: 0.3rem; color: var(--color-info);">chat_bubble</i>
                                             {{ $post->comentariosCount ?? 0 }} Comentários
                                         </span>
                                     </div>
                                     </div>
                                     </div>
                                     @endforeach
                                     </div>
                            @else
                            <div class="empty-state">
                                <i class="material-icons-sharp">campaign</i>
                                <p>Esta ONG ainda não possui campanhas publicadas</p>
                            </div>
                            @endif

                            <!-- Seção de Animais para Adoção -->
                            <h4 class="section-title">Animais para Adoção</h4>
                            @if(isset($ong->animaisCount) && $ong->animaisCount > 0)
                            <div class="stat-card" style="width: 100%; max-width: 500px; margin: 0 auto;">
                                <i class="material-icons-sharp">pets</i>
                                <h5>Total de Animais</h5>
                                <div class="stat-value">{{ $ong->animaisCount }}</div>
                                <p style="margin-top: 1rem;">Visite a página da ONG para ver todos os animais disponíveis para adoção</p>
                            </div>
                            @else
                            <div class="empty-state">
                                <i class="material-icons-sharp">pets</i>
                                <p>Esta ONG ainda não cadastrou animais para adoção</p>
                            </div>
                            @endif
                        </div>
                    
                    </div>
                </div>
                @endforeach
            @endif
        </main>

        <!-- Right Header -->
        @include('componentes.headerAdm')
    </div>

    <script>
        // Função para abrir modal
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Função para fechar modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Fechar modal ao clicar fora dele
        document.addEventListener('click', function(event) {
            const modals = document.querySelectorAll('.modal-overlay');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // Animação de entrada para os cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.ong-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                }, index * 100);
            });
        });

        // Resetar filtros
        document.getElementById('resetFilters').addEventListener('click', function() {
            const form = document.getElementById('filterForm');
            const inputs = form.querySelectorAll('input, select');
            
            inputs.forEach(input => {
                if (input.type === 'text' || input.type === 'date') {
                    input.value = '';
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });
            
            form.submit();
        });

        // Validar datas (data final não pode ser anterior à data inicial)
        document.getElementById('date_to').addEventListener('change', function() {
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = this.value;
            
            if (dateFrom && dateTo && new Date(dateTo) < new Date(dateFrom)) {
                alert('A data final não pode ser anterior à data inicial');
                this.value = '';
            }
        });
    </script>
    <script src="{{ asset('js/dark.js') }}"></script>
</body>

</html>