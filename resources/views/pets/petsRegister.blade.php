<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel das Pets</title>
    <link rel="shortcut icon" href="{{ asset('img/site/logo 2.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style66.css') }}">
    <style>
        /* Estilos para uniformizar todos os inputs */
        .custom-select-container {
            position: relative;
            width: 100%;
        }

        .custom-select-container input,
        .custom-select-container select,
        .custom-select-container textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        .custom-select-container input:focus,
        .custom-select-container select:focus,
        .custom-select-container textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .custom-select-container input:hover,
        .custom-select-container select:hover,
        .custom-select-container textarea:hover {
            border-color: #007bff;
        }

        .custom-select-container textarea {
            resize: vertical;
            min-height: 120px;
        }

        .custom-select-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .custom-select-trigger:hover {
            border-color: #007bff;
        }

        .custom-select-trigger.active {
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .custom-select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            max-height: 300px;
            overflow: hidden;
        }

        .custom-select-dropdown.show {
            display: block;
        }

        .select-search {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
        }

        .select-search input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
        }

        .select-search input:focus {
            border-color: #007bff;
        }

        .select-options {
            max-height: 200px;
            overflow-y: auto;
        }

        .select-option {
            padding: 12px 16px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .select-option:hover {
            background-color: #f8f9fa;
        }

        .select-option.selected {
            background-color: #007bff;
            color: white;
        }

        .select-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 16px;
            border-top: 1px solid #eee;
            background-color: #f8f9fa;
            font-size: 12px;
        }

        .pagination-controls {
            display: flex;
            gap: 8px;
        }

        .pagination-btn {
            padding: 4px 8px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 4px;
            font-size: 12px;
            transition: all 0.2s ease;
        }

        .pagination-btn:hover:not(:disabled) {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .no-results {
            padding: 16px;
            text-align: center;
            color: #666;
            font-style: italic;
        }

        /* Overlay para fechar o dropdown ao clicar fora */
        .select-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999;
            display: none;
        }

        .select-overlay.show {
            display: block;
        }
    </style>
</head>

<body>
<div class="container">
    @include('componentes.sidebar')

    <div class="main-content">
        <div class="card-table">
            <h1>{{ isset($animal) ? 'Editar Pet' : 'Cadastrar Novo Pet' }}</h1>

            <form id="formAnimal" method="POST" action="{{ isset($animal) ? route('pets.update', $animal->idAnimal) : route('pets.store') }}" enctype="multipart/form-data">
                @csrf
                @if (isset($animal))
                    @method('PUT')
                @endif

                <!-- Upload de imagem principal -->
                <div class="img-preview-container">
                    <img id="previewImgPrincipal" 
                         src="{{ isset($animal) && $animal->imgPrincipal ? asset('img/imgAnimal/' . $animal->imgPrincipal) : asset('img/imgAnimal/perfil.png') }}" 
                         alt="Imagem Principal" 
                         class="img-preview">
                    <br>
                    <label for="imgPrincipal" class="btn">
                        <i class="fas fa-upload"></i> Carregar Imagem Principal
                    </label>
                    <input type="file" id="imgPrincipal" name="imgPrincipal" accept="image/*" style="display: none;">
                    @error('imgPrincipal')
                        <div style="color: red; font-size: 0.875rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Formulário de dados -->
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nomeAnimal">Nome:</label>
                        <div class="custom-select-container">
                            <input type="text" name="nomeAnimal" id="nomeAnimal" 
                                   class="{{ $errors->has('nomeAnimal') ? 'is-invalid' : '' }}" 
                                   value="{{ old('nomeAnimal', $animal->nomeAnimal ?? '') }}" required>
                        </div>
                        @error('nomeAnimal')
                            <div style="color: red; font-size: 0.875rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="idadeAnimal">Idade:</label>
                        <div class="custom-select-container">
                            <input type="text" name="idadeAnimal" id="idadeAnimal" 
                                   class="{{ $errors->has('idadeAnimal') ? 'is-invalid' : '' }}" 
                                   value="{{ old('idadeAnimal', $animal->idadeAnimal ?? '') }}" required>
                        </div>
                        @error('idadeAnimal')
                            <div style="color: red; font-size: 0.875rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="idEspecie">Espécie:</label>
                        <div class="custom-select-container">
                            <select name="idEspecie" id="idEspecie" onchange="getRacas()">
                                @foreach($especies as $especie)
                                    <option value="{{ $especie->idEspecie }}" {{ (old('idEspecie', $animal->idEspecie ?? '') == $especie->idEspecie) ? 'selected' : '' }}>
                                        {{ $especie->nomeEspecie }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="idRaca">Raça:</label>
                        <div class="custom-select-container">
                            <!-- Campo hidden para enviar o valor selecionado -->
                            <input type="hidden" name="idRaca" id="idRaca" value="{{ old('idRaca', $animal->idRaca ?? '') }}">
                            
                            <!-- Trigger do select customizado -->
                            <div class="custom-select-trigger" id="racaSelectTrigger">
                                <span id="racaSelectedText">Selecione uma raça</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>

                            <!-- Overlay para fechar o dropdown -->
                            <div class="select-overlay" id="selectOverlay"></div>

                            <!-- Dropdown do select customizado -->
                            <div class="custom-select-dropdown" id="racaDropdown">
                                <div class="select-search">
                                    <input type="text" id="racaSearch" placeholder="Pesquisar raça...">
                                </div>
                                <div class="select-options" id="racaOptions">
                                    <!-- As opções serão carregadas via JavaScript -->
                                </div>
                                <div class="select-pagination">
                                    <span id="paginationInfo">0 de 0</span>
                                    <div class="pagination-controls">
                                        <button type="button" class="pagination-btn" id="prevPage">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button type="button" class="pagination-btn" id="nextPage">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="idPorte">Porte:</label>
                        <div class="custom-select-container">
                            <select name="idPorte" id="idPorte">
                                @foreach($portes as $porte)
                                    <option value="{{ $porte->idPorte }}" {{ (old('idPorte', $animal->idPorte ?? '') == $porte->idPorte) ? 'selected' : '' }}>
                                        {{ $porte->nomePorte }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="idPelagemAnimal">Pelagem:</label>
                        <div class="custom-select-container">
                            <select name="idPelagemAnimal" id="idPelagemAnimal">
                                @foreach($pelagens as $pelagem)
                                    <option value="{{ $pelagem->idPelagemAnimal }}" {{ (old('idPelagemAnimal', $animal->idPelagemAnimal ?? '') == $pelagem->idPelagemAnimal) ? 'selected' : '' }}>
                                        {{ $pelagem->pelagemAnimal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="bioAnimal">Biografia:</label>
                        <div class="custom-select-container">
                            <textarea name="bioAnimal" id="bioAnimal" rows="5">{{ old('bioAnimal', $animal->bioAnimal ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="form-actions">
                    <a class="btn" href="{{ route('pets.index') }}">Voltar</a>
                    <button type="submit" class="btn">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Variáveis globais para o select de raças
    let allRacas = [];
    let filteredRacas = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let selectedRacaId = "{{ isset($animal) ? $animal->idRaca : '' }}";

    // Preview da imagem principal
    document.getElementById('imgPrincipal').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('previewImgPrincipal').src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    });

    // Função para carregar raças dinamicamente
    function getRacas() {
        var idEspecie = $('#idEspecie').val();

        if (idEspecie) {
            $.ajax({
                url: '/racas/' + idEspecie,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    allRacas = data;
                    filteredRacas = [...allRacas];
                    currentPage = 1;
                    
                    // Atualiza o texto selecionado se houver uma raça pré-selecionada
                    updateSelectedText();
                    renderRacaOptions();
                },
                error: function(xhr) {
                    console.error('Erro ao buscar raças:', xhr.responseText);
                    alert('Erro ao carregar as raças. Consulte o console para detalhes.');
                    allRacas = [];
                    filteredRacas = [];
                    renderRacaOptions();
                }
            });
        } else {
            allRacas = [];
            filteredRacas = [];
            $('#racaSelectedText').text('Selecione uma espécie primeiro');
            $('#idRaca').val('');
            renderRacaOptions();
        }
    }

    // Função para atualizar o texto do item selecionado
    function updateSelectedText() {
        if (selectedRacaId && allRacas.length > 0) {
            const selectedRaca = allRacas.find(r => r.idRaca == selectedRacaId);
            if (selectedRaca) {
                $('#racaSelectedText').text(selectedRaca.nomeRaca);
                $('#idRaca').val(selectedRacaId);
            }
        } else {
            $('#racaSelectedText').text(allRacas.length > 0 ? 'Selecione uma raça' : 'Nenhuma raça disponível');
        }
    }

    // Função para renderizar as opções de raça com paginação
    function renderRacaOptions() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, filteredRacas.length);
        const pageRacas = filteredRacas.slice(startIndex, endIndex);

        const optionsContainer = $('#racaOptions');
        optionsContainer.empty();

        if (pageRacas.length === 0) {
            optionsContainer.append('<div class="no-results">Nenhuma raça encontrada</div>');
        } else {
            pageRacas.forEach(function(raca) {
                const isSelected = raca.idRaca == selectedRacaId;
                const optionHtml = `
                    <div class="select-option ${isSelected ? 'selected' : ''}" 
                         data-value="${raca.idRaca}" 
                         onclick="selectRaca(${raca.idRaca}, '${raca.nomeRaca}')">
                        ${raca.nomeRaca}
                    </div>
                `;
                optionsContainer.append(optionHtml);
            });
        }

        // Atualiza informações de paginação
        updatePaginationInfo();
    }

    // Função para atualizar as informações de paginação
    function updatePaginationInfo() {
        const totalItems = filteredRacas.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const startItem = totalItems > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
        const endItem = Math.min(currentPage * itemsPerPage, totalItems);

        $('#paginationInfo').text(`${startItem}-${endItem} de ${totalItems}`);
        
        // Atualiza estado dos botões de paginação
        $('#prevPage').prop('disabled', currentPage <= 1);
        $('#nextPage').prop('disabled', currentPage >= totalPages);
    }

    // Função para selecionar uma raça
    function selectRaca(idRaca, nomeRaca) {
        selectedRacaId = idRaca;
        $('#idRaca').val(idRaca);
        $('#racaSelectedText').text(nomeRaca);
        
        // Atualiza a seleção visual
        $('.select-option').removeClass('selected');
        $(`.select-option[data-value="${idRaca}"]`).addClass('selected');
        
        // Fecha o dropdown
        closeRacaDropdown();
    }

    // Função para abrir o dropdown
    function openRacaDropdown() {
        $('#racaSelectTrigger').addClass('active');
        $('#racaDropdown').addClass('show');
        $('#selectOverlay').addClass('show');
        $('#racaSearch').focus();
    }

    // Função para fechar o dropdown
    function closeRacaDropdown() {
        $('#racaSelectTrigger').removeClass('active');
        $('#racaDropdown').removeClass('show');
        $('#selectOverlay').removeClass('show');
        $('#racaSearch').val('');
        
        // Restaura a lista completa
        filteredRacas = [...allRacas];
        currentPage = 1;
        renderRacaOptions();
    }

    // Event listeners
    $(document).ready(function() {
        // Inicializa o select se já houver espécie selecionada
        if ($('#idEspecie').val()) {
            getRacas();
        }

        // Click no trigger para abrir/fechar dropdown
        $('#racaSelectTrigger').click(function(e) {
            e.stopPropagation();
            if ($('#racaDropdown').hasClass('show')) {
                closeRacaDropdown();
            } else {
                openRacaDropdown();
            }
        });

        // Click no overlay para fechar
        $('#selectOverlay').click(function() {
            closeRacaDropdown();
        });

        // Pesquisa de raças
        $('#racaSearch').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            filteredRacas = allRacas.filter(raca => 
                raca.nomeRaca.toLowerCase().includes(searchTerm)
            );
            currentPage = 1;
            renderRacaOptions();
        });

        // Navegação de páginas
        $('#prevPage').click(function() {
            if (currentPage > 1) {
                currentPage--;
                renderRacaOptions();
            }
        });

        $('#nextPage').click(function() {
            const totalPages = Math.ceil(filteredRacas.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderRacaOptions();
            }
        });

        // Previne o fechamento do dropdown ao clicar dentro dele
        $('#racaDropdown').click(function(e) {
            e.stopPropagation();
        });
    });
</script>

</body>
</html>