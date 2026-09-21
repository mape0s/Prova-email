<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Banco') }}</title>
    @vite('resources/svelte/main.js')
</head>
<body>
    <div id="svelte-app"></div>
</body>
</html>
