@extends('template.main', ['titulo' => 'Dashboard'])

@section('content')
    <h3>Painel</h3>

    @if(Auth::user()->role_id === 2)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">Contas dos clientes</h5>
                <p class="text-muted mb-0">Escolha a conta que deseja bloquear ou desbloquear.</p>
            </div>
        </div>

        <div class="row">
            @forelse($contas as $conta)
                <div class="col-md-6 mb-3">
                    <div class="card border shadow-sm">
                        <div class="card-body">
                            <h5>{{ $conta->cliente->name ?? 'Cliente sem nome' }}</h5>

                            <p class="mb-1">
                                Conta: <strong>{{ $conta->id }}</strong>
                            </p>

                            <p class="mb-1">
                                Saldo: <strong>R$ {{ number_format($conta->saldo, 2, ',', '.') }}</strong>
                            </p>

                            <p>
                                Status:
                                @if($conta->status === 'bloqueada')
                                    <span class="badge bg-danger">Bloqueada</span>
                                @else
                                    <span class="badge bg-success">Ativa</span>
                                @endif
                            </p>

                            @if($conta->status === 'bloqueada')
                                <form action="{{ route('contas.desbloquear', $conta->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success">
                                        Desbloquear
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('contas.bloquear', $conta->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger">
                                        Bloquear
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    Nenhuma conta encontrada.
                </div>
            @endforelse
        </div>
    @else
        <h5>Bem-vindo, {{ Auth::user()->name }}</h5>
        <p>Use o menu acima para acessar as funcoes do sistema.</p>
    @endif
@endsection
