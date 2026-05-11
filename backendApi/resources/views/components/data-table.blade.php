@props([
    'columns',       // array ['campo' => 'Label']
    'data',          // coleção paginada
    'route',         // nome da rota ou URL completa
    'searchField' => 'q', // nome do input de pesquisa
])

@php
    // Determina se $route é URL ou nome de rota
    $isUrl = str_starts_with($route, 'http');
    $routeUrl = $isUrl ? $route : route($route);

    $sort = request('sort', array_key_first($columns));
    $direction = request('direction', 'asc');
@endphp

<div class="mb-3">
    {{-- Formulário de pesquisa --}}
    <form method="GET" action="{{ $routeUrl }}" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="{{ $searchField }}" class="form-control"
                    placeholder="Pesquisar..." value="{{ request($searchField) }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">Pesquisar</button>
            </div>
        </div>
    </form>

    {{-- Tabela --}}
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                @foreach($columns as $col => $label)
                    @php
                        $dir = ($sort == $col && $direction == 'asc') ? 'desc' : 'asc';

                        // Monta a URL para ordenação
                        $queryParams = array_merge(request()->except('page'), [
                            'sort' => $col,
                            'direction' => $dir,
                        ]);

                        $sortUrl = $isUrl
                            ? $routeUrl.'?'.http_build_query($queryParams)
                            : route($route, $queryParams);
                    @endphp
                    <th>
                        <a href="{{ $sortUrl }}">
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
            {{ $slot }} {{-- Linhas injetadas pelo módulo --}}
        </tbody>
    </table>

    {{-- Paginação --}}
    {{ $data->appends(request()->query())->links() }}
</div>
<!-- Paginação por botões -->
@if($data->lastPage() > 1)
<nav>
    <ul class="pagination">
        @for ($i = 1; $i <= $data->lastPage(); $i++)
            <li class="page-item {{ ($data->currentPage() == $i) ? 'active' : '' }}">
                <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
            </li>
        @endfor
    </ul>
</nav>
@endif
