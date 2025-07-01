<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Raças</title>
    <link rel="shortcut icon" href="{{ asset('img/site/logo 2.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style66.css') }}">
</head>

<body>
<div class="container">
    @include('componentes.sidebar')

    <div class="main-content">
        <div class="card-table">
            <h1>Gerenciamento de Raças</h1>
            
            <button id="openRaceModal" class="btn">
                <i class="fas fa-plus"></i> Adicionar Raça
            </button>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome da Raça</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($racas as $raca)
                    <tr>
                        <td>{{ $raca->idRaca }}</td>
                        <td>{{ $raca->nomeRaca }}</td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Adicionar/Editar Raça -->
<div id="raceModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h3 id="modalTitle">Adicionar Nova Raça</h3>
            <span class="custom-close-modal">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="raceForm">
                @csrf
                <input type="hidden" id="raceId" name="idRaca">
                
                <div class="form-group">
                    <label for="nomeRaca">Nome da Raça</label>
                    <input type="text" id="nomeRaca" name="nomeRaca" required>
                    <span class="required-badge">Campo obrigatório</span>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="idEspecie">Espécie</label>
                    <select id="idEspecie" name="idEspecie" required>
                        <option value="">Selecione uma espécie</option>
                        @foreach(App\Models\Especie::all() as $especie)
                            <option value="{{ $especie->idEspecie }}">{{ $especie->nomeEspecie }}</option>
                        @endforeach
                    </select>
                    <span class="required-badge">Campo obrigatório</span>
                    <div class="invalid-feedback"></div>
                </div>
            </form>
        </div>
        <div class="custom-modal-footer">
            <button class="cancel-btn">Cancelar</button>
            <button class="confirm-btn" id="saveRace">Salvar</button>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Abrir modal para adicionar nova raça
    $('#openRaceModal').click(function() {
        $('#modalTitle').text('Adicionar Nova Raça');
        $('#raceForm')[0].reset();
        $('#raceId').val('');
        $('#raceModal').fadeIn();
    });

    // Editar raça
    $('.edit-race').click(function() {
        const id = $(this).data('id');
        
        $.get(`/racas/${id}`, function(data) {
            $('#modalTitle').text('Editar Raça');
            $('#raceId').val(data.idRaca);
            $('#nomeRaca').val(data.nomeRaca);
            $('#raceModal').fadeIn();
        }).fail(function(error) {
            alert('Erro ao carregar dados da raça');
        });
    });

    // Salvar raça (criar ou atualizar)
    $('#saveRace').click(function() {
    const formData = {
        nomeRaca: $('#nomeRaca').val(),
        idEspecie: $('#idEspecie').val(),
        _token: '{{ csrf_token() }}'
    };

    const raceId = $('#raceId').val();
    const url = raceId ? `/racas/${raceId}` : '/racas';
    const method = raceId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: method,
        data: formData,
        success: function(response) {
            alert(response.message);
            $('#raceModal').fadeOut();
            location.reload();
        },
        error: function(error) {
            if(error.status === 422) {
                const errors = error.responseJSON.errors;
                for(const field in errors) {
                    $(`#${field}`).addClass('is-invalid');
                    $(`#${field}`).next('.invalid-feedback').text(errors[field][0]);
                }
            } else {
                alert('Erro ao salvar raça');
            }
        }
    });
});
    const formData = {
    nomeRaca: $('#nomeRaca').val(),
    idEspecie: $('#idEspecie').val(),
    _token: '{{ csrf_token() }}'
};
    // Excluir raça
    let raceToDelete = null;
    $('.delete-race').click(function() {
        raceToDelete = $(this).data('id');
        $('#confirmModal').fadeIn();
    });

    $('#confirmDelete').click(function() {
        if(raceToDelete) {
            $.ajax({
                url: `/racas/${raceToDelete}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    $('#confirmModal').fadeOut();
                    location.reload();
                },
                error: function() {
                    alert('Erro ao excluir raça');
                }
            });
        }
    });

    // Fechar modais
    $('.custom-close-modal, .cancel-btn').click(function() {
        $('#raceModal').fadeOut();
    });

    $('#cancelDelete').click(function() {
        $('#confirmModal').fadeOut();
        raceToDelete = null;
    });

    // Fechar modal ao clicar fora
    $(window).click(function(event) {
        if($(event.target).hasClass('custom-modal')) {
            $('#raceModal').fadeOut();
        }
        if($(event.target).hasClass('modal')) {
            $('#confirmModal').fadeOut();
        }
    });
});
</script>
</body>
</html>