@extends('layouts.app')
@section('title','Clientes')
@section('content')

<div class="mb-3">
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">Novo Cliente</a>
</div>

<!-- Campo único de pesquisa -->
<form method="GET" action="{{ route('clientes.index') }}" class="mb-3">
    <div class="row">
        <div class="col-md-6">
            <input type="text" name="q" class="form-control" placeholder="Pesquisar por nome, email ou telefone" value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-block">Pesquisar</button>
        </div>
    </div>
</form>

@php
    // Campos que podem ser ordenados
    $columns = [
        'id' => 'ID',
        'nome' => 'Nome',
        'email' => 'Email',
        'telefone' => 'Telefone',
        'endereco' => 'Endereço'
    ];

    $sort = request('sort', 'id');
    $direction = request('direction', 'asc');
@endphp

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            @foreach($columns as $col => $label)
                @php
                    $dir = ($sort == $col && $direction == 'asc') ? 'desc' : 'asc';
                @endphp
                <th>
                    <a href="{{ route('clientes.index', array_merge(request()->except('page'), ['sort' => $col, 'direction' => $dir])) }}">
                        {{ $label }}
                        @if($sort == $col)
                            @if($direction == 'asc')
                                <i class="fas fa-sort-up"></i>
                            @else
                                <i class="fas fa-sort-down"></i>
                            @endif
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </a>
                </th>
            @endforeach
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $cliente)
        <tr>
            <td>{{ $cliente->id }}</td>
            <td>{{ $cliente->nome }}</td>
            <td>{{ $cliente->email }}</td>
            <td>{{ $cliente->telefone }}</td>
            <td>{{ $cliente->endereco }}</td>
            <td>
                <a href="{{ route('clientes.edit',$cliente->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('clientes.destroy',$cliente->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $clientes->appends(request()->query())->links() }}

@endsection

