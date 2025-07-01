<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGs Pendentes de Aprovação</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilização dos botões */

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
        .ic {
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

        .approval-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .approval-table td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }

        .approval-table tr:last-child td {
            border-bottom: none;
        }

        .approval-table tr:hover {
            background-color: #f8f9fa;
        }

        /* Mensagem vazia */
        .empty-message {
            text-align: center;
            padding: 2rem;
            color: #7f8c8d;
            font-style: italic;
        }

        /* Botões de Ação */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-approve {
            background-color: #2ecc71;
            color: white;
        }

        .btn-approve:hover {
            background-color: #27ae60;
        }

        .btn-reject {
            background-color: #e74c3c;
            color: white;
        }

        .btn-reject:hover {
            background-color: #c0392b;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .rejection-modal {
            background-color: white;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .rejection-modal-header {
            padding: 1rem 1.5rem;
            background-color: #e74c3c;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rejection-modal-title {
            margin: 0;
            font-size: 1.2rem;
        }

        .btn-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
        }

        .rejection-modal-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .rejection-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        .rejection-textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .rejection-modal-footer {
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        /* Alertas */
        .alert {
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .approval-table {
                display: block;
                overflow-x: auto;
            }

            .action-buttons {
                flex-direction: column;
            }

            .approval-table td[data-label]::before {
                content: attr(data-label);
                font-weight: bold;
                display: inline-block;
                width: 120px;
            }
        }

        /* Estilos para o modal de rejeição */
        .rejection-modal {
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin: 2rem auto;
        }

        .rejection-modal-header {
            background: #e74c3c;
            color: white;
            padding: 1rem;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rejection-modal-title {
            margin: 0;
            font-size: 1.2rem;
        }

        .rejection-modal-body {
            padding: 1.5rem;
        }

        .rejection-textarea {
            width: 100%;
            min-height: 150px;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            font-family: inherit;
        }

        .rejection-textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.3);
        }

        .rejection-modal-footer {
            padding: 1rem;
            background: #f8f9fa;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        /* Validação */
        input:invalid,
        textarea:invalid {
            border-color: #e74c3c;
        }

        input:valid,
        textarea:valid {
            border-color: #2ecc71;
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

                        <i class="material-icons-sharp">groups</i>
                        ONGs Pendentes de Aprovação
                    </h1>

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <table class="table table-hover table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>CNPJ</th>
                                <th>E-mail</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ongs as $ong)
                            <tr>
                                <td data-label="Nome">{{ $ong->nomeOng }}</td>
                                <td data-label="CNPJ">{{ formatCnpj($ong->cnpjOng) }}</td>
                                <td data-label="E-mail">{{ $ong->emailOng }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <form action="{{ route('admin.ongs.aprovar', $ong->idOng) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-action btn-approve">
                                                <i class="fas fa-check"></i> Aprovar
                                            </button>
                                        </form>
                                        <button type="button" class="btn-action btn-reject" onclick="abrirModal('rejeitarModal{{ $ong->idOng }}')">
                                            <i class="fas fa-times"></i> Rejeitar
                                        </button>
                                    </div>

                                    <!-- Modal de Rejeição -->
                                    <div class="modal" id="rejeitarModal{{ $ong->idOng }}">
                                        <div class="rejection-modal">
                                            <form action="{{ route('admin.ongs.rejeitar', $ong->idOng) }}" method="POST">
                                                @csrf
                                                <div class="rejection-modal-header">
                                                    <h5 class="rejection-modal-title">Motivo da Rejeição</h5>
                                                    <button type="button" class="btn-close" onclick="fecharModal('rejeitarModal{{ $ong->idOng }}')">&times;</button>
                                                </div>
                                                <div class="rejection-modal-body">
                                                    <div class="form-group">
                                                        <label for="motivo">Informe o motivo:</label>
                                                        <textarea class="rejection-textarea" name="motivo" required rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="rejection-modal-footer">
                                                    <button type="button" class="btn btn-secondary" onclick="fecharModal('rejeitarModal{{ $ong->idOng }}')">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Confirmar Rejeição</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="empty-message">Nenhuma ONG pendente de aprovação</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Funções para controlar o modal - CORRIGIDAS
        function abrirModal(id) {
            document.getElementById(id).classList.add('active');
            document.body.style.overflow = 'hidden'; // Impede scroll da página
        }

        function fecharModal(id) {
            document.getElementById(id).classList.remove('active');
            document.body.style.overflow = 'auto'; // Restaura scroll
        }

        // Fechar modal ao clicar fora do conteúdo
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                fecharModal(event.target.id);
            }
        });

        // Fechar modal com ESC
        window.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(modal => {
                    fecharModal(modal.id);
                });
            }
        });
    </script>
    <script src="{{ asset('js/adm.js') }}"></script>
    <script src="{{ asset('js/dark.js') }}"></script>
    <script src="{{ asset('js/notifica.js') }}"></script>
</body>

</html>