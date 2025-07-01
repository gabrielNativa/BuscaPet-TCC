<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor ONG</title>
    <link rel="shortcut icon" href="{{ asset("img/site/logo 2.png") }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset("css/sidebar.css") }}">
    <link rel="stylesheet" href="{{ asset("css/style66.css") }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .action-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .edit-btn {
            color: #4CAF50;
            transition: all 0.3s ease;
            font-size: 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .edit-btn:hover {
            color: #388E3C;
            transform: scale(1.2);
        }

        .delete-btn {
            color: #F44336;
            transition: all 0.3s ease;
            font-size: 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .delete-btn:hover {
            color: #D32F2F;
            transform: scale(1.2);
        }

        td {
            vertical-align: middle !important;
        }

        /* Centralização específica para as colunas de Status e Interesse */
        .status-column, .interesse-column {
            text-align: center !important;
            vertical-align: middle !important;
            padding: 12px !important;
        }

        .status-column .status-badge {
            display: inline-block;
            margin: 0 auto;
        }

        .interesse-column .interesse-badge {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        .animal-row {
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .animal-row:hover {
            background-color: #f8f9fa !important;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 2% auto;
            padding: 0;
            border-radius: 12px;
            width: 95%;
            max-width: 1000px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .modal-header {
            background: #667eea;
            color: white;
            padding: 20px 30px;
            border-radius: 12px 12px 0 0;
            position: relative;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 20px;
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .close:hover {
            opacity: 0.7;
        }

        .modal-body {
            padding: 30px;
        }

        .animal-info {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .animal-image {
            text-align: center;
        }

        .animal-image img {
            width: 100%;
            max-width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .animal-details {
            display: grid;
            gap: 15px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .detail-item i {
            margin-right: 12px;
            color: #667eea;
            width: 20px;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            margin-right: 8px;
        }

        .detail-value {
            color: #6c757d;
        }

        .bio-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        .bio-section h4 {
            margin: 0 0 10px 0;
            color: #495057;
            display: flex;
            align-items: center;
        }

        .bio-section h4 i {
            margin-right: 8px;
            color: #28a745;
        }

        /* Seção centralizada de status e interesse */
        .status-interesse-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
        }

        .status-card, .interesse-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .status-card h3, .interesse-card h3 {
            margin: 0 0 15px 0;
            color: #495057;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            white-space: nowrap;
        }

        .status-adocao {
            background-color: #007bff;
            color: white;
        }

        .status-analise {
            background-color: #ffc107;
            color: #212529;
        }

        .status-adotado {
            background-color: #28a745;
            color: white;
        }

        .interesse-count {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
        }

        .interesse-badge {
            background-color: #ffc107;
            color: #212529;
            padding: 6px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .interesse-badge i {
            font-size: 10px;
        }

        /* Seção de usuários interessados */
        .usuarios-interessados-section {
            margin-top: 30px;
            padding: 25px;
            background-color: #fff;
            border-radius: 12px;
            border: 1px solid #dee2e6;
        }

        .usuarios-interessados-section h3 {
            margin: 0 0 20px 0;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .usuarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .usuario-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #007bff;
            transition: transform 0.2s ease;
        }

        .usuario-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .usuario-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .usuario-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
        }

        .usuario-avatar.default {
            background: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .usuario-info h4 {
            margin: 0 0 5px 0;
            color: #495057;
            font-size: 16px;
        }

        .usuario-info p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        .usuario-contato {
            display: grid;
            gap: 8px;
        }

        .contato-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #495057;
        }

        .contato-item i {
            color: #007bff;
            width: 16px;
        }

        .usuario-observacoes {
            margin-top: 15px;
            padding: 10px;
            background: white;
            border-radius: 6px;
            font-size: 14px;
            color: #495057;
            font-style: italic;
        }

        .status-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 15px;
        }

        .btn-status {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 140px;
            justify-content: center;
        }

        .btn-analise {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-analise:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        }

        .btn-adotado {
            background-color: #28a745;
            color: white;
        }

        .btn-adotado:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .btn-voltar {
            background-color: #6c757d;
            color: white;
        }

        .btn-voltar:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
        }

        .finalizado-status {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }

        .finalizado-status i {
            margin-right: 10px;
            font-size: 20px;
        }

        .alert {
            padding: 12px 20px;
            margin: 15px 0;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .alert i {
            margin-right: 10px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .delete-modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .modal-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .modal-buttons button {
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-buttons button:first-child {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .modal-buttons button:last-child {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .no-usuarios {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 98%;
                margin: 1% auto;
            }

            .animal-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .status-interesse-section {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .usuarios-grid {
                grid-template-columns: 1fr;
            }

            .status-actions {
                flex-direction: column;
            }

            .btn-status {
                min-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        @include("componentes.sidebar")

        <div class="main-content">
            <div class="card-table-container">
                <div class="card-table">
                    <h1>Painel dos Pets</h1>

                    <!-- Área para mensagens de feedback -->
                    <div id="message-area"></div>

                    <div class="d-flex justify-content-end">
                        <a class="btn btn px-3" href="{{ route("pets.create") }}">
                            <i class="fas fa-plus me-2"></i>Adicionar Animal
                        </a>
                    </div>

                    @if($animais->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Nenhum animal cadastrado ainda. <a href="{{ route("pets.create") }}">Cadastre seu primeiro pet!</a>
                    </div>
                    @else
                    <table class="table table-hover table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Idade</th>
                                <th>Raça</th>
                                <th>Porte</th>
                                <th>Status</th>
                                <th>Interesses</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($animais as $animal)
                            <tr class="animal-row" onclick="abrirModal({{ $animal->idAnimal }})">
                                <td title="Clique para ver detalhes do animal">{{ $animal->nomeAnimal }}</td>
                                <td title="Clique para ver detalhes do animal">{{ $animal->idadeAnimal }}</td>
                                <td title="Clique para ver detalhes do animal">{{ $animal->raca->nomeRaca ?? "N/A" }}</td>
                                <td title="Clique para ver detalhes do animal">{{ $animal->porte->nomePorte ?? "N/A" }}</td>
                                <td class="status-column" title="Clique para ver detalhes do animal">
                                    <span class="status-badge 
                                            @if($animal->status->descStatusAnimal == " Adoção") status-adocao
                                        @elseif($animal->status->descStatusAnimal == "Análise") status-analise
                                        @elseif($animal->status->descStatusAnimal == "Adotado") status-adotado
                                        @endif">
                                        {{ $animal->status->descStatusAnimal ?? "N/A" }}
                                    </span>
                                </td>
                                <td class="interesse-column" onclick="event.stopPropagation();" title="Clique para ver detalhes do animal">
                                    <span class="interesse-badge">
                                        <i class="fas fa-heart"></i>
                                        {{ $animal->interesses_count ?? 0 }}
                                    </span>
                                </td>
                                <td onclick="event.stopPropagation();">
                                    <div class="action-btn">
                                        <a class="edit-btn" href="{{ route('pets.edit', $animal->idAnimal) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                                <td onclick="event.stopPropagation();">
                                    <div class="action-btn">
                                        <button class="delete-btn" onclick="showDeleteModal({{ $animal->idAnimal }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes do Animal -->
    <div id="animalModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Detalhes do Animal</h2>
                <span class="close" onclick="fecharModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div id="modal-message-area"></div>

                <div class="animal-info">
                    <div class="animal-image">
                        <img id="modalImage" src="" alt="Foto do animal">
                    </div>
                    <div class="animal-details">
                        <div class="detail-item">
                            <i class="fas fa-paw"></i>
                            <span class="detail-label">Nome:</span>
                            <span class="detail-value" id="modalNome"></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="detail-label">Idade:</span>
                            <span class="detail-value" id="modalIdade"></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-dog"></i>
                            <span class="detail-label">Espécie:</span>
                            <span class="detail-value" id="modalEspecie"></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-dna"></i>
                            <span class="detail-label">Raça:</span>
                            <span class="detail-value" id="modalRaca"></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-ruler-vertical"></i>
                            <span class="detail-label">Porte:</span>
                            <span class="detail-value" id="modalPorte"></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-palette"></i>
                            <span class="detail-label">Pelagem:</span>
                            <span class="detail-value" id="modalPelagem"></span>
                        </div>
                    </div>
                </div>

                <!-- Seção centralizada de Status e Interesse -->
                <div class="status-interesse-section">
                    <div class="status-card">
                        <h3><i class="fas fa-info-circle"></i>Status do Animal</h3>
                        <div class="status-badge" id="modalStatusBadge"></div>
                        <div id="statusActions" class="status-actions">
                            <!-- Botões serão inseridos dinamicamente via JavaScript -->
                        </div>
                        <div id="finalizadoStatus" class="finalizado-status" style="display: none;">
                            <i class="fas fa-check-circle"></i>
                            <span>Este animal já foi adotado!</span>
                        </div>
                    </div>
                    
                    <div class="interesse-card">
                        <h3><i class="fas fa-heart"></i>Interesse dos Usuários</h3>
                        <div class="interesse-count" id="interesseCount">0</div>
                        <p>usuário(s) demonstraram interesse</p>
                    </div>
                </div>

                <div class="bio-section" id="bioSection">
                    <h4><i class="fas fa-file-alt"></i>Biografia</h4>
                    <p id="modalBio"></p>
                </div>

                <!-- Seção de usuários interessados -->
                <div class="usuarios-interessados-section">
                    <h3><i class="fas fa-users"></i>Usuários Interessados</h3>
                    <div id="usuariosGrid" class="usuarios-grid">
                        <!-- Usuários serão inseridos dinamicamente via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="modal">
        <div class="delete-modal-content">
            <h3>Confirmar Exclusão</h3>
            <p>Tem certeza que deseja excluir este animal?</p>
            <div class="modal-buttons">
                <button onclick="closeDeleteModal()">Cancelar</button>
                <button onclick="confirmDelete()">Excluir</button>
            </div>
        </div>
    </div>

    <script>
        let animalParaExcluir = null;

        function abrirModal(idAnimal) {
            console.log('Tentando abrir modal para animal ID:', idAnimal);
            
            // Fazer requisição AJAX para buscar detalhes completos
            // CORREÇÃO: URL correta baseada nas rotas do Laravel
            fetch(`/pets/${idAnimal}/detalhes`)
                .then(response => {
                    console.log('Status da resposta:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Dados recebidos:', data);
                    if (data.success) {
                        const animal = data.animal;
                        preencherModal(animal);
                        document.getElementById('animalModal').style.display = 'block';
                        console.log('Modal aberto com sucesso');
                    } else {
                        console.error('Erro na resposta:', data.message);
                        alert('Erro ao carregar detalhes do animal: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    alert('Erro ao carregar detalhes do animal: ' + error.message);
                });
        }

        function preencherModal(animal) {
            // Preenche os dados básicos
            document.getElementById('modalTitle').textContent = `Detalhes - ${animal.nomeAnimal}`;
            document.getElementById('modalNome').textContent = animal.nomeAnimal;
            document.getElementById('modalIdade').textContent = animal.idadeAnimal;
            document.getElementById('modalEspecie').textContent = animal.especie;
            document.getElementById('modalRaca').textContent = animal.raca;
            document.getElementById('modalPorte').textContent = animal.porte;
            document.getElementById('modalPelagem').textContent = animal.pelagem;
            document.getElementById('modalBio').textContent = animal.bioAnimal || 'Nenhuma biografia disponível.';

            // Preenche a contagem de interesses
            const interesseCount = animal.interesses_count || 0;
            document.getElementById('interesseCount').textContent = interesseCount;

            // Define a imagem
            const modalImage = document.getElementById('modalImage');
            if (animal.imgPrincipal) {
                modalImage.src = `/img/imgAnimal/${animal.imgPrincipal}`;
            } else {
                modalImage.src = '/img/default-pet.jpg';
            }

            // Define o status
            const statusBadge = document.getElementById('modalStatusBadge');
            statusBadge.textContent = animal.status.nome;
            statusBadge.className = 'status-badge';

            if (animal.status.nome === 'Adoção') {
                statusBadge.classList.add('status-adocao');
            } else if (animal.status.nome === 'Análise') {
                statusBadge.classList.add('status-analise');
            } else if (animal.status.nome === 'Adotado') {
                statusBadge.classList.add('status-adotado');
            }

            // Configura os botões de ação baseado no status
            configurarBotoesStatus(animal.idAnimal, animal.status.id);

            // Preenche os usuários interessados
            preencherUsuariosInteressados(animal.usuarios_interessados);
        }

        function aplicarMascaraTelefone(telefone) {
            // Remove todos os caracteres não numéricos
            const numeroLimpo = telefone.replace(/\D/g, '');
            
            // Aplica a máscara baseada na quantidade de dígitos
            if (numeroLimpo.length === 11) {
                // Celular: (XX) XXXXX-XXXX
                return numeroLimpo.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (numeroLimpo.length === 10) {
                // Fixo: (XX) XXXX-XXXX
                return numeroLimpo.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else {
                // Retorna o número original se não tiver 10 ou 11 dígitos
                return telefone;
            }
        }

        function preencherUsuariosInteressados(usuarios) {
            const usuariosGrid = document.getElementById('usuariosGrid');
            
            if (!usuarios || usuarios.length === 0) {
                usuariosGrid.innerHTML = '<div class="no-usuarios">Nenhum usuário demonstrou interesse ainda.</div>';
                return;
            }

            usuariosGrid.innerHTML = '';
            
            usuarios.forEach(usuario => {
                const usuarioCard = document.createElement('div');
                usuarioCard.className = 'usuario-card';
                
                const avatarHtml = usuario.imgUser 
                    ? `<img src="${usuario.imgUser}" alt="${usuario.nomeUser}" class="usuario-avatar">`
                    : `<div class="usuario-avatar default">${usuario.nomeUser.charAt(0).toUpperCase()}</div>`;
                
                const observacoesHtml = usuario.observacoes 
                    ? `<div class="usuario-observacoes"><strong>Observações:</strong> ${usuario.observacoes}</div>`
                    : '';

                // Aplica a máscara no telefone
                const telefoneFormatado = aplicarMascaraTelefone(usuario.telUser);

                usuarioCard.innerHTML = `
                    <div class="usuario-header">
                        ${avatarHtml}
                        <div class="usuario-info">
                            <h4>${usuario.nomeUser}</h4>
                            <p>Interessado desde ${formatarData(usuario.dataInteresse)}</p>
                        </div>
                    </div>
                    <div class="usuario-contato">
                        <div class="contato-item">
                            <i class="fas fa-phone"></i>
                            <span>${telefoneFormatado}</span>
                        </div>
                        <div class="contato-item">
                            <i class="fas fa-envelope"></i>
                            <span>${usuario.emailUser}</span>
                        </div>
                    </div>
                    ${observacoesHtml}
                `;
                
                usuariosGrid.appendChild(usuarioCard);
            });
        }

        function formatarData(dataString) {
            const data = new Date(dataString);
            return data.toLocaleDateString('pt-BR');
        }

        function configurarBotoesStatus(idAnimal, statusId) {
            const statusActions = document.getElementById('statusActions');
            const finalizadoStatus = document.getElementById('finalizadoStatus');

            statusActions.innerHTML = '';
            finalizadoStatus.style.display = 'none';

            if (statusId == 3) { // Adoção
                statusActions.innerHTML = `
                    <button class="btn-status btn-analise" onclick="alterarStatus(${idAnimal}, 'colocar_analise')">
                        <i class="fas fa-search"></i>
                        Colocar em Análise
                    </button>
                `;
            } else if (statusId == 5) { // Análise
                statusActions.innerHTML = `
                    <button class="btn-status btn-adotado" onclick="alterarStatus(${idAnimal}, 'marcar_adotado')">
                        <i class="fas fa-check"></i>
                        Marcar como Adotado
                    </button>
                    <button class="btn-status btn-voltar" onclick="alterarStatus(${idAnimal}, 'voltar_adocao')">
                        <i class="fas fa-arrow-left"></i>
                        Voltar para Adoção
                    </button>
                `;
            } else if (statusId == 4) { // Adotado
                finalizadoStatus.style.display = 'flex';
            }
        }

        function alterarStatus(idAnimal, acao) {
            fetch(`/pets/${idAnimal}/alterar-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        acao: acao
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success', 'modal-message-area');

                        // Atualiza o status no modal
                        const statusBadge = document.getElementById('modalStatusBadge');
                        statusBadge.textContent = data.novoStatus;
                        statusBadge.className = 'status-badge';

                        if (data.novoStatus === 'Adoção') {
                            statusBadge.classList.add('status-adocao');
                        } else if (data.novoStatus === 'Análise') {
                            statusBadge.classList.add('status-analise');
                        } else if (data.novoStatus === 'Adotado') {
                            statusBadge.classList.add('status-adotado');
                        }

                        // Reconfigura os botões
                        configurarBotoesStatus(idAnimal, data.novoStatusId);

                        // Recarrega a página após 2 segundos para atualizar a tabela
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        showMessage(data.message, 'error', 'modal-message-area');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showMessage('Erro ao alterar status', 'error', 'modal-message-area');
                });
        }

        function fecharModal() {
            document.getElementById('animalModal').style.display = 'none';
            document.getElementById('modal-message-area').innerHTML = '';
        }

        function showDeleteModal(idAnimal) {
            animalParaExcluir = idAnimal;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            animalParaExcluir = null;
        }

        function confirmDelete() {
            if (animalParaExcluir) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pets/${animalParaExcluir}`;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showMessage(message, type, containerId = 'message-area') {
            const container = document.getElementById(containerId);
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';

            container.innerHTML = `
                <div class="alert ${alertClass}">
                    <i class="${icon}"></i>
                    ${message}
                </div>
            `;

            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        // Fecha o modal quando clica fora dele
        window.onclick = function(event) {
            const modal = document.getElementById('animalModal');
            const deleteModal = document.getElementById('deleteModal');

            if (event.target === modal) {
                fecharModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }

        // Exibe mensagens de sessão se existirem
        @if(session('success'))
        showMessage('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
        showMessage('{{ session('error') }}', 'error');
        @endif
    </script>
</body>

</html>