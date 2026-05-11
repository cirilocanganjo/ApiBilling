@extends('layouts.app')
@section('title','Usuários')
@section('content')
<div class="mb-3">
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Novo Usuário</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Empresa</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Status</th>
            <th>Último Login</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($usuarios as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->empresa->nome ?? '' }}</td>
            <td>{{ $user->nome }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->tipo }}</td>
            <td>{{ $user->status }}</td>
            <td>{{ $user->ultimo_login }}</td>
            <td>
                <a href="{{ route('usuarios.edit',$user->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('usuarios.destroy',$user->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $usuarios->links() }}
@endsection
