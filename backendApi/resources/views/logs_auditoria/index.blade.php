@extends('layouts.app')
@section('title','Logs de Auditoria')
@section('content')
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Empresa</th>
            <th>Usuário</th>
            <th>Tabela</th>
            <th>Ação</th>
            <th>ID do Registro</th>
            <th>Dados Anteriores</th>
            <th>Dados Novos</th>
            <th>IP</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->id }}</td>
            <td>{{ $log->empresa->nome ?? '' }}</td>
            <td>{{ $log->user->nome ?? '' }}</td>
            <td>{{ $log->tabela }}</td>
            <td>{{ $log->acao }}</td>
            <td>{{ $log->registo_id }}</td>
            <td><pre>{{ json_encode($log->dados_anteriores, JSON_PRETTY_PRINT) }}</pre></td>
            <td><pre>{{ json_encode($log->dados_novos, JSON_PRETTY_PRINT) }}</pre></td>
            <td>{{ $log->ip }}</td>
            <td>{{ $log->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $logs->links() }}
@endsection
