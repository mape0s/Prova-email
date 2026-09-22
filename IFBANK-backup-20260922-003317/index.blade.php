@extends('template.main', ['titulo' => 'Solicitacoes'])

@section('content')
    <h3>Solicitacoes de limite</h3>

    @if(Auth::user()->role_id === 2)
        <div class="card border shadow-sm mb-4">
            <div class="card-body">
                <h5>Solicitar aumento de limite para cliente</h5>
                <p class="text-muted mb-3">
                    Selecione um cliente da sua carteira e informe o novo limite solicitado.
                </p>

                <form action="{{ route('solicitacoes.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select name="conta_id" class="form-select" required>
                            <option value="">Selecione o cliente</option>
                            @foreach($clientes as $conta)
                                <option value="{{ $conta->id }}">
                                    {{ $conta->cliente->name }} — limite atual:
                                    R$ {{ number_format($conta->limite, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Novo limite solicitado</label>
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

                    <button class="btn btn-primary" {{ $clientes->isEmpty() ? 'disabled' : '' }}>
                        Enviar para aprovação
                    </button>
                </form>
            </div>
        </div>

        <h5>Solicitacoes da minha carteira</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Conta</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitacoes as $solicitacao)
                        <tr>
                            <td>{{ $solicitacao->conta->cliente->name ?? 'Cliente' }}</td>
                            <td>{{ $solicitacao->conta_id }}</td>
                            <td>R$ {{ number_format($solicitacao->valor_solicitado, 2, ',', '.') }}</td>
                            <td>{{ ucfirst($solicitacao->status) }}</td>
                            <td>{{ $solicitacao->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhuma solicitacao encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif(Auth::user()->role_id === 1)
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Conta</th>
                        <th>Gerente de Conta</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitacoes as $solicitacao)
                        <tr>
                            <td>{{ $solicitacao->conta->cliente->name ?? 'Cliente' }}</td>
                            <td>{{ $solicitacao->conta_id }}</td>
                            <td>{{ $solicitacao->conta->gerenteConta->name ?? '—' }}</td>
                            <td>R$ {{ number_format($solicitacao->valor_solicitado, 2, ',', '.') }}</td>
                            <td>{{ ucfirst($solicitacao->status) }}</td>
                            <td>
                                @if($solicitacao->status === 'pendente')
                                    <form action="{{ route('solicitacoes.aprovar', $solicitacao->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Aprovar</button>
                                    </form>
                                    <form action="{{ route('solicitacoes.reprovar', $solicitacao->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">Reprovar</button>
                                    </form>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhuma solicitacao encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection
