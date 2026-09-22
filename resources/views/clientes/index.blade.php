@extends('template.main', ['titulo' => 'Clientes'])

@section('content')
    <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm mb-3">Novo Cliente</a>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Status da conta</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                @php($conta = $cliente->conta)

                <tr>
                    <td>{{ $cliente->name }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>
                        @if($conta?->status === 'bloqueada')
                            <span class="badge bg-danger">Bloqueada</span>
                        @else
                            <span class="badge bg-success">Ativa</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('clientes.extrato', $cliente->id) }}" class="btn btn-sm btn-outline-primary">Extrato</a>
                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>

                        @if($conta)
                            @if($conta->status === 'bloqueada')
                                <form action="{{ route('contas.desbloquear', $conta->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success">Desbloquear</button>
                                </form>
                            @else
                                <form action="{{ route('contas.bloquear', $conta->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-warning">Bloquear</button>
                                </form>
                            @endif
                        @endif

                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">Remover</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
