@extends('template.main', ['titulo' => 'Gerentes de Conta'])

@section('content')
    <a href="{{ route('gerentes.create') }}" class="btn btn-primary btn-sm mb-3">Novo Gerente</a>

    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($gerentes as $gerente)
                <tr>
                    <td>{{ $gerente->name }}</td>
                    <td>{{ $gerente->email }}</td>
                    <td>
                        <a href="{{ route('gerentes.edit', $gerente->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                        <form action="{{ route('gerentes.destroy', $gerente->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">Remover</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
