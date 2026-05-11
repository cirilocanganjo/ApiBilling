@extends('layouts.app')
@section('title','Empresas')
@section('content')
<div class="mb-3">
    <a href="{{ route('empresas.create') }}" class="btn btn-primary">Nova Empresa</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>NIF</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($empresas as $empresa)
        <tr>
            <td>{{ $empresa->id }}</td>
            <td>{{ $empresa->nome }}</td>
            <td>{{ $empresa->nif }}</td>
            <td>{{ $empresa->email }}</td>
            <td>{{ $empresa->telefone }}</td>
            <td>{{ $empresa->status }}</td>
            <td>
                <a href="{{ route('empresas.edit',$empresa->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('empresas.destroy',$empresa->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $empresas->links() }}
@endsection
