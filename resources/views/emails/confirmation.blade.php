<!DOCTYPE html>
<html>
<head>
    <title>Confirmação de Cadastro</title>
</head>
<body>
    <h1>Olá, {{ $user->name }}!</h1>
    <p>Obrigado por se cadastrar. Clique no link abaixo para confirmar seu cadastro:</p>
    <a href="{{ route('confirmation', ['token' => $user->confirmation_token]) }}">Confirmar Cadastro</a>
</body>
</html>