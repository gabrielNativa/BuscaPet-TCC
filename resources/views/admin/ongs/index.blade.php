<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas as ONGs - BuscaPet</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <style>
        :root {
            --color-primary: #e1b882;
            --color-danger: #ff7782;
            --color-success: #41f1b6;
            --color-warning: #ffbb55;
            --color-white: #fff;
            --color-info-dark: #7d8da1;
            --color-info-light: #dce1eb;
            --color-dark: #e1b882;
            --color-light: #e1b882;
            --color-primary-variant: #e1b882;
            --color-dark-variant: #677483;
            --color-background: #f6f6f9;
            --card-border-radius: 12px;
            --border-radius-1: 4px;
            --border-radius-2: 8px;
            --border-radius-3: 12px;
            --card-padding: 1.5rem;
            --box-shadow: 0 2rem 3rem var(--color-light);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--color-background);
            color: var(--color-dark);
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.75rem;
            color: var(--color-dark);
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--color-white);
            border-radius: var(--border-radius-2);
            padding: 0.5rem 1rem;
            box-shadow: var(--box-shadow);
        }

        .search-box input {
            border: none;
            background: transparent;
            padding: 0.5rem;
            width: 250px;
            outline: none;
        }

        .primary-btn {
            background: var(--color-primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius-2);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .primary-btn:hover {
            background: var(--color-primary-variant);
            transform: translateY(-2px);
        }

        .ongs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .ong-card {
            background: var(--color-white);
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .ong-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .ong-header {
            padding: 1.25rem;
            border-bottom: 1px solid var(--color-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ong-header h2 {
            font-size: 1.1rem;
            color: var(--color-dark);
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-badge.aprovado {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-badge.pendente {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-badge.rejeitado {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .ong-body {
            padding: 1.25rem;
        }

        .ong-info-item {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .ong-info-item:last-child {
            margin-bottom: 0;
        }

        .ong-info-item i {
            color: var(--color-primary);
            font-size: 1.1rem;
            margin-top: 2px;
        }

        .ong-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--color-light);
            background: var(--color-background);
        }

        .details-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .details-btn:hover {
            color: var(--color-primary-variant);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .approve-btn, .reject-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .approve-btn {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .approve-btn:hover {
            background: #28a745;
            color: white;
        }

        .reject-btn {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .reject-btn:hover {
            background: #dc3545;
            color: white;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-1);
            text-decoration: none;
        }

        .pagination a {
            color: var(--color-primary);
            border: 1px solid var(--color-light);
        }

        .pagination a:hover {
            background: var(--color-primary);
            color: white;
        }

        .pagination .active span {
            background: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .ongs-grid {
                grid-template-columns: 1fr;
            }
            
            .search-box input {
                width: 180px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="material-icons-sharp">groups</i>
                Todas as ONGs Cadastradas
            </h1>
            <div class="header-actions">
                <div class="search-box">
                    <i class="material-icons-sharp">search</i>
                    <input type="text" placeholder="Pesquisar ONGs...">
                </div>
                <button class="primary-btn">
                    <i class="material-icons-sharp">filter_alt</i>
                    Filtrar
                </button>
            </div>
        </div>

        <div class="ongs-grid">
            @foreach($ongs as $ong)
            <div class="ong-card">
                <div class="ong-header">
                    <h2>{{ $ong->nomeOng }}</h2>
                    <span class="status-badge {{ $ong->status }}">
                        {{ ucfirst($ong->status) }}
                    </span>
                </div>
                
                <div class="ong-body">
                    <div class="ong-info-item">
                        <i class="material-icons-sharp">email</i>
                        <span>{{ $ong->emailOng }}</span>
                    </div>
                    
                    <div class="ong-info-item">
                        <i class="material-icons-sharp">phone</i>
                        <span>{{ $ong->telOng ?? 'Telefone não informado' }}</span>
                    </div>
                    
                    <div class="ong-info-item">
                        <i class="material-icons-sharp">place</i>
                        <span>{{ $ong->cidadeOng ?? 'Cidade não informada' }}, {{ $ong->ufOng ?? 'UF não informada' }}</span>
                    </div>
                </div>
                
                <div class="ong-footer">
                    <a href="{{ route('ongs.show', $ong->idOng) }}" class="details-btn">
                        <i class="material-icons-sharp">visibility</i>
                        Detalhes
                    </a>
                    
                    @if($ong->status == 'pendente')
                    <div class="action-buttons">
                        <a href="{{ route('admin.ongs.aprovar', $ong->idOng) }}" class="approve-btn">
                            <i class="material-icons-sharp">check</i>
                        </a>
                        <a href="{{ route('admin.ongs.rejeitar', $ong->idOng) }}" class="reject-btn">
                            <i class="material-icons-sharp">close</i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-container">
            {{ $ongs->links() }}
        </div>
    </div>
</body>
</html>