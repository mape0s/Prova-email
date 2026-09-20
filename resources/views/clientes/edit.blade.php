@extends('template.main', ['titulo' => 'Editar Cliente'])

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $cliente->name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Nova senha (deixe em branco para manter)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button class="btn btn-primary">Salvar</button>
    </form>
@endsection
