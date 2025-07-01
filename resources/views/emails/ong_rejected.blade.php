<!DOCTYPE html>
<html>
<head>
    <title>Cadastro Rejeitado - Busca Pet</title>
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
        .rejected-badge {
            background-color: #e53e3e;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .details-box {
            background-color: #fff5f5;
            border-left: 4px solid #e53e3e;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #e53e3e;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #c53030;
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
        ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo/Cabeçalho -->
        
        
        <h1 class="header">Olá, {{ $ong->nomeOng }}</h1>
        
        <div class="rejected-badge">CADASTRO REJEITADO</div>
        
        <p>Lamentamos informar que seu cadastro como ONG no <strong>Busca Pet</strong> não foi aprovado.</p>
        
        <div class="details-box">
            <h3 style="margin-top: 0;">Detalhes da rejeição:</h3>
            <p><strong>Motivo:</strong> {{ $motivo }}</p>
            <p><strong>Data da decisão:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        
        <h3>O que você pode fazer agora:</h3>
        <ul>
            <li>Corrigir as informações conforme o motivo indicado</li>
            <li>Realizar um novo cadastro quando estiver tudo adequado</li>
            <li>Entrar em contato conosco para mais esclarecimentos</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ url('/ong/cadastro') }}" class="button">
                REALIZAR NOVO CADASTRO
            </a>
        </div>
        
        <p>Caso precise de ajuda com o processo de cadastro, temos um guia completo disponível em nosso site.</p>
        
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