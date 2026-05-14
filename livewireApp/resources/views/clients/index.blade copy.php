@extends('layouts.app')
@section('title','Clientes')
@section('content')

<div class="mb-3">
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">Novo Cliente</a>
</div>

<!-- Filtro de pesquisa -->
<form method="GET" action="{{ route('clientes.index') }}" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <input type="text" name="nome" class="form-control" placeholder="Nome" value="{{ request('nome') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="email" class="form-control" placeholder="Email" value="{{ request('email') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="telefone" class="form-control" placeholder="Telefone" value="{{ request('telefone') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary btn-block">Pesquisar</button>
        </div>
    </div>
</form>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Endereço</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
        <tr>
            <td>{{ $cliente->id }}</td>
            <td>{{ $cliente->nome }}</td>
            <td>{{ $cliente->email }}</td>
            <td>{{ $cliente->telefone }}</td>
            <td>{{ $cliente->endereco }}</td>
            <td>
                <a href="{{ route('clientes.edit',$cliente->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('clientes.destroy',$cliente->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $clientes->appends(request()->query())->links() }}

@endsection
