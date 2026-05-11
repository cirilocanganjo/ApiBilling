@extends('layouts.app')
@section('title','Marcas')
@section('content')
<div class="mb-3">
    <a href="{{ route('marcas.create') }}" class="btn btn-primary">Nova Marca</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($marcas as $marca)
        <tr>
            <td>{{ $marca->id }}</td>
            <td>{{ $marca->nome }}</td>
            <td>
                <a href="{{ route('marcas.edit',$marca->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('marcas.destroy',$marca->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $marcas->links() }}
@endsection
