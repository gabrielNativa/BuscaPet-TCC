<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Denúncias - BuscaPet</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="container">
        @include('componentes.menuAdm')
        @include('componentes.headerAdm')

        <main>
            <div class="content">
                <div class="card-table-container">
                    <div class="card-table">
                        <div class="page-header">
                            <h1>
                                <i class="material-icons-sharp">report_problem</i>
                                Gerenciamento de Denúncias
                            </h1>
                        </div>

                        @if(session('success'))
                        <div class="alert-success">
                            <span class="material-icons-sharp">check_circle</span>
                            <p>{{ session('success') }}</p>
                        </div>
                        @endif

                        <!-- Filtros de status -->
                        <div class="status-filters">
                            <a href="{{ route('admin.denuncias.index', ['status' => 'analise']) }}" class="status-filter {{ $status == 'analise' ? 'active' : '' }}">
                                <span class="material-icons-sharp">pending</span>
                                Em Análise
                                <div class="count">{{ $counts['analise'] }}</div>
                            </a>
                            
                            <a href="{{ route('admin.denuncias.index', ['status' => 'resolvida']) }}" class="status-filter {{ $status == 'resolvida' ? 'active' : '' }}">
                                <span class="material-icons-sharp">check_circle</span>
                                Resolvidas
                                <div class="count">{{ $counts['resolvida'] }}</div>
                            </a>
                        </div>

                        <!-- Cards de denúncias -->
                        <div class="denuncias-grid">
                            @forelse($denuncias as $denuncia)
                            <div class="denuncia-card status-{{ $denuncia->statusDenuncia }}">
                                <div class="denuncia-header">
                                    <div class="denuncia-id">
                                        <span class="material-icons-sharp">report_problem</span>
                                        <h3>Denúncia #{{ $denuncia->idDenuncia }}</h3>
                                    </div>
                                    <div class="status-badge status-{{ $denuncia->statusDenuncia }}">
                                        {{ ucfirst($denuncia->statusDenuncia) }}
                                    </div>
                                </div>

                                <div class="denuncia-content">
                                    <div class="denuncia-info">
                                        <div class="info-item">
                                            <span class="material-icons-sharp">comment</span>
                                            <div>
                                                <h4>Comentário</h4>
                                                <p>{{ Str::limit($denuncia->comentario->comment ?? 'Comentário não disponível', 50) }}</p>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <span class="material-icons-sharp">person</span>
                                            <div>
                                                <h4>Autor do Comentário</h4>
                                                <p>{{ $denuncia->comentario->user->nomeUser ?? 'Usuário não disponível' }}</p>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <span class="material-icons-sharp">warning</span>
                                            <div>
                                                <h4>Motivo</h4>
                                                <p>{{ Str::limit($denuncia->motivoDenuncia, 100) }}</p>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <span class="material-icons-sharp">person_outline</span>
                                            <div>
                                                <h4>Denunciante</h4>
                                                <p>{{ $denuncia->usuario->nomeUser ?? 'Denunciante não disponível' }}</p>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <span class="material-icons-sharp">calendar_today</span>
                                            <div>
                                                <h4>Data</h4>
                                                <p>{{ $denuncia->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="denuncia-actions">
                                        <a href="{{ route('admin.denuncias.show', $denuncia->idDenuncia) }}" class="action-btn btn-view">
                                            <span class="material-icons-sharp">visibility</span>
                                            Ver Detalhes
                                        </a>


                                        @if($denuncia->statusDenuncia != 'resolvida')
<div class="action-group">
<button type="button" class="action-btn btn-aprovar" onclick="openAprovarModal({{ $denuncia->idDenuncia }})">
    <span class="material-icons-sharp">check_circle</span>
    Aprovar
</button>

    <form action="{{ route('admin.denuncias.rejeitar', $denuncia->idDenuncia) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="action-btn btn-rejeitar">
            <span class="material-icons-sharp">cancel</span>
            Rejeitar
        </button>
    </form>
</div>
@endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state">
                                <span class="material-icons-sharp">search_off</span>
                                <h3>Nenhuma denúncia {{ $status }} encontrada</h3>
                                <p>Quando houver denúncias com status "{{ $status }}", elas aparecerão aqui.</p>
                            </div>
                            @endforelse
                        </div>

                        <!-- Paginação -->
                        @if($denuncias->count() > 0)
                        <div class="pagination-container">
                            {{ $denuncias->appends(['status' => $status])->links() }}
                            <div class="pagination-info">
                                Mostrando {{ $denuncias->firstItem() ?? 0 }} a {{ $denuncias->lastItem() ?? 0 }} de {{ $denuncias->total() }} registros
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Confirmação de Bloqueio de Usuário -->
<div id="bloquearUsuarioModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Aprovação e Bloqueio</h3>
            <span class="material-icons-sharp close-modal" onclick="closeModal('bloquearUsuarioModal')">close</span>
        </div>
        <div class="modal-body">
            <div class="modal-icon warning">
                <span class="material-icons-sharp">warning</span>
            </div>
            <p>Tem certeza que deseja aprovar esta denúncia?</p>
            <p class="modal-details">Ao aprovar:</p>
            <ul class="modal-details-list">
                <li>O comentário será removido</li>
                <li>A denúncia será marcada como resolvida</li>
                <li>O usuário que fez o comentário será <strong>bloqueado automaticamente</strong></li>
            </ul>
        </div>
        <div class="modal-footer">
            <button class="modal-btn btn-cancel" onclick="closeModal('bloquearUsuarioModal')">Cancelar</button>
            <form id="aprovarForm" action="" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="modal-btn btn-confirm">Confirmar e Bloquear</button>
            </form>
        </div>
    </div>
</div>

    <!-- Modal de Confirmação de Rejeição -->
    <div id="rejeitarModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmar Rejeição</h3>
                <span class="material-icons-sharp close-modal" onclick="closeModal('rejeitarModal')">close</span>
            </div>
            <div class="modal-body">
                <div class="modal-icon info">
                    <span class="material-icons-sharp">info</span>
                </div>
                <p>Tem certeza que deseja rejeitar esta denúncia?</p>
                <p class="modal-details">O comentário será mantido e a denúncia será marcada como resolvida.</p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn btn-cancel" onclick="closeModal('rejeitarModal')">Cancelar</button>
                <form id="rejeitarForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="modal-btn btn-confirm">Confirmar</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--color-info-light);
        }

        .page-header h1 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
        }

        .alert-success {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--color-success);
            padding: 1rem;
            border-radius: var(--border-radius-1);
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--color-success);
        }

        .alert-success p {
            color: var(--color-success);
            margin: 0;
        }

        .status-filters {
            display: flex;
            margin-bottom: 2rem;
            border-radius: var(--border-radius-1);
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }

        .status-filter {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background-color: var(--color-white);
            color: var(--color-dark-variant);
            text-decoration: none;
            transition: all 300ms ease;
            border: 1px solid var(--color-info-light);
            position: relative;
            overflow: hidden;
        }

        .status-filter span {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .status-filter .count {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background-color: var(--color-info-light);
            color: var(--color-dark);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 1rem;
            min-width: 1.5rem;
            text-align: center;
        }

        .status-filter:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .status-filter.active {
            background-color: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
        }

        .status-filter.active .count {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .denuncias-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .denuncia-card {
            background-color: var(--color-white);
            border-radius: var(--border-radius-1);
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            transition: all 300ms ease;
            border-top: 4px solid var(--color-warning);
        }

        .denuncia-card.status-analise {
            border-top-color: #2196F3;
        }

        .denuncia-card.status-resolvida {
            border-top-color: var(--color-success);
        }

        .denuncia-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }

        .denuncia-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--color-info-light);
            background-color: rgba(225, 184, 130, 0.05);
        }

        .denuncia-id {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .denuncia-id h3 {
            margin: 0;
            font-size: 1rem;
            color: var(--color-dark);
        }

        .denuncia-id span {
            color: var(--color-primary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.8rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.status-pendente {
            background-color: rgba(255, 187, 85, 0.1);
            color: var(--color-warning);
            border: 1px solid var(--color-warning);
        }

        .status-badge.status-analise {
            background-color: rgba(33, 150, 243, 0.1);
            color: #2196F3;
            border: 1px solid #2196F3;
        }

        .status-badge.status-resolvida {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--color-success);
            border: 1px solid var(--color-success);
        }

        .denuncia-content {
            padding: 1rem;
        }

        .denuncia-info {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
        }

        .info-item span {
            color: var(--color-primary);
            font-size: 1.2rem;
        }

        .info-item h4 {
            margin: 0 0 0.3rem 0;
            color: var(--color-dark-variant);
            font-size: 0.8rem;
        }

        .info-item p {
            margin: 0;
            color: var(--color-dark);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .denuncia-actions {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .action-group {
            display: flex;
            gap: 0.8rem;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            border-radius: var(--border-radius-1);
            font-weight: 600;
            cursor: pointer;
            transition: all 300ms ease;
            border: none;
            font-size: 0.9rem;
            width: 100%;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.3rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .btn-view {
            background-color: var(--color-primary);
            color: white;
        }

        .btn-voltar {
            background-color: var(--color-info-light);
            color: var(--color-dark);
        }

        .btn-analise {
            background-color: #2196F3;
            color: white;
        }

        .btn-aprovar {
            background-color: var(--color-success);
            color: white;
            width: 175px;
        }

        .btn-rejeitar {
            background-color: var(--color-danger);
            color: white;
            width: 175px;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            text-align: center;
            background-color: var(--color-white);
            border-radius: var(--border-radius-1);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            grid-column: 1 / -1;
        }

        .empty-state span {
            font-size: 4rem;
            color: var(--color-info-light);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            margin: 0 0 0.5rem 0;
            color: var(--color-dark);
        }

        .empty-state p {
            color: var(--color-dark-variant);
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--color-info-light);
        }

        .pagination-info {
            color: var(--color-dark-variant);
            font-size: 0.9rem;
        }

    /* Estilo para o modal */
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
        background-color: white;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        animation: modalFadeIn 0.3s ease;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-details-list {
        text-align: left;
        padding-left: 1.5rem;
        margin: 0.5rem 0;
    }
    
    .modal-details-list li {
        margin-bottom: 0.3rem;
    }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid var(--color-info-light);
        }

        .modal-header h3 {
            margin: 0;
            color: var(--color-dark);
            font-size: 1.3rem;
        }

        .close-modal {
            cursor: pointer;
            color: var(--color-dark-variant);
            transition: all 300ms ease;
        }

        .close-modal:hover {
            color: var(--color-danger);
        }

        .modal-body {
            padding: 2rem;
            text-align: center;
        }

        .modal-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            margin-bottom: 1.5rem;
        }

        .modal-icon.warning {
            background-color: rgba(255, 187, 85, 0.1);
        }

        .modal-icon.warning span {
            color: var(--color-warning);
            font-size: 2rem;
        }

        .modal-icon.info {
            background-color: rgba(33, 150, 243, 0.1);
        }

        .modal-icon.info span {
            color: #2196F3;
            font-size: 2rem;
        }

        .modal-body p {
            margin: 0 0 0.5rem 0;
            color: var(--color-dark);
            font-size: 1.1rem;
        }

        .modal-details {
            color: var(--color-dark-variant) !important;
            font-size: 0.9rem !important;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding: 1.5rem;
            border-top: 1px solid var(--color-info-light);
        }

        .modal-btn {
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius-1);
            font-weight: 600;
            cursor: pointer;
            transition: all 300ms ease;
            border: none;
        }

        .btn-cancel {
            background-color: var(--color-info-light);
            color: var(--color-dark);
        }

        .btn-confirm {
            background-color: var(--color-primary);
            color: white;
        }

        /* Responsividade */
        @media screen and (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .page-actions {
                width: 100%;
            }

            .status-filters {
                flex-direction: column;
            }

            .denuncias-grid {
                grid-template-columns: 1fr;
            }

            .action-group {
                flex-direction: column;
            }

            .pagination-container {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .modal-content {
                width: 95%;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-btn {
                width: 100%;
            }
        }
    </style>

    <script>

     function openAprovarModal(id) {

        document.getElementById('aprovarForm').action = "{{ route('admin.denuncias.aprovar', 'ID') }}".replace('ID', id);
        document.getElementById('bloquearUsuarioModal').style.display = 'flex';
    }

    function openRejeitarModal(id) {
        document.getElementById('rejeitarForm').action = "{{ route('admin.denuncias.rejeitar', 'ID') }}".replace('ID', id);
        document.getElementById('rejeitarModal').style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Fechar modal ao clicar fora dele
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
    </script>
</body>

</html>