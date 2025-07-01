<!DOCTYPE html>
<html>
<head>
    <title>Cadastro Aprovado - Busca Pet</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container { 
            max-width: 600px; 
            margin: 20px auto; 
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo {
            max-width: 180px;
            height: auto;
        }
        .header { 
            color: #2d3748; 
            font-size: 24px; 
            margin-bottom: 20px;
            text-align: center;
        }
        .success-badge {
            background-color: #48bb78;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .details-box {
            background-color: #f0fff4;
            border-left: 4px solid #48bb78;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #48bb78;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .footer { 
            margin-top: 40px; 
            font-size: 14px; 
            color: #718096;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        .contact-info {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo/Cabeçalho -->
        
        
        <h1 class="header">Parabéns, {{ $ong->nomeOng }}!</h1>
        
        <div class="success-badge">CADASTRO APROVADO</div>
        
        <p>É com grande satisfação que informamos que seu cadastro como ONG no <strong>Busca Pet</strong> foi aprovado com sucesso!</p>
        
        <div class="details-box">
            <h3 style="margin-top: 0;">Detalhes do cadastro:</h3>
            <p><strong>CNPJ:</strong> {{ $ong->cnpjOng }}</p>
            <p><strong>E-mail:</strong> {{ $ong->emailOng }}</p>
            <p><strong>Data de aprovação:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        
        <p>Agora você pode acessar sua conta e começar a utilizar todos os recursos disponíveis em nossa plataforma:</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/ong/login') }}" class="button">
                ACESSAR MINHA CONTA
            </a>
        </div>
        
        <p>Se precisar de ajuda para começar, temos tutoriais e materiais disponíveis em nosso portal.</p>
        
        <div class="footer">
            <p><strong>Equipe Busca Pet</strong></p>
            
            <div class="contact-info">
                <p>📞 (11) 1234-5678 | ✉️ suporte@buscapet.com.br</p>
                <p>Horário de atendimento: Seg-Sex, 9h às 18h</p>
            </div>
            
            <p style="margin-top: 20px;">© {{ date('Y') }} Busca Pet - Todos os direitos reservados</p>
        </div>
    </div>
</body>
</html>