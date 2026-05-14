@extends('layouts.app')
@section('title','Movimentos de Estoque')
@section('content')
<div class="mb-3">
    <a href="{{ route('movimentos_estoque.create') }}" class="btn btn-primary">Novo Movimento</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Tipo</th>
            <th>Quantidade</th>
            <th>Motivo</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($movimentos as $mov)
        <tr>
            <td>{{ $mov->id }}</td>
            <td>{{ $mov->produto->nome ?? '' }}</td>
            <td>{{ $mov->tipo }}</td>
            <td>{{ $mov->quantidade }}</td>
            <td>{{ $mov->motivo }}</td>
            <td>{{ $mov->created_at }}</td>
            <td>
                <a href="{{ route('movimentos_estoque.edit',$mov->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('movimentos_estoque.destroy',$mov->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $movimentos->links() }}
@endsection
