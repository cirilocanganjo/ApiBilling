<table class="table table-bordered table-striped">
    <thead>
        <tr>
            @foreach($columns as $col => $label)
                @php
                    $dir = ($sort == $col && $direction == 'asc') ? 'desc' : 'asc';
                @endphp
                <th>
                    <a href="#" class="sort-link" data-column="{{ $col }}" data-direction="{{ $dir }}">
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
        @forelse($clientes as $cliente)
            <tr>
                <td>{{ $cliente->id }}</td>
                <td>{{ $cliente->nome }}</td>
                <td>{{ $cliente->email }}</td>
                <td>{{ $cliente->telefone }}</td>
                <td>{{ $cliente->endereco }}</td>
                <td>
                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns)+1 }}" class="text-center">Nenhum registro encontrado</td>
            </tr>
        @endforelse
    </tbody>
</table>

@if($clientes->lastPage() > 1)
<nav id="pagination">
    <ul class="pagination">
        @for ($i = 1; $i <= $clientes->lastPage(); $i++)
            <li class="page-item {{ $clientes->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link page-link-ajax" href="{{ $clientes->url($i) }}">{{ $i }}</a>
            </li>
        @endfor
    </ul>
</nav>
@endif
