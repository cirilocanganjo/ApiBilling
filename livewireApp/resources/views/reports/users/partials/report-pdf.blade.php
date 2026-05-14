<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 6px;
            border: 1px solid #ccc;
        }
        th {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<h3>Relatório de Utilizadores</h3>

<table>
    <thead>
        <tr>
            <th>Nome Usuário</th>
            <th>Nome Fornecedor</th>
            <th>Email Fornecedor</th>
            <th>Telemóvel Fornecedor</th>
            <th>Endereço Fornecedor</th>
            <th>Data de Cadastro do Fornecedor</th>
        </tr>
    </thead>
    <tbody>
        @if($data->isNotEmpty())
        @foreach ($data as $user)
            <tr>
                <td>{{ $user['stored_by_user']['name'] }}</td>
                <td>{{ $user['name'] ?? '' }}</td>
                <td>{{ $user['email'] ?? ''}}</td>
                <td>{{ $user['phone'] ?? ''}}</td>
                <td>{{ $user['address'] ?? ''}}</td>
                <td>{{ date('d-m-Y H:i', strtotime($user['created_at'])) ?? ''}}</td>
            </tr>
        @endforeach
        @endif
    </tbody>
</table>

</body>
</html>
