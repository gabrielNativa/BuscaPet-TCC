<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor ADM</title>
    <link rel="shortcut icon" href="{{ asset('img/site/logo 2.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .invalid-feedback {
            color: red;
            font-size: 0.875rem;
        }

        .is-invalid {
            border: 2px solid red;
        }

        .hidden-input {
            display: none;
            background-color: transparent !important;
        }
    
    </style>
</head>

<body>
    <div class="container">
        @include('componentes.menuAdm')
        @include('componentes.headerAdm')

        <div class="content">
            <h1>Painel de Administradores</h1>
            <div class="card custom-card">
            <form id="formAdm" method="POST" 
      action="{{ isset($adm) ? route('admin.update', $adm->idAdm) : route('admin.store') }}"
      enctype="multipart/form-data">
                    @csrf
                    @if (isset($adm))
                    @method('PUT')
                    @endif
                    @if(isset($adm))
    <input type="hidden" name="id" value="{{ $adm->idAdm }}">
@endif

                    <div class="ali">
                        <div class="form-header">
                            <img id="preview" src="{{ isset($adm) && $adm->imgAdm ? asset('img/imgAdm/' . $adm->imgAdm) : asset('img/imgAdm/perfil.png') }}" alt="Preview da imagem" class="img-preview">
                            <div class="upload-container">
                                <label for="foto" class="btn-upload">Carregar Imagem</label>
                                <input type="file" id="foto" name="foto" accept="image/*" class="hidden-input">
                                <input type="hidden" name="acao" value="create">
                            </div>
                        </div>
                    </div>

                    <div class="form-body">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome" id="nome" maxlength="50" value="{{ old('nome', $adm->nomeAdm ?? '') }}" class="@error('nome') is-invalid @enderror" required>
                            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail:</label>
                            <input type="email" name="email" id="email" maxlength="50" value="{{ old('email', $adm->emailAdm ?? '') }}" class="@error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="cpf">CPF:</label>
                            <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $adm->cpfAdm ?? '') }}" class="@error('cpf') is-invalid @enderror" data-mask="000.000.000-00" required>
                            @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="cep">CEP:</label>
                            <input type="text" name="cep" id="cep" maxlength="9" value="{{ old('cep', $adm->cepAdm ?? '') }}" class="@error('cep') is-invalid @enderror" data-mask="00000-000" required>
                            @error('cep')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="uf">UF:</label>
                            <input type="text" name="uf" id="uf" maxlength="2" value="{{ old('uf', $adm->ufAdm ?? '') }}" class="@error('uf') is-invalid @enderror" required>
                            @error('uf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="endereco">Endereço:</label>
                            <input type="text" name="endereco" id="endereco" maxlength="100" value="{{ old('endereco', $adm->lograAdm ?? '') }}" class="@error('endereco') is-invalid @enderror" required>
                            @error('endereco')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="num">Num:</label>
                            <input type="text" name="num" id="num" maxlength="5" value="{{ old('num', $adm->numLograAdm ?? '') }}" class="@error('num') is-invalid @enderror" required>
                            @error('num')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="nasc">Data de Nasc.:</label>
                            <input type="date" name="nasc" id="nasc" value="{{ old('nasc', $adm->dataNascAdm ?? '') }}" class="@error('nasc') is-invalid @enderror" required>
                            @error('nasc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="celular">Celular:</label>
                            <input type="text" name="celular" id="celular" value="{{ old('celular', $adm->telAdm ?? '') }}" class="@error('celular') is-invalid @enderror" data-mask="(00) 00000-0000" required>
                            @error('celular')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <div class="password-container">
                                <input type="password" name="password" id="password" maxlength="10" class="form-control @error('password') is-invalid @enderror"
                                    @if (!isset($adm)) required @endif value="{{ old('password', $adm->password ?? '') }}">
                                <span id="togglePassword" class="material-icons">visibility</span> 
                            </div>
                            @if (isset($adm))
                            <div class="help-container">
                                <div id="popupHelp" class="popup-help">Se não quiser trocar a senha, deixe o campo em branco.</div>
                            </div>
                            <span id="helpIcon" class="material-icons help-icon" tabindex="0">help_outline</span>
                            @endif
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-footer">
                        <a class="btn btn-voltar" href="{{ route('admin.index') }}">Voltar</a>
                        <button type="submit" class="btn btn-salvar">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/dark.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('js/mascara.js') }}"></script>
    <script src="{{ asset('js/img.js') }}"></script>
    <script src="{{ asset('js/adm.js') }}"></script>
    <script src="{{ asset('js/cep.js') }}"></script>
    <script src="{{ asset('js/password.js') }}"></script>
</body>

</html>