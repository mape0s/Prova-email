@extends('template.main', ['titulo' => 'Extrato do Cliente'])

@section('content')
    <h3>{{ $cliente->name }}</h3>
    <p>Saldo: R$ {{ $conta->saldo }} - Status: {{ $conta->status }}</p>

    <table class="table">
        <thead>
            <tr><th>Tipo</th><th>Valor</th><th>Data</th></tr>
        </thead>
        <tbody>
            @forelse($movimentacoes as $m)
                <tr>
                    <td>{{ $m->tipo }}</td>
                    <td>{{ $m->natureza === 'entrada' ? '+' : '-' }} R$ {{ $m->valor }}</td>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Nenhuma movimentacao ainda.</td></tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-secondary">Voltar</a>
@endsection
