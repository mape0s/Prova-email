<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>{{ $titulo ?? 'Banco' }}</title>
    <style>
        body { background: #f4f6f9; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .navbar-brand { font-weight: 700; letter-spacing: 0.5px; }
        table th { text-transform: uppercase; font-size: 0.75rem; color: #6c757d; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark" style="background:#1e293b;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Banco</a>

            <ul class="navbar-nav me-auto">
                @if(Auth::user()->role_id === 1)
                    <li class="nav-item"><a class="nav-link" href="{{ route('gerentes.index') }}">Gerentes de Conta</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('solicitacoes.index') }}">Solicitacoes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('auditoria.index') }}">Auditoria</a></li>
                @endif
                @if(Auth::user()->role_id === 2)
                    <li class="nav-item"><a class="nav-link" href="{{ route('clientes.index') }}">Clientes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('solicitacoes.index') }}">Solicitacoes</a></li>
                @endif
            </ul>

            <div class="d-flex align-items-center">
                <span class="text-white me-3">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">Sair</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card">
            <div class="card-body p-4">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
