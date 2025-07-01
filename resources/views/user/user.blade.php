<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor ADM</title>
    <link rel="shortcut icon" href="{{ asset('img/site/logo 2.png') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/paginacao.css') }}">
    <style>
        :root {
            --color-primary: #e1b882;
            --color-primary-light: #f0d9b8;
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
            --card-border-radius: 16px;
            --border-radius-1: 8px;
            --border-radius-2: 12px;
            --border-radius-3: 16px;

            --card-padding: 2rem;
            --padding-1: 1.2rem;

            --box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            --box-shadow-hover: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .dark-mode {
            --color-background: #181a1e;
            --color-white: #202528;
            --color-dark: #edeffd;
            --color-dark-variant: #a3bdcc;
            --color-light: rgba(0, 0, 0, 0.4);
            --box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            --box-shadow-hover: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        /* Estilo do Card Principal */
        .card-table-container {
            margin: 2rem auto;
            max-width: 1200px;
        }

        .card-table {
            background-color: var(--color-white);
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
            padding: var(--card-padding);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-table:hover {
            box-shadow: var(--box-shadow-hover);
            transform: translateY(-5px);
        }

        .card-table h1 {
            color: var(--color-dark);
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--color-primary-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-table h1 i {
            color: var(--color-primary);
        }

        /* Estilo da Tabela */
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1.5rem;
        }

        .table thead th {
            background-color: var(--color-primary);
            color: white;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
        }

        .table thead th:first-child {
            border-top-left-radius: var(--border-radius-1);
        }

        .table thead th:last-child {
            border-top-right-radius: var(--border-radius-1);
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(225, 184, 130, 0.1);
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--color-info-light);
            color: var(--color-dark);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Botões de Ação */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .iv,
        .ic,
        .ib,
        .iub {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .iv {
            color: var(--color-primary);
            background-color: rgba(225, 184, 130, 0.1);
        }

        .iv:hover {
            background-color: var(--color-primary);
            color: white;
            transform: scale(1.1);
        }

        .ic {
            color: var(--color-danger);
            background-color: rgba(255, 119, 130, 0.1);
        }

        .ic:hover {
            background-color: var(--color-danger);
            color: white;
            transform: scale(1.1);
        }

        .ib {
            color: var(--color-warning);
            background-color: rgba(255, 187, 85, 0.1);
        }

        .ib:hover {
            background-color: var(--color-warning);
            color: white;
            transform: scale(1.1);
        }

        .iub {
            color: var(--color-success);
            background-color: rgba(65, 241, 182, 0.1);
        }

        .iub:hover {
            background-color: var(--color-success);
            color: white;
            transform: scale(1.1);
        }

        /* Status do usuário */
        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.ativo {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .status-badge.bloqueado {
            background: rgba(244, 67, 54, 0.1);
            color: #F44336;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        /* Estilos para os modais */
        .view-modal,
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }

        .image-bordered {
            width: 400px;
            height: 205px;
            border: 2px solid lightgray;
            box-shadow: 0 2px 8px gray;
            border-radius: 8px;
            object-fit: contain;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .user-details-container {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .user-details {
            flex: 1;
            min-width: 200px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .view-modal-content,
        .modal-content {
            background-color: var(--color-white);
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .view-modal-content {
            min-height: 300px;
        }

        /* Modal de visualização */
        .view-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            background-color: var(--color-white);
            z-index: 1;
        }

        .view-modal-header h2 {
            margin: 0;
            color: var(--color-dark);
            font-size: 1.5rem;
        }

        .close-view {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-view:hover {
            color: #333;
        }

        .view-modal-body {
            max-height: 60vh;
            overflow-y: auto;
            padding-right: 10px;
        }

        .user-details span {
            display: inline-block;
            max-width: 100%;
            word-break: break-word;
            white-space: pre-wrap;
            color: var(--color-dark);
        }

        .user-details p {
            margin: 15px 0;
            font-size: 1rem;
            line-height: 1.5;
            word-break: break-word;
            color: var(--color-dark);
        }

        .user-details strong {
            color: var(--color-primary);
            min-width: 120px;
            display: inline-block;
            vertical-align: top;
        }

        /* Modal de confirmação */
        .modal-content h3 {
            margin-top: 0;
            color: var(--color-dark);
        }

        .modal-content p {
            margin: 20px 0;
            color: var(--color-dark);
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-buttons button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }

        .modal-buttons button:first-child {
            background-color: #e0e0e0;
            color: #333;
        }

        .modal-buttons button:first-child:hover {
            background-color: #d0d0d0;
        }

        .modal-buttons button:last-child {
            background-color: #d9534f;
            color: white;
        }

        .modal-buttons button:last-child:hover {
            background-color: #c9302c;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--color-primary);
            cursor: pointer;
            padding: 0;
            margin-left: 5px;
            font-size: 0.9rem;
        }

        .toggle-btn:hover {
            text-decoration: underline;
        }

        /* Paginação */
        .pagination-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 2rem;
            gap: 1.5rem;
            width: 100%;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination a,
        .pagination span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius-1);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination a.page-link {
            color: var(--color-dark);
            background-color: var(--color-white);
            border: 1px solid var(--color-info-light);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .pagination a.page-link:hover {
            background-color: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(225, 184, 130, 0.3);
        }

        .pagination span.active {
            background-color: var(--color-primary);
            color: white;
            border: 1px solid var(--color-primary);
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(225, 184, 130, 0.3);
        }

        .pagination span.disabled {
            color: var(--color-info-dark);
            background-color: var(--color-info-light);
            cursor: not-allowed;
            opacity: 0.7;
        }

        .pagination-info {
            color: var(--color-dark-variant);
            font-size: 0.9rem;
            text-align: center;
            background-color: rgba(225, 184, 130, 0.1);
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius-1);
        }

        /* Estilos para o modal de atividades do usuário */
        .user-activities-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow: auto;
        }

        .user-activities-content {
            background-color: var(--color-white);
            margin: 5% auto;
            padding: 2rem;
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
            width: 85%;
            max-width: 900px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .user-activities-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--color-primary-light);
        }

        .user-activities-header h2 {
            color: var(--color-dark);
            font-size: 1.8rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-activities-header h2 i {
            color: var(--color-primary);
        }

        .close-activities {
            color: var(--color-dark-variant);
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-activities:hover {
            color: var(--color-primary);
        }

        .activities-tabs {
            display: flex;
            border-bottom: 1px solid var(--color-info-light);
            margin-bottom: 1.5rem;
        }

        .tab-button {
            padding: 0.8rem 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            color: var(--color-dark-variant);
            position: relative;
            transition: all 0.3s;
        }

        .tab-button.active {
            color: var(--color-primary);
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--color-primary);
        }

        .tab-button:hover:not(.active) {
            color: var(--color-dark);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .activity-card {
            background-color: var(--color-white);
            border-radius: var(--border-radius-2);
            box-shadow: var(--box-shadow);
            padding: 1.2rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .activity-card:hover {
            box-shadow: var(--box-shadow-hover);
            transform: translateY(-3px);
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.8rem;
        }

        .activity-type {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .activity-type.reel {
            background: rgba(75, 192, 192, 0.1);
            color: #4bc0c0;
        }

        .activity-type.lost {
            background: rgba(255, 99, 132, 0.1);
            color: #ff6384;
        }

        .activity-type.comment {
            background: rgba(54, 162, 235, 0.1);
            color: #36a2eb;
        }

        .activity-date {
            color: var(--color-info-dark);
            font-size: 0.85rem;
        }

        .activity-body {
            margin-bottom: 0.8rem;
        }

        .activity-footer {
            display: flex;
            justify-content: flex-end;
        }

        .see-all-btn {
            background-color: var(--color-primary);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius-1);
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .see-all-btn:hover {
            background-color: var(--color-primary-variant);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(225, 184, 130, 0.3);
        }

        /* Modal de todos os comentários */
        .comments-modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            overflow: auto;
        }

        .comments-content {
            background-color: var(--color-white);
            margin: 3% auto;
            padding: 2rem;
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
            width: 80%;
            max-width: 800px;
            max-height: 85vh;
            overflow-y: auto;
        }

        .comments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--color-primary-light);
        }

        .comments-header h3 {
            color: var(--color-dark);
            font-size: 1.5rem;
            margin: 0;
        }

        .close-comments {
            color: var(--color-dark-variant);
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-comments:hover {
            color: var(--color-primary);
        }

        .comment-item {
            padding: 1rem;
            border-bottom: 1px solid var(--color-info-light);
            margin-bottom: 1rem;
        }

        .comment-content {
            margin-bottom: 0.5rem;
            color: var(--color-dark);
        }

        .comment-meta {
            display: flex;
            justify-content: space-between;
            color: var(--color-info-dark);
            font-size: 0.85rem;
        }

        .comment-post {
            margin-top: 0.5rem;
            padding: 0.8rem;
            background-color: rgba(225, 184, 130, 0.1);
            border-radius: var(--border-radius-1);
            color: var(--color-dark);
        }

        .empty-message {
            text-align: center;
            padding: 2rem;
            color: var(--color-info-dark);
            font-style: italic;
        }

        .empty-message i {
            font-size: 2rem;
            color: var(--color-primary-light);
            margin-bottom: 1rem;
            display: block;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .card-table {
                padding: 1.5rem;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                border-radius: var(--border-radius-1);
                box-shadow: 0 2px 8px var(--color-light);
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.8rem 1rem;
                border-bottom: 1px solid var(--color-info-light);
            }

            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--color-primary);
                margin-right: 1rem;
            }

            .action-buttons {
                justify-content: flex-end;
            }

            .user-details-container {
                flex-direction: column;
            }

            .image-bordered {
                width: 100%;
                height: auto;
                max-height: 200px;
            }

            .view-modal-content,
            .modal-content {
                width: 90%;
                margin: 10% auto;
            }
        }

        @media (max-width: 480px) {

            .view-modal-content,
            .modal-content {
                width: 95%;
                padding: 15px;
            }

            .user-details p {
                font-size: 0.9rem;
            }

            .pagination a,
            .pagination span {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        @include('componentes.menuAdm')
        @include('componentes.headerAdm')

        <div class="content">
            <div class="card-table-container">
                <div class="card-table">
                    <h1>
                        <i class="material-icons-sharp">person</i>
                        Painel de Usuários
                    </h1>


                    <table class="table table-hover table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Celular</th>
                                <th>Status</th>
                                <th>Ações</th>
                                <th>Gerenciar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td data-label="ID">{{ $user->idUser }}</td>
                                <td data-label="Nome">{{ $user->nomeUser }}</td>
                                <td data-label="Email">{{ $user->emailUser }}</td>
                                <td data-label="Celular">
                                    <span class="phone-mask">{{ $user->telUser }}</span>
                                </td>
                                <td data-label="Status">
                                    @if($user->ativo)
                                    <span class="status-badge ativo">Ativo</span>
                                    @else
                                    <span class="status-badge bloqueado">Bloqueado</span>
                                    @endif
                                </td>
                                <td class="text-center action-buttons" data-label="Ações">
                                <a class="iv" onclick="showUserDetails('{{ $user->idUser }}', '{{ $user->nomeUser }}', '{{ $user->emailUser }}', '{{ $user->telUser }}', '{{ $user->ativo ? 'Ativo' : 'Bloqueado' }}')">
                                    <i class="material-icons-sharp">search</i>
                                </a>
                                    <a class="iv" style="background-color: rgba(54, 162, 235, 0.1); color: #36a2eb;" onclick="openActivitiesModal('{{ $user->idUser }}')">
                                        <i class="material-icons-sharp">history</i>
                                    </a>
                                </td>
                                <td class="text-center" data-label="Gerenciar">
                                    @if($user->ativo)
                                    <form id="blockForm-{{ $user->idUser }}" action="{{ route('user.block', $user->idUser) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="ib" onclick="openBlockModal('{{ $user->idUser }}')">
                                            <i class="material-icons-sharp">block</i>
                                        </button>
                                    </form>
                                    @else
                                    <form id="unblockForm-{{ $user->idUser }}" action="{{ route('user.unblock', $user->idUser) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="iub" onclick="openUnblockModal('{{ $user->idUser }}')">
                                            <i class="material-icons-sharp">check_circle</i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="pagination-container">
                        <div class="pagination">
                            @if ($users->onFirstPage())
                            <span class="disabled"><i class="material-icons-sharp">chevron_left</i></span>
                            @else
                            <a href="{{ $users->previousPageUrl() }}" class="page-link"><i class="material-icons-sharp">chevron_left</i></a>
                            @endif

                            @for ($i = 1; $i <= $users->lastPage(); $i++)
                                @if ($i == $users->currentPage())
                                <span class="active">{{ $i }}</span>
                                @else
                                <a href="{{ $users->url($i) }}" class="page-link">{{ $i }}</a>
                                @endif
                                @endfor

                                @if ($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="page-link"><i class="material-icons-sharp">chevron_right</i></a>
                                @else
                                <span class="disabled"><i class="material-icons-sharp">chevron_right</i></span>
                                @endif
                        </div>
                        <div class="pagination-info">
                            Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} registros
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Visualização de Detalhes do Usuário -->
    <div id="viewModal" class="view-modal">
        <div class="view-modal-content">
            <div class="view-modal-header">
                <h2>Detalhes do Usuário</h2>
                <span class="close-view" onclick="closeViewModal()">&times;</span>
            </div>
            <div class="view-modal-body">
                <div class="user-details-container">
                    <div class="user-details">
                        <p><strong>ID:</strong> <span id="userId"></span></p>
                        <p><strong>Nome:</strong> <span id="userName"></span></p>
                        <p><strong>Email:</strong> <span id="userEmail"></span></p>
                        <p><strong>Telefone:</strong> <span id="phone-mask"></span></p>
                        <p><strong>Status:</strong> <span id="userStatus"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Bloqueio -->
    <div id="blockModal" class="modal">
        <div class="modal-content">
            <h3>Confirmar Bloqueio</h3>
            <p>Tem certeza que deseja bloquear este usuário? O usuário não poderá mais acessar o sistema até ser desbloqueado.</p>
            <div class="modal-buttons">
                <button onclick="closeBlockModal()">Cancelar</button>
                <button onclick="confirmBlock()">Bloquear</button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Desbloqueio -->
    <div id="unblockModal" class="modal">
        <div class="modal-content">
            <h3>Confirmar Desbloqueio</h3>
            <p>Tem certeza que deseja desbloquear este usuário? O usuário poderá voltar a acessar o sistema normalmente.</p>
            <div class="modal-buttons">
                <button onclick="closeUnblockModal()">Cancelar</button>
                <button onclick="confirmUnblock()">Desbloquear</button>
            </div>
        </div>
    </div>

    <!-- Modal de Atividades do Usuário -->
    <div id="userActivitiesModal" class="user-activities-modal">
        <div class="user-activities-content">
            <div class="user-activities-header">
                <h2>
                    <i class="material-icons-sharp">history</i>
                    Atividades do Usuário
                </h2>
                <span class="close-activities" onclick="closeActivitiesModal()">&times;</span>
            </div>
            <div class="activities-tabs">
                <button class="tab-button active" onclick="openTab(event, 'reelsTab')">PetToks</button>
                <button class="tab-button" onclick="openTab(event, 'commentsTab')">Comentários</button>
                <button class="tab-button" onclick="openTab(event, 'likesTab')">Curtidas</button>
                <button class="tab-button" onclick="openTab(event, 'reportsTab')">Denúncias</button>
            </div>

            <div id="reelsTab" class="tab-content active">
                <div id="reelsContent">
                    <div class="empty-message">
                        <i class="material-icons-sharp">movie</i>
                        Carregando reels...
                    </div>
                </div>
            </div>

            <div id="commentsTab" class="tab-content">
                <div id="commentsContent">
                    <div class="empty-message">
                        <i class="material-icons-sharp">comment</i>
                        Carregando comentários...
                    </div>
                </div>
            </div>

            <div id="likesTab" class="tab-content">
                <div id="likesContent">
                    <div class="empty-message">
                        <i class="material-icons-sharp">favorite</i>
                        Carregando curtidas...
                    </div>
                </div>
            </div>

            <div id="reportsTab" class="tab-content">
                <div id="reportsContent">
                    <div class="empty-message">
                        <i class="material-icons-sharp">report</i>
                        Carregando denúncias...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variáveis globais
        let currentUserId = null;
        let currentActivitiesUserId = null;

        // Funções para o modal de visualização
        function showUserDetails(id, name, email, phone, status) {
        document.getElementById('userId').textContent = id;
        document.getElementById('userName').textContent = name;
        document.getElementById('userEmail').textContent = email;
        document.getElementById('phone-mask').textContent = formatPhoneNumber(phone);
        document.getElementById('userStatus').textContent = status;
        document.getElementById('viewModal').style.display = 'block';
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        // Funções para o modal de bloqueio
        function openBlockModal(userId) {
            currentUserId = userId;
            document.getElementById('blockModal').style.display = 'block';
        }

        function closeBlockModal() {
            document.getElementById('blockModal').style.display = 'none';
        }

        function confirmBlock() {
            if (currentUserId) {
                document.getElementById('blockForm-' + currentUserId).submit();
            }
        }

        // Funções para o modal de desbloqueio
        function openUnblockModal(userId) {
            currentUserId = userId;
            document.getElementById('unblockModal').style.display = 'block';
        }

        function closeUnblockModal() {
            document.getElementById('unblockModal').style.display = 'none';
        }

        function confirmUnblock() {
            if (currentUserId) {
                document.getElementById('unblockForm-' + currentUserId).submit();
            }
        }

        // Funções para o modal de atividades
        function openActivitiesModal(userId) {
            currentActivitiesUserId = userId;
            document.getElementById('userActivitiesModal').style.display = 'block';
            loadUserActivities(userId);
        }

        function closeActivitiesModal() {
            document.getElementById('userActivitiesModal').style.display = 'none';
            currentActivitiesUserId = null;
        }

        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }

            const tabButtons = document.getElementsByClassName('tab-button');
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }

            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }

        function loadUserActivities(userId) {
            fetch(`/admin/usuario/${userId}/atividades-json`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na resposta da rede');
                    }
                    return response.json();
                })
                .then(data => {
                    renderReels(data.reels);
                    renderComments(data.comentarios);
                    renderLikes(data.curtidas);
                    renderReports(data.denuncias);
                })
                .catch(error => {
                    console.error('Erro ao carregar atividades:', error);
                    document.getElementById('reelsContent').innerHTML = '<div class="empty-message"><i class="material-icons-sharp">error</i>Erro ao carregar reels.</div>';
                    document.getElementById('commentsContent').innerHTML = '<div class="empty-message"><i class="material-icons-sharp">error</i>Erro ao carregar comentários.</div>';
                    document.getElementById('likesContent').innerHTML = '<div class="empty-message"><i class="material-icons-sharp">error</i>Erro ao carregar curtidas.</div>';
                    document.getElementById('reportsContent').innerHTML = '<div class="empty-message"><i class="material-icons-sharp">error</i>Erro ao carregar denúncias.</div>';
                });
        }

        function renderReels(reels) {
            const container = document.getElementById('reelsContent');
            if (reels.length === 0) {
                container.innerHTML = '<div class="empty-message"><i class="material-icons-sharp">movie</i>Nenhum PetToks postado.</div>';
                return;
            }

            let html = '';
            reels.forEach(reel => {
                html += `
                <div class="activity-card">
                    <div class="activity-header">
                        <span class="activity-type reel">PetToks</span>
                        <span class="activity-date">${formatDate(reel.created_at)}</span>
                    </div>
                    <div class="activity-body">
                        <strong>Título:</strong> ${reel.tituloReels}
                    </div>
                </div>
            `;
            });
            container.innerHTML = html;
        }

        function renderComments(comments) {
            const container = document.getElementById('commentsContent');
            if (comments.length === 0) {
                container.innerHTML = '<div class="empty-message"><i class="material-icons-sharp">comment</i>Nenhum comentário realizado.</div>';
                return;
            }

            let html = '';
            comments.forEach(comment => {
                html += `
                <div class="activity-card">
                    <div class="activity-header">
                        <span class="activity-type comment">Comentário</span>
                        <span class="activity-date">${formatDate(comment.created_at)}</span>
                    </div>
                    <div class="activity-body">
                        <strong>Comentário:</strong> "${comment.comment}"
                    </div>
                </div>
            `;
            });
            container.innerHTML = html;
        }

        function renderLikes(likes) {
            const container = document.getElementById('likesContent');
            if (likes.length === 0) {
                container.innerHTML = '<div class="empty-message"><i class="material-icons-sharp">favorite</i>Nenhuma curtida registrada.</div>';
                return;
            }

            let html = '';
            likes.forEach(like => {
                html += `
            <div class="activity-card">
                <div class="activity-header">
                    <span class="activity-type">${like.tipo}</span>
                    <span class="activity-date">${formatDate(like.created_at)}</span>
                </div>
                <div class="activity-body">
                    <strong>${like.titulo}</strong>
                </div>
            </div>
        `;
            });
            container.innerHTML = html;
        }


        function renderReports(reports) {
            const container = document.getElementById('reportsContent');
            if (reports.length === 0) {
                container.innerHTML = '<div class="empty-message"><i class="material-icons-sharp">report</i>Nenhuma denúncia realizada.</div>';
                return;
            }

            let html = '';
            reports.forEach(report => {
                html += `
                <div class="activity-card">
                    <div class="activity-header">
                        <span class="activity-type lost">Denúncia</span>
                        <span class="activity-date">${formatDate(report.created_at)}</span>
                    </div>
                    <div class="activity-body">
                        <strong>Motivo:</strong> ${report.motivoDenuncia}
                    </div>
                </div>
            `;
            });
            container.innerHTML = html;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR');
        }



        // Única definição de window.onclick combinando todos os modais
        window.onclick = function(event) {
            if (event.target == document.getElementById('viewModal')) {
                closeViewModal();
            }
            if (event.target == document.getElementById('blockModal')) {
                closeBlockModal();
            }
            if (event.target == document.getElementById('unblockModal')) {
                closeUnblockModal();
            }
            if (event.target == document.getElementById('userActivitiesModal')) {
                closeActivitiesModal();
            }
        }

        function formatPhoneNumber(phone) {
    // Remove todos os caracteres não numéricos
    const cleaned = ('' + phone).replace(/\D/g, '');
    
    // Verifica se é um número de celular brasileiro (11 dígitos)
    if (cleaned.length === 11) {
        return cleaned.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    }
    // Verifica se é um número de telefone fixo brasileiro (10 dígitos)
    else if (cleaned.length === 10) {
        return cleaned.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    }
    // Se não corresponder aos padrões brasileiros, retorna o número original
    return phone;
}

// Aplica a máscara aos telefones quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    const phoneElements = document.querySelectorAll('.phone-mask');
    phoneElements.forEach(function(element) {
        const originalPhone = element.textContent.trim();
        element.textContent = formatPhoneNumber(originalPhone);
    });
});

    </script>
</body>

</html>