<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Credenciais IFBANK</title>
</head>
<body>
    <h1>IFBANK</h1>

    <p>Olá, {{ $usuario->name }}.</p>

    <p>Seu acesso ao sistema foi criado.</p>

    <p><strong>Perfil:</strong> {{ $perfil }}</p>
    <p><strong>E-mail:</strong> {{ $usuario->email }}</p>
    <p><strong>Senha inicial:</strong> {{ $senhaTemporaria }}</p>

    <p>Recomendamos alterar a senha após o primeiro acesso.</p>

    <p>Atenciosamente,<br>IFBANK</p>
</body>
</html>
