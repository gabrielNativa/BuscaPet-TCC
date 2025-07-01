<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Código de Verificação - BuscaPet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 0 0 5px 5px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #e8f5e9;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BuscaPet</h1>
        <p>Código de Verificação</p>
    </div>
    <div class="content">
        <p>Olá,</p>
        <p>Seu código de verificação para acessar o BuscaPet é:</p>
        <div class="code">{{ $code }}</div>
        <p>Este código é válido por 10 minutos. Se você não solicitou este código, por favor ignore este email.</p>
        <p>Atenciosamente,<br>Equipe BuscaPet</p>
    </div>
    <div class="footer">
        © {{ date('Y') }} BuscaPet. Todos os direitos reservados.
    </div>
</body>
</html>