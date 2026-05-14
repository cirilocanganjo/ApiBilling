@extends('layouts.app')
@section('title','Itens da Venda')
@section('content')
<h4>Venda #{{ $venda->id }} - Cliente: {{ $venda->cliente->nome ?? '' }}</h4>
<div class="mb-3">
    <a href="{{ route('venda_itens.create',$venda->id) }}" class="btn btn-primary">Adicionar Item</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Subtotal</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venda_itens as $item)
        <tr>
            <td>{{ $item->produto->nome ?? '' }}</td>
            <td>{{ $item->quantidade }}</td>
            <td>{{ number_format($item->preco_unitario,2) }}</td>
            <td>{{ number_format($item->subtotal,2) }}</td>
            <td>
                <a href="{{ route('venda_itens.edit', $item->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('venda_itens.destroy',$item->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este item?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
