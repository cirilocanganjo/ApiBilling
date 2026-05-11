@extends('layouts.app')
@section('title','Produtos')

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
#produtosTable_length, #produtosTable_info {
    text-align: left !important;
}
#produtosTable_length label {
    display: flex;
    align-items: center;
    gap: 6px;
}
td small {
    font-size: 0.85em;
    color: #6c757d;
}
.img-thumb {
    width: 60px;      
    height: 60px;     
    object-fit: cover; 
    border-radius: 5px;
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
                <a href="{{ route('produtos.create') }}" class="btn btn-primary mb-2">Novo Produto</a>
                <form method="GET" action="{{ route('produtos.index') }}" class="mb-3 flex-grow-1">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" name="q" class="form-control" placeholder="Pesquisar por nome, código, EAN" value="{{ request('q') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">Pesquisar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box-content">
                <table id="produtosTable" class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Marca / Unidade</th>
                            <th>Preço / Estoque</th>
                            <th class="no-sort">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($produtos as $produto)
                        <tr class="{{ !$produto->is_inativo ? 'text-danger' : '' }}">
                            <!-- Coluna de Imagem -->
                            <td>
                                @if($produto->imagem)
                                    <img src="{{ asset('storage/produtos/'.$produto->imagem) }}" alt="Imagem do Produto" class="img-thumb">
                                @else
                                    <div class="img-thumb d-flex justify-content-center align-items-center" style="background:#f0f0f0;">📷</div>
                                @endif
                            </td>

                            <!-- Nome + EAN / Código -->
                            <td>
                                <strong>{{ $produto->nome }}</strong><br>
                                <small>{{ $produto->ean_gtin ?? $produto->codigo ?? '' }}</small>
                            </td>
                            <!-- Categoria / Subcategoria -->
                            <td>
                                {{ $produto->categoria->nome ?? '' }}<br>
                                <small>{{ $produto->subcategoria->nome ?? '' }}</small>
                            </td>
                            <!-- Marca / Unidade -->
                            <td>
                                {{ $produto->marca->nome ?? '' }}<br>
                                <small>{{ $produto->unidade->sigla ?? '' }}</small>
                            </td>
                            <!-- Preço / Estoque -->
                            <td>
                                <!-- <span class="text-success">{{ number_format($produto->preco_venda,2) }} €</span><br> -->
                                {{ number_format($produto->preco_venda,2) }} Akz<br>
                                <small>Estoque: {{ $produto->estoque_atual ?? 0 }}</small>
                            </td>
                            <td>
                                <a href="{{ route('produtos.edit',$produto->id) }}" class="btn btn-sm btn-warning mb-1">Editar</a>
                                <form action="{{ route('produtos.destroy',$produto->id) }}" method="POST" class="d-inline">
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
    $('#produtosTable').DataTable({
        paging: true,
        info: true,
        searching: false,
        responsive: true,
        autoWidth: false,
        pagingType: "simple_numbers",
        order: [], // sem ordenação inicial
        columnDefs: [
            { orderable: false, targets: [0,5] } // Coluna Imagem e Ações não ordena
        ],
        language: {
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            paginate: {
                previous: "Anterior",
                next: "Próximo"
            },
            zeroRecords: "Nenhum registro encontrado"
        },
        initComplete: function () {
            $('.dataTables_length select')
                .removeClass()
                .addClass('form-control form-control-sm');
        },
        drawCallback: function(settings) {
            var pages = this.api().page.info().pages;
            if (pages <= 1) $('.dataTables_paginate').hide();
            else $('.dataTables_paginate').show();
        }
    });
});
</script>
@endsection
