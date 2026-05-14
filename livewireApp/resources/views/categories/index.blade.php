@extends('layouts.app')
@section('title','Categories')

@section('styles')
<style>
.box-content {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.divSearch {
    background-color: #FAFAFA;
    padding: 15px;
    border: 1px solid #DDDDDD;
    margin-bottom: 15px;
}
#categoriasTable_length, #categoriasTable_info {
    text-align: left !important;
}
#categoriasTable_length label {
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>
@endsection

@section('content')
<section class="content">
<div class="container-fluid">

    <!-- Barra de pesquisa e botão -->
<!--     <div class="row clsPaddingBottom">
        <div class="col-sm-12">
            <div class="divSearch d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('categories.create') }}" class="btn btn-primary mb-2">Novo Categoria</a>
                <form method="GET" action="{{ route('categories.index') }}" class="mb-3 d-flex gap-2">
                    <input type="text" name="q" class="form-control" placeholder="Pesquisar por nome, descrição" value="{{ request('q') }}">
                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                </form>
            </div>
        </div>
    </div> -->
<!-- Pesquisa / Filtros -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm" style="background-color: #ffffff;">
            <div class="card-body">
            <form method="GET" action="{{ route('categories.index') }}">
                <div class="row g-3 align-items-center">
                    <!-- Pesquisa única -->
                    <div class="col-md-6 col-12 d-flex flex-wrap align-items-center mb-2 mb-md-0">
                        <!-- <label for="searchInput" class="col-form-label me-3 mb-2 mb-md-0 clsPaddingRigthLabelForInput">Search</label> -->
                        <div class="flex-grow-1">
                            <div class="input-group">
                                <input type="text" id="searchInput" name="q" class="form-control"
                                    placeholder="Pesquisar por nome, descrição" value="{{ request('q') }}">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="this.closest('.input-group').querySelector('input[name=q]').value=''">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Botão -->
                    <div class="col-md-2 col-12 d-flex justify-content-md-start justify-content-end">
                        <button type="submit" class="btn btn-info" style="min-width: 120px;"><i class="fa fa-search"></i> Pesquisar</button>
                    </div>

                </div>
            </form>

            </div>
        </div>
    </div>
</div>
    <!-- Tabela -->
    <div class="row">
        <div class="col-sm-12">          
            <div class="box-content">
                <form id="lengthForm" method="GET" class="mb-3 d-inline">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <label style="font-weight: 1 !important;">Mostrar
                        <select name="numberlineshow" id="numberlineshow" class="form-control form-control-sm d-inline" style="width: auto;">
                            <option value="10" {{ request('numberlineshow',10)==10?'selected':'' }}>10</option>
                            <option value="25" {{ request('numberlineshow',10)==25?'selected':'' }}>25</option>
                            <option value="50" {{ request('numberlineshow',10)==50?'selected':'' }}>50</option>
                            <option value="100" {{ request('numberlineshow',10)==100?'selected':'' }}>100</option>
                        </select> registros
                    </label>
                </form>
                <table id="categoriasTable" class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th class="no-sort">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <a href="{{ route('categories.edit', encrypt($category->id)) }}" class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <form action="{{ route('categories.destroy', encrypt($category->id)) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">
                                    Apagar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                       <!--  @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <a href="{{ route('categories.edit',$category->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('categories.destroy',$category->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach -->
                    </tbody>
                </table>
                <div class="mt-2 text-muted">
                    Mostrando de {{ $categories->firstItem() }} até {{ $categories->lastItem() }}
                    de {{ $categories->total() }} registros
                </div>

                <!-- Paginação Laravel -->
                <div class="mt-2">
                    {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

</div>
</section>
@endsection

@section('scripts')
<script>
    // Intercepta mudança do select do DataTables e envia para o Laravel
/*     $('#categoriasTable_length select').on('change', function() {
        var perPage = $(this).val();
        var currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('categoriasTable_length', perPage);
        window.location.href = currentUrl.toString();
    });

    // Ajusta select do DataTables para refletir o valor atual do Laravel
    $('#categoriasTable_length select').val('{{ request("categoriasTable_length", 10) }}');
 */
  
</script>
@endsection
