@extends('layouts.app')
@section('title','Categorias')

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
</style>
@endsection

@section('content')
<section class="content">
<div class="container-fluid">

    <!-- Barra de pesquisa e botão -->
    <div class="row clsPaddingBottom">
        <div class="col-sm-12">
            <div class="divSearch d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('categorias.create') }}" class="btn btn-primary mb-2">Novo Categoria</a>
                <input type="text" id="searchBox" class="form-control flex-grow-1" placeholder="Pesquisar por nome, descrição">
            </div>
        </div>         
    </div>

    <!-- Tabela -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box-content">
                <table id="categoriasTable" class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Categorias::all() as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td>{{ $categoria->nome }}</td>
                            <td>{{ $categoria->descricao }}</td>
                            <td>
                                <a href="{{ route('categorias.edit',$categoria->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('categorias.destroy',$categoria->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicialização do DataTable
    var table = $('#categoriasTable').DataTable({
        paging: true,
        info: true,
        searching: true,
        responsive: true,
        autoWidth: false,
        order: [[0, 'asc']],        // Ordena pelo ID por padrão
        columnDefs: [
            { orderable: false, targets: 3 } // Coluna Ações não ordena
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Pesquisar...",
            lengthMenu: "Mostrar _MENU_ registros por página",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            paginate: {
                previous: "Anterior",
                next: "Próximo"
            },
            zeroRecords: "Nenhum registro encontrado"
        }
    });

    // Input externo para pesquisa
    $('#searchBox').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endsection
