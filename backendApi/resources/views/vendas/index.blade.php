@extends('layouts.app')
@section('title','Vendas')
@section('content')
<div class="mb-3">
    <a href="{{ route('vendas.create') }}" class="btn btn-primary">Nova Venda</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Usuário</th>
            <th>Data</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vendas as $venda)
        <tr>
            <td>{{ $venda->id }}</td>
            <td>{{ $venda->cliente->nome ?? '' }}</td>
            <td>{{ $venda->usuario->nome ?? '' }}</td>
            <td>{{ $venda->data_venda }}</td>
            <td>{{ number_format($venda->total,2) }}</td>
            <td>{{ $venda->estado }}</td>
            <td>
                <a href="{{ route('vendas.show',$venda->id) }}" class="btn btn-sm btn-info">Detalhes</a>
                <a href="{{ route('vendas.edit',$venda->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('vendas.destroy',$venda->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $vendas->links() }}
@endsection
