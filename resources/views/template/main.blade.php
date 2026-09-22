<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>{{ $titulo ?? 'IFBANK' }}</title>
    <style>
        body { background:#f4f6f9; }
        .navbar-brand { font-weight:700; letter-spacing:.5px; }
        table th { text-transform:uppercase; font-size:.75rem; color:#6c757d; }
        .role-badge { font-size:.72rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark" style="background:#1e293b;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">IFBANK</a>

            <ul class="navbar-nav me-auto">
                @if(Auth::user()->role_id === 1)
                    <li class="nav-item"><a class="nav-link" href="{{ route('gerentes.index') }}">Gerentes de Conta</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('solicitacoes.index') }}">Solicitacoes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('auditoria.index') }}">Auditoria</a></li>
                @elseif(Auth::user()->role_id === 2)
                    <li class="nav-item"><a class="nav-link" href="{{ route('clientes.index') }}">Clientes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('solicitacoes.index') }}">Solicitacoes</a></li>
                @endif
            </ul>

            <div class="d-flex align-items-center">
                <div class="text-end me-3 text-white">
                    <div>{{ Auth::user()->name }}</div>
                    <span class="badge text-bg-secondary role-badge">
                        {{ Auth::user()->role_id === 1 ? 'Gerente Geral' : 'Gerente de Conta' }}
                    </span>
                </div>
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

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
