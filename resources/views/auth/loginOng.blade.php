<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/site/logo 2.png') }}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style5.css') }}">
    <title>Login ONG</title>
</head>

<body>
    <div class="con">
        <div class="esquerda">
            <img class="img" src="{{ asset('img/site/Logo busca.png') }}" alt="log ong">
        </div>

        <div class="direita">
        <div class="ong">
                        <p class="subtitulo">Voltar ao Login ADM:</p><a class="a1" href="/">Clique Aqui</a>
                    </div>
            <div class="titu">
           
                <h1>ONGs</h1>
            </div>
            <div class="caixa-lg">
            
                <div class="tit">
                
                    <h2>Login</h2>
                    <img class="pata" src="{{ asset('img/pata.png') }}">
                </div>

                <!-- Formulário de Login -->
                <form method="POST" action="{{ route('ong.login.submit') }}">
                    @csrf
                    <div class="grupo-in">
                        <label for="cnpj" class="subtitulo">CNPJ:</label>
                        <input type="text" name="cnpj" class="input" id="cnpj" placeholder="Digite o CNPJ..." value="" data-mask="00.000.000/0000-00">
                    </div>
                    @error('cnpj')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
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
                   
                  
                    <div class="ong">
                        <p class="subtitulo">Cadastrar ONG:</p><a class=" a1" href="/ong/cadastrar">Clique Aqui</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/mascara.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('js/password.js') }}"></script>
    @if(session('modal_message'))
<div id="successModal" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header">
            <div class="icon-container">
                <svg viewBox="0 0 24 24" class="check-icon">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
            </div>
            <h3>Cadastro Recebido!</h3>
            <button onclick="hideModal()" class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <p>{{ session('modal_message') }}</p>
        </div>
        <div class="modal-footer">
            <button onclick="hideModal()" class="confirm-btn">Entendi</button>
        </div>
    </div>
</div>

<script>
    function hideModal() {
        document.getElementById('successModal').style.display = 'none';
    }
    
    // Mostra o modal automaticamente
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('successModal');
        if(modal) {
            modal.style.display = 'flex';
            setTimeout(hideModal, 5000);
        }
    });
</script>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        animation: fadeIn 0.3s;
    }
    
    .modal-card {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 450px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(20px);
        opacity: 0;
        animation: slideUp 0.3s forwards;
    }
    
    .modal-header {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #eee;
        position: relative;
    }
    
    .icon-container {
        width: 40px;
        height: 40px;
        background: #f0fdf4;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .check-icon {
        width: 24px;
        height: 24px;
        fill: #16a34a;
    }
    
    .close-btn {
        position: absolute;
        right: 20px;
        top: 20px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #777;
    }
    
    .modal-body {
        padding: 25px 20px;
        color: #555;
    }
    
    .modal-footer {
        padding: 15px 20px;
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid #eee;
    }
    
    .confirm-btn {
        background: #16a34a;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.2s;
    }
    
    .confirm-btn:hover {
        background: #15803d;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
@endif
</body>

</html>