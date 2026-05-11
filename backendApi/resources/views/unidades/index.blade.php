@extends('layouts.app')
@section('title','Unidades')
@section('content')
<div class="mb-3">
    <a href="{{ route('unidades.create') }}" class="btn btn-primary">Nova Unidade</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sigla</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($unidades as $uni)
        <tr>
            <td>{{ $uni->id }}</td>
            <td>{{ $uni->nome }}</td>
            <td>{{ $uni->sigla }}</td>
            <td>
                <a href="{{ route('unidades.edit',$uni->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('unidades.destroy',$uni->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $unidades->links() }}
@endsection
