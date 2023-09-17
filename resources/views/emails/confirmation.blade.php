<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Confirmação de cadastro</title>
  <style>
    body {
      font-family: sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
    }

    .header {
      background-color: #000;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    .content {
      background-color: #fff;
      padding: 20px;
    }

    .title {
      font-size: 24px;
      font-weight: bold;
    }

    .text {
      font-size: 16px;
    }

    .link {
      color: #000;
      font-weight: bold;
      text-decoration: none;
    }

    .footer {
      background-color: #000;
      color: #fff;
      padding: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Confirmação de cadastro</h1>
    </div>
    <div class="content">
      <h2 class="title">Obrigado por se cadastrar!</h2>
      <p class="text">Para confirmar seu cadastro, clique no link abaixo:</p>
      <a href="{{ route('confirmation', ['token' => $user->confirmation_token]) }}" class="link">Confirmar</a>
    </div>
    <div class="footer">
      <p class="text">Copyright © 2023 Example Inc.</p>
    </div>
  </div>
</body>
</html>
