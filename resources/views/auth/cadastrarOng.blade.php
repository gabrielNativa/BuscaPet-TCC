<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/site/logo 2.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/style6.css') }}">
    <title>Cadastro ONG</title>
    <style>
        .hidden {
            display: none;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: 15px auto;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            width: 100%;
        }

        .toggle-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="con">
        <div class="direita">

            <div class="titu">
                <h1>ONGs</h1>
            </div>
            <div class="caixa-lg">
                <div class="tit">
                    <h2>Cadastrar-se</h2>
                    <img class="pata" src="{{ asset('img/pata.png') }}">
                </div>
                <form method="POST" action="{{ route('ong.store') }}">
                    @csrf

                    <!-- Primeira etapa -->
                    <div id="step-1">
                        <div class="grupo-in">
                            <label for="nome" class="subtitulo">Nome:</label>
                            <input type="text" name="nome" class="input" id="nome" placeholder="Digite seu nome..." maxlength="50">
                        </div>
                        <div class="grupo-in">
                            <label for="cnpj" class="subtitulo">CNPJ:</label>
                            <input type="text" name="cnpj" class="input" id="cnpj" placeholder="00.000.000/0000-00" maxlength="20" data-mask="00.000.000/0000-00">
                        </div>
                        <div class="grupo-in">
                            <label for="email" class="subtitulo">E-mail:</label>
                            <input type="email" name="email" class="input" id="email" placeholder="Digite seu E-mail...">
                        </div>
                        <div class="grupo-in">
                            <label for="celular" class="subtitulo">Telefone:</label>
                            <input type="text" name="celular" class="input" id="celular" placeholder="(00) 00000-0000" maxlength="15" data-mask="(00) 00000-0000">
                        </div>
                    </div>

                    <!-- Segunda etapa -->
                    <div id="step-2" class="hidden">
                        <div class="grupo-in">
                            <label for="cep" class="subtitulo">CEP:</label>
                            <input type="text" name="cep" class="input" id="cep" placeholder="00000-000" maxlength="9" data-mask="00000-000">
                        </div>
                        <div class="grupo-in">
                            <label for="endereco" class="subtitulo">Endereço:</label>
                            <input type="text" name="endereco" class="input" id="endereco" placeholder="Digite seu endereço...">
                        </div>
                        <div class="grupo-in">
                            <label for="password" class="subtitulo">Senha (mínimo 8 caracteres):</label>
                            <input type="password" name="password" class="input" id="password" placeholder="Digite no mínimo 8 caracteres" minlength="6">
                        </div>
                        <div class="grupo-in">
                            <label for="password_confirmation" class="subtitulo">Confirmar Senha:</label>
                            <input type="password" name="password_confirmation" class="input" id="password_confirmation" placeholder="Confirme sua Senha...">
                        </div>
                    </div>

                    <!-- Botão de alternância -->
                    <button type="button" class="toggle-btn" onclick="toggleStep()">Próximo</button>

                    <div class="bt">
                        <button type="submit" class="botao hidden" id="submit-btn"> <a class="a1">Cadastrar-se</a></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="esquerda">
            <img class="img" src="{{ asset('img/site/Logo busca.png') }}" alt="logo ong">
        </div>
    </div>

    <!-- Adicione esses scripts no final do body -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        let isStep1 = true;

        function toggleStep() {
            let step1 = document.getElementById('step-1');
            let step2 = document.getElementById('step-2');
            let btn = document.querySelector('.toggle-btn');
            let submitBtn = document.getElementById('submit-btn');

            if (isStep1) {
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
                btn.textContent = "Voltar";
                submitBtn.classList.remove('hidden');
            } else {
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
                btn.textContent = "Próximo";
                submitBtn.classList.add('hidden');
            }
            isStep1 = !isStep1;
        }

        
            $('#cep').blur(function() {
                const cep = $(this).cleanVal();
                if (cep.length === 8) {
                    $.getJSON(`https://viacep.com.br/ws/${cep}/json/`)
                        .done(function(data) {
                            if (!data.erro) {
                                $('#endereco').val(
                                    `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`
                                );
                            }
                        })
                        .fail(function() {
                            console.log('Erro ao buscar CEP');
                        });
                }
            });

            
    </script>

<script>
    // Configuração da validação de senha
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        
        // Criar elemento para mostrar os requisitos
        const passwordRequirements = document.createElement('div');
        passwordRequirements.id = 'password-requirements';
        passwordRequirements.style.marginTop = '10px';
        passwordRequirements.style.color = '#666';
        passwordRequirements.style.fontSize = '14px';
        passwordInput.insertAdjacentElement('afterend', passwordRequirements);

        // Requisitos mínimos da senha
        const requirements = [
            { regex: /.{8,}/, message: 'Mínimo 8 caracteres' },
            { regex: /[A-Z]/, message: 'Pelo menos 1 letra maiúscula' },
            { regex: /[a-z]/, message: 'Pelo menos 1 letra minúscula' },
            { regex: /[0-9]/, message: 'Pelo menos 1 número' },
            { regex: /[^A-Za-z0-9]/, message: 'Pelo menos 1 caractere especial' }
        ];

        // Atualizar feedback visual
        function updatePasswordFeedback() {
            const password = passwordInput.value;
            let feedbackHTML = '<strong>Requisitos da senha:</strong><ul style="margin:5px 0 0 20px;padding:0;">';
            
            requirements.forEach(req => {
                const isValid = req.regex.test(password);
                feedbackHTML += `<li style="color:${isValid ? 'green' : 'red'}">${req.message}</li>`;
            });
            
            feedbackHTML += '</ul>';
            passwordRequirements.innerHTML = feedbackHTML;
            
            // Atualizar confirmação
            updateConfirmationFeedback();
        }

        // Verificar se as senhas coincidem
        function updateConfirmationFeedback() {
            if (!passwordConfirmation.value) return;
            
            const feedback = passwordConfirmation.value === passwordInput.value ?
                '<span style="color:green;">As senhas coincidem!</span>' :
                '<span style="color:red;">As senhas não coincidem!</span>';
            
            // Criar ou atualizar elemento de feedback
            let feedbackElement = document.getElementById('confirmation-feedback');
            if (!feedbackElement) {
                feedbackElement = document.createElement('div');
                feedbackElement.id = 'confirmation-feedback';
                feedbackElement.style.marginTop = '5px';
                feedbackElement.style.fontSize = '14px';
                passwordConfirmation.insertAdjacentElement('afterend', feedbackElement);
            }
            
            feedbackElement.innerHTML = feedback;
        }

        // Validar antes do envio
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            let isValid = true;
            
            // Verificar todos os requisitos
            requirements.forEach(req => {
                if (!req.regex.test(password)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('A senha não atende a todos os requisitos de segurança!');
                passwordInput.focus();
                return;
            }
            
            if (password !== passwordConfirmation.value) {
                e.preventDefault();
                alert('As senhas não coincidem!');
                passwordConfirmation.focus();
            }
        });

        // Event listeners
        passwordInput.addEventListener('input', updatePasswordFeedback);
        passwordConfirmation.addEventListener('input', updateConfirmationFeedback);
    });
</script>

</body>

</html>