@extends('layouts.app')
@section('title', isset($produto) ? 'Editar Produto' : 'Novo Produto')
@section('content')
<section class="content">
<div class="container-fluid">
    <!-- Tabela -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box-content">
            <form action="{{ isset($produto) ? route('produtos.update', $produto->id) : route('produtos.store') }}" method="POST" novalidate>
                @csrf
                @if(isset($produto)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-8">

                        <!-- Nome -->
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label control-label">Nome <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="nome" class="form-control" value="{{ $produto->nome ?? '' }}" required>
                                <div class="invalid-feedback">O nome do produto é obrigatório.</div>
                            </div>
                        </div>

                        <!-- Código de barras -->
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label control-label">Código de Barras</label>
                            <div class="col-md-4">
                                <input type="text" name="codigo_barras" class="form-control" value="{{ $produto->codigo_barras ?? '' }}">
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <input type="checkbox" name="is_deleted" class="mr-2" {{ isset($produto) && $produto->is_deleted ? 'checked' : '' }}>
                                Produto inativo
                            </div>
                        </div>

                        <!-- Empresa -->
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label control-label">Empresa</label>
                            <div class="col-md-4">
                                <select name="empresa_id" class="form-control">
                                    <option value="">Selecione</option>
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{ (isset($produto) && $produto->empresa_id == $empresa->id) ? 'selected' : '' }}>
                                            {{ $empresa->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Categoria -->
                            <label class="col-md-2 col-form-label control-label">Categoria</label>
                            <div class="col-md-4">
                                <select name="categoria_id" class="form-control">
                                    <option value="">Selecione</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ (isset($produto) && $produto->categoria_id == $categoria->id) ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Marca e Unidade -->
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label control-label">Marca</label>
                            <div class="col-md-4">
                                <select name="marca_id" class="form-control">
                                    <option value="">Selecione</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ (isset($produto) && $produto->marca_id == $marca->id) ? 'selected' : '' }}>
                                            {{ $marca->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="col-md-2 col-form-label control-label">Unidade</label>
                            <div class="col-md-4">
                                <select name="unidade_id" class="form-control">
                                    <option value="">Selecione</option>
                                    @foreach($unidades as $unidade)
                                        <option value="{{ $unidade->id }}" {{ (isset($produto) && $produto->unidade_id == $unidade->id) ? 'selected' : '' }}>
                                            {{ $unidade->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Preço e Quantidade -->
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label control-label">Preço</label>
                            <div class="col-md-4">
                                <input type="text" name="preco" class="form-control" value="{{ $produto->preco ?? '' }}">
                            </div>

                            <label class="col-md-2 col-form-label control-label">Quantidade</label>
                            <div class="col-md-4">
                                <input type="number" name="quantidade" class="form-control" value="{{ $produto->quantidade ?? 0 }}">
                            </div>
                        </div>

                        <!-- Botão salvar -->
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-2">
                                <button type="submit" class="btn btn-lg btn-success">Salvar</button>
                            </div>
                        </div>

                    </div> <!-- col-md-8 -->

                    <!-- Coluna direita (imagem ou observações) -->
                    <div class="col-md-4">
                        <div class="image-box text-center border p-3">
                            <div style="font-size: 32px;">📷</div>
                            <small>Adicionar imagem</small>
                        </div>
                        <a href="#" class="mt-2 d-block">Adicionar Observação – F4</a>
                    </div>

                </div>
            </form>

            </div>
        </div>
    </div>
</div>
</section>


@push('scripts')
<script>
(function() {
  'use strict';
  var forms = document.querySelectorAll('form');
  Array.prototype.slice.call(forms).forEach(function(form) {
    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
@endpush
@endsection
