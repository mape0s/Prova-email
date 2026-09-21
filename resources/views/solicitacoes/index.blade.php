@extends('template.main', ['titulo' => 'Solicitacoes'])

@section('content')
    <h3>Solicitacoes de limite</h3>

    @if(Auth::user()->role_id === 2)
        <div class="card border shadow-sm mb-4">
            <div class="card-body">
                <h5>Solicitar aumento de limite</h5>

                <form action="{{ route('solicitacoes.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Valor solicitado</label>
                        <input
                            type="number"
                            name="valor_solicitado"
                            step="0.01"
                            min="0.01"
                            class="form-control"
                            placeholder="5000.00"
                            required
                        >
                    </div>

                    <button class="btn btn-primary">
                        Solicitar
                    </button>
                </form>
            </div>
        </div>
    @endif

    @if(Auth::user()->role_id === 1)
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Conta</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitacoes as $solicitacao)
                        <tr>
                            <td>
                                {{ $solicitacao->conta->cliente->name ?? 'Cliente' }}
                            </td>

                            <td>{{ $solicitacao->conta_id }}</td>

                            <td>
                                R$ {{ number_format($solicitacao->valor_solicitado, 2, ',', '.') }}
                            </td>

                            <td>{{ ucfirst($solicitacao->status) }}</td>

                            <td>
                                @if($solicitacao->status === 'pendente')
                                    <form
                                        action="{{ route('solicitacoes.aprovar', $solicitacao->id) }}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        <button class="btn btn-success btn-sm">
                                            Aprovar
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('solicitacoes.reprovar', $solicitacao->id) }}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        <button class="btn btn-danger btn-sm">
                                            Reprovar
                                        </button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                Nenhuma solicitacao encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <h5>Minhas solicitacoes</h5>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitacoes->where('conta.user_id', Auth::id()) as $solicitacao)
                        <tr>
                            <td>
                                R$ {{ number_format($solicitacao->valor_solicitado, 2, ',', '.') }}
                            </td>
                            <td>{{ ucfirst($solicitacao->status) }}</td>
                            <td>{{ $solicitacao->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                Nenhuma solicitacao encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection
