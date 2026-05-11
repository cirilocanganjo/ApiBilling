@extends('layouts.app')
@section('title','Caixas')
@section('content')
<div class="mb-3">
    <a href="{{ route('caixas.create') }}" class="btn btn-primary">Abrir Caixa</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Saldo Inicial</th>
            <th>Saldo Final</th>
            <th>Status</th>
            <th>Abertura</th>
            <th>Fecho</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($caixas as $caixa)
        <tr>
            <td>{{ $caixa->id }}</td>
            <td>{{ $caixa->usuario->nome ?? '' }}</td>
            <td>{{ number_format($caixa->saldo_inicial,2) }}</td>
            <td>{{ number_format($caixa->saldo_final,2) }}</td>
            <td>{{ $caixa->status }}</td>
            <td>{{ $caixa->data_abertura }}</td>
            <td>{{ $caixa->data_fecho }}</td>
            <td>
                <a href="{{ route('caixas.edit',$caixa->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('caixas.destroy',$caixa->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $caixas->links() }}
@endsection
