@extends('template.main', ['titulo' => 'Auditoria'])

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Acao</th>
                <th>Usuario</th>
                <th>Conta</th>
                <th>Data</th>
                <th>Antes</th>
                <th>Depois</th>
            </tr>
        </thead>
        <tbody>
            @forelse($audits as $audit)
                <tr>
                    <td>{{ strtoupper($audit->event) }}</td>
                    <td>{{ $audit->user->name ?? 'Sistema' }}</td>
                    <td>#{{ $audit->auditable_id }}</td>
                    <td>{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @foreach($audit->old_values as $campo => $valor)
                            <div>{{ $campo }}: {{ $valor }}</div>
                        @endforeach
                    </td>
                    <td>
                        @foreach($audit->new_values as $campo => $valor)
                            <div>{{ $campo }}: {{ $valor }}</div>
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Nenhum registro ainda. Bloqueia/desbloqueia uma conta para gerar um log.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
