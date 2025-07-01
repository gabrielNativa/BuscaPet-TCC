<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/site/logo 2.png') }}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style4.css') }}">
    <title>Login ADM</title>
</head>

<body>
    <div class="con">
        <div class="esquerda">
            <img class="img" src="{{ asset('img/site/Logo busca.png') }}" alt="Dog and Cat">
        </div>
        <div class="direita">
            <div class="titu">
                <h1>Administrador</h1>
            </div>
            <div class="caixa-lg">
                <div class="tit">
                    <h2>Login</h2>
                    <img class="pata" src="{{ asset('img/pata.png') }}">
                </div>

                <!-- Formulário de Login -->
                <form method="POST" action="{{ url('/login') }}">
                    @csrf
                    <div class="grupo-in">
                        <label for="email" class="subtitulo">E-mail:</label>
                        <input type="email" name="email" class="input" id="email" placeholder="Digite seu E-mail..." value="{{ old('email') }}">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grupo-in">
                        <label for="password" class="subtitulo">Senha:</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="password" name="password" class="input" id="password" placeholder="Digite sua Senha..." style="width: 100%; padding-right: 40px;">
                            <span id="togglePassword" class="material-icons" style="cursor: pointer; position: absolute; right: 10px; color: white;">visibility</span>
                        </div>
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="bt">
                        <button type="submit" class="botao">Login</button>
                    </div>
                </form>
                <div class="ong">
                    <p class="a2">Caso seja uma ong </p><a class="a1" href="/ong/login">Clique Aqui</a>
                </div>

            </div>
        </div>
    </div>
    <script src="{{ asset('js/password.js') }}"></script>
</body>

</html>