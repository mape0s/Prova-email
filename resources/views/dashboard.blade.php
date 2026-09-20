@extends('template.main', ['titulo' => 'Dashboard'])

@section('content')
    <h3>Painel</h3>

    <p>Conta de teste (id 1)</p>

    <form action="{{ route('contas.bloquear', 1) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-danger btn-sm">Bloquear</button>
    </form>

    <form action="{{ route('contas.desbloquear', 1) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-success btn-sm">Desbloquear</button>
    </form>
@endsection
