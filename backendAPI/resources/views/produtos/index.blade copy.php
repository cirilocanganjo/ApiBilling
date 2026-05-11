@extends('layouts.app')
@section('title','Produtos')
@section('content')
<div class="mb-3">
    <a href="{{ route('produtos.create') }}" class="btn btn-primary">Novo Produto</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Marca</th>
            <th>Unidade</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produtos as $produto)
        <tr>
            <td>{{ $produto->id }}</td>
            <td>{{ $produto->nome }}</td>
            <td>{{ $produto->categoria->nome ?? '' }}</td>
            <td>{{ $produto->marca->nome ?? '' }}</td>
            <td>{{ $produto->unidade->sigla ?? '' }}</td>
            <td>{{ number_format($produto->preco,2) }}</td>
            <td>{{ $produto->quantidade }}</td>
            <td>
                <a href="{{ route('produtos.edit',$produto->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('produtos.destroy',$produto->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $produtos->links() }}
@endsection
