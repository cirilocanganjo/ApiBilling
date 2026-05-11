@extends('layouts.app')
@section('title','Pagamentos')
@section('content')
<h4>Venda #{{ $venda->id }} - Cliente: {{ $venda->cliente->nome ?? '' }}</h4>
<div class="mb-3">
    <a href="{{ route('pagamentos.create',$venda->id) }}" class="btn btn-primary">Registrar Pagamento</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Metodo</th>
            <th>Valor</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pagamentos as $pag)
        <tr>
            <td>{{ $pag->metodo }}</td>
            <td>{{ number_format($pag->valor,2) }}</td>
            <td>{{ $pag->data_pagamento }}</td>
            <td>
                <form action="{{ route('pagamentos.destroy',$pag->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este pagamento?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
