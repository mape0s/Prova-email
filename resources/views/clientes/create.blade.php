@extends('template.main', ['titulo' => 'Novo Cliente'])

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

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Saldo inicial</label>
                <input type="number" step="0.01" name="saldo" class="form-control" value="0">
            </div>
            <div class="col mb-3">
                <label class="form-label">Limite inicial</label>
                <input type="number" step="0.01" name="limite" class="form-control" value="0">
            </div>
        </div>
        <button class="btn btn-primary">Salvar</button>
    </form>
@endsection
