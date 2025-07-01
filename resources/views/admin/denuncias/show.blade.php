<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Denúncia - BuscaPet</title>
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
                            <div class="title-container">
                                <h1>
                                    <i class="material-icons-sharp">report_problem</i>
                                    Denúncia #{{ $denuncia->idDenuncia }}
                                </h1>
                                <div class="status-badge status-{{ $denuncia->statusDenuncia }}">
                                    {{ ucfirst($denuncia->statusDenuncia) }}
                                </div>
                                <div class="resolucao-info">
                                    <span class="material-icons-sharp">
                                        {{ $denuncia->comentario->visivel == 0 ? 'check_circle' : 'cancel' }}
                                    </span>
                                    <h3>
                                        Esta denúncia foi {{ $denuncia->comentario->visivel == 0 ? 'aceita' : 'rejeitada' }}
                                    </h3>
                                </div>
                            </div>
                            <div class="page-actions">
                                <a href="{{ route('admin.denuncias.index') }}" class="action-btn btn-voltar">
                                    <span class="material-icons-sharp">arrow_back</span>
                                    Voltar para Lista
                                </a>
                            </div>
                        </div>

                        @if(session('success'))
                        <div class="alert-success">
                            <span class="material-icons-sharp">check_circle</span>
                            <p>{{ session('success') }}</p>
                        </div>
                        @endif

                        <div class="denuncia-meta">
                            <div class="meta-item">
                                <span class="material-icons-sharp">calendar_today</span>
                                <div>
                                    <h4>Data da Denúncia</h4>
                                    <p>{{ $denuncia->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            
                            <div class="meta-item">
                                <span class="material-icons-sharp">update</span>
                                <div>
                                    <h4>Última Atualização</h4>
                                    <p>{{ $denuncia->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="denuncia-content">
                            <div class="denuncia-section">
                                <div class="section-header">
                                    <span class="material-icons-sharp">warning</span>
                                    <h2>Motivo da Denúncia</h2>
                                </div>
                                <div class="section-content motivo-box">
                                    <p>{{ $denuncia->motivoDenuncia }}</p>
                                </div>
                            </div>

                            <div class="denuncia-section">
                                <div class="section-header">
                                    <span class="material-icons-sharp">comment</span>
                                    <h2>Comentário Denunciado</h2>
                                </div>
                                <div class="section-content comentario-box">
                                    @if($denuncia->comentario)
                                        <div class="comentario-header">
                                            <div class="user-info">
                                                @if($denuncia->comentario->user)
                                                    <div class="profile-photo">
                                                    <img src="{{ $denuncia->usuario->imgUser ? asset($denuncia->usuario->imgUser) : asset('img/imgUser/perfil.png') }}" alt="Foto de perfil">
                                                    </div>
                                                    <div class="user-details">
                                                        <h3>{{ $denuncia->comentario->user->nomeUser }}</h3>
                                                        <small class="text-muted">{{ $denuncia->comentario->created_at->format('d/m/Y H:i') }}</small>
                                                    </div>
                                                @else
                                                    <div class="user-details">
                                                        <h3>Usuário não disponível</h3>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="comentario-body">
                                            <p>{{ $denuncia->comentario->comment }}</p>
                                        </div>
                                        <div class="comentario-footer">
                                            @if($denuncia->comentario->post)
                                                <div class="post-info">
                                                    <span class="material-icons-sharp">article</span>
                                                    <p>Comentário em: <a href="{{ route('posts.show', $denuncia->comentario->post->id) }}" target="_blank">{{ $denuncia->comentario->post->title }}</a></p>
                                                </div>
                                            @else
                                                <div class="post-info">
                                                    <span class="material-icons-sharp">error_outline</span>
                                                    <p>Post não disponível</p>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            <span class="material-icons-sharp">error_outline</span>
                                            <p>Comentário não disponível</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="denuncia-section">
                                <div class="section-header">
                                    <span class="material-icons-sharp">person</span>
                                    <h2>Informações do Denunciante</h2>
                                </div>
                                <div class="section-content">
                                    @if($denuncia->usuario)
                                        <div class="denunciante-info">
                                            <div class="info-item">
                                                <span class="material-icons-sharp">badge</span>
                                                <div>
                                                    <h4>Nome</h4>
                                                    <p>{{ $denuncia->usuario->nomeUser }}</p>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <span class="material-icons-sharp">email</span>
                                                <div>
                                                    <h4>Email</h4>
                                                    <p>{{ $denuncia->usuario->emailUser }}</p>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <span class="material-icons-sharp">phone</span>
                                                <div>
                                                    <h4>Telefone</h4>
                                                    <p>{{ $denuncia->usuario->telUser ?? 'Não informado' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            <span class="material-icons-sharp">error_outline</span>
                                            <p>Informações do denunciante não disponíveis</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

        
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Confirmação de Aprovação -->
    <div id="aprovarModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmar Aprovação</h3>
                <span class="material-icons-sharp close-modal" onclick="closeModal('aprovarModal')">close</span>
            </div>
            <div class="modal-body">
                <div class="modal-icon warning">
                    <span class="material-icons-sharp">warning</span>
                </div>
                <p>Tem certeza que deseja aprovar esta denúncia?</p>
                <p class="modal-details">O comentário será ocultado e a denúncia será marcada como resolvida.</p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn btn-cancel" onclick="closeModal('aprovarModal')">Cancelar</button>
                <form action="{{ route('admin.denuncias.aprovar', $denuncia->idDenuncia) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="modal-btn btn-confirm">Confirmar</button>
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
                <form action="{{ route('admin.denuncias.rejeitar', $denuncia->idDenuncia) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="modal-btn btn-confirm">Confirmar</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Estilos específicos para a página de detalhes da denúncia */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--color-info-light);
        }

        .title-container {
            display: flex;
            align-items: center;
            gap: 1rem;
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

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 1rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pendente {
            background-color: rgba(255, 187, 85, 0.1);
            color: var(--color-warning);
            border: 1px solid var(--color-warning);
        }

        .status-analise {
            background-color: rgba(33, 150, 243, 0.1);
            color: #2196F3;
            border: 1px solid #2196F3;
        }

        .status-resolvida {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--color-success);
            border: 1px solid var(--color-success);
        }

        .denuncia-meta {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
            background-color: var(--color-white);
            padding: 1rem;
            border-radius: var(--border-radius-1);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .meta-item span {
            color: var(--color-primary);
            font-size: 1.5rem;
        }

        .meta-item h4 {
            margin: 0 0 0.3rem 0;
            color: var(--color-dark-variant);
            font-size: 0.8rem;
        }

        .meta-item p {
            margin: 0;
            color: var(--color-dark);
            font-weight: 500;
        }

        .denuncia-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .denuncia-section {
            background-color: var(--color-white);
            border-radius: var(--border-radius-1);
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            transition: all 300ms ease;
        }

        .denuncia-section:hover {
            box-shadow: none;
            transform: translateY(-2px);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background-color: rgba(225, 184, 130, 0.1);
            border-bottom: 1px solid var(--color-info-light);
        }

        .section-header h2 {
            margin: 0;
            color: var(--color-primary);
            font-size: 1.2rem;
        }

        .section-header span {
            color: var(--color-primary);
        }

        .section-content {
            padding: 1.5rem;
        }

        .motivo-box {
            background-color: rgba(225, 184, 130, 0.05);
            border-radius: var(--border-radius-1);
            padding: 1rem;
            border-left: 4px solid var(--color-primary);
        }

        .motivo-box p {
            margin: 0;
            color: var(--color-dark);
            font-weight: 500;
        }

        .comentario-box {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .comentario-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-details h3 {
            margin: 0;
            color: var(--color-dark);
            font-weight: 600;
        }

        .comentario-body {
            background-color: rgba(225, 184, 130, 0.05);
            padding: 1rem;
            border-radius: var(--border-radius-1);
        }

        .comentario-body p {
            margin: 0;
            color: var(--color-dark);
        }

        .comentario-footer {
            font-size: 0.9rem;
            color: var(--color-dark-variant);
        }

        .post-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .post-info p {
            margin: 0;
        }

        .comentario-footer a {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .comentario-footer a:hover {
            text-decoration: underline;
        }

        .denunciante-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
        }

        .info-item span {
            color: var(--color-primary);
            font-size: 1.5rem;
        }

        .info-item h4 {
            margin: 0 0 0.3rem 0;
            color: var(--color-dark-variant);
        }

        .info-item p {
            margin: 0;
            color: var(--color-dark);
            font-weight: 500;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: var(--color-dark-variant);
            text-align: center;
        }

        .empty-state span {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .denuncia-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius-1);
            font-weight: 600;
            cursor: pointer;
            transition: all 300ms ease;
            border: none;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
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
        }

        .btn-rejeitar {
            background-color: var(--color-danger);
            color: white;
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
            background-color: var(--color-white);
            border-radius: var(--card-border-radius);
            width: 90%;
            max-width: 500px;
            box-shadow: var(--box-shadow);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

            .title-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .page-actions {
                width: 100%;
            }

            .denuncia-meta {
                flex-direction: column;
                gap: 1rem;
            }

            .denunciante-info {
                grid-template-columns: 1fr;
            }

            .denuncia-actions {
                flex-direction: column;
                width: 100%;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
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
        // Funções para controle dos modais
        function openAprovarModal() {
            document.getElementById('aprovarModal').style.display = 'flex';
        }

        function openRejeitarModal() {
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
