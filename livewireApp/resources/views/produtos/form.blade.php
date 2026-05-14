@extends('layouts.app')
@section('title', isset($produto) ? 'Editar Produto' : 'Novo Produto')

@section('styles')
<style>
.box-content {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.image-box {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 10px;
    min-height: 180px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}
.image-box img {
    max-width: 100%;
    max-height: 150px;
}
.btn-upload {
    display: inline-block;
    margin-top: 5px;
}
</style>
@endsection

@section('content')
<section class="content">
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="box-content">
                <form action="{{ isset($produto) ? route('produtos.update', $produto->id) : route('produtos.store') }}" 
                      method="POST" 
                      novalidate 
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($produto)) @method('PUT') @endif

                    <div class="row">
                        <!-- COLUNA ESQUERDA: CAMPOS -->
                        <div class="col-md-8">

                            <!-- Nome -->
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Nome <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" name="nome" class="form-control" value="{{ $produto->nome ?? '' }}" required>
                                </div>
                            </div>

                            <!-- Empresa e Categoria -->
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Empresa</label>
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

                                <label class="col-md-2 col-form-label">Categoria</label>
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

                            <!-- Subcategoria e Marca -->
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Subcategoria</label>
                                <div class="col-md-4">
                                    <select name="subcategoria_id" class="form-control">
                                        <option value="">Selecione</option>
                                        @foreach($subcategorias as $sub)
                                            <option value="{{ $sub->id }}" {{ (isset($produto) && $produto->subcategoria_id == $sub->id) ? 'selected' : '' }}>
                                                {{ $sub->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <label class="col-md-2 col-form-label">Marca</label>
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
                            </div>

                            <!-- Preço e Markup -->
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Preço de Venda</label>
                                <div class="col-md-4">
                                    <input type="text" name="preco_venda" class="form-control" value="{{ $produto->preco_venda ?? '' }}">
                                </div>
                                <div class="col-md-6 d-flex align-items-center gap-2">
                                    <input type="checkbox" name="preco_automatico" {{ isset($produto) && $produto->preco_automatico ? 'checked' : '' }}> Automático
                                    <input type="checkbox" name="preco_alteravel" {{ isset($produto) && $produto->preco_alteravel ? 'checked' : '' }}> Preço alterável
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Preço de Custo</label>
                                <div class="col-md-4">
                                    <input type="text" name="preco_custo" class="form-control" value="{{ $produto->preco_custo ?? '' }}">
                                </div>

                                <label class="col-md-2 col-form-label">Markup %</label>
                                <div class="col-md-4">
                                    <input type="text" name="markup_percent" class="form-control" value="{{ $produto->markup_percent ?? 30 }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input type="checkbox" name="seguir_markup" {{ isset($produto) && $produto->seguir_markup ? 'checked' : '' }}>
                                    Seguir Markup Padrão
                                </div>
                            </div>

                            <hr>

                            <!-- Estoque -->
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <input type="checkbox" name="controlar_estoque" {{ isset($produto) && $produto->controlar_estoque ? 'checked' : '' }}>
                                    Controlar Estoque
                                </div>
                                <div class="col-md-4">
                                    <label>Estoque Atual</label>
                                    <input type="number" name="estoque_atual" class="form-control" value="{{ $produto->estoque_atual ?? 0 }}">
                                </div>

                                <div class="col-md-6">
                                    <label>Limite de Estoque</label>
                                    <select name="limite_estoque_id" class="form-control">
                                        <option value="">Selecione</option>
                                        @foreach($limites_estoque as $limite)
                                            <option value="{{ $limite->id }}" {{ (isset($produto) && $produto->limite_estoque_id == $limite->id) ? 'selected' : '' }}>
                                                {{ $limite->descricao }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Unidade</label>
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
                                <div class="col-md-6 d-flex align-items-center">
                                    <input type="checkbox" name="permite_fracionamento" {{ isset($produto) && $produto->permite_fracionamento ? 'checked' : '' }}>
                                    Permite fracionamento
                                </div>
                            </div>

                            <!-- Produto Inativo -->
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input type="checkbox" name="is_inativo" {{ isset($produto) && $produto->is_inativo ? 'checked' : '' }}>
                                    Produto Inativo
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Observações</label>
                                <div class="col-md-10">
                                    <textarea name="observacoes" class="form-control" rows="3">{{ $produto->observacoes ?? '' }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-lg btn-success">Salvar</button>
                        </div>

                        <!-- COLUNA DIREITA: IMAGEM -->
                        <div class="col-md-4">
                            <div class="image-box text-center border mb-2" id="preview-container">
                                @if(isset($produto) && $produto->imagem)
                                    <img src="{{ asset('storage/produtos/'.$produto->imagem) }}" alt="Imagem do Produto" id="preview">
                                @else
                                    <div id="placeholder" style="font-size: 32px;">📷</div>
                                @endif
                            </div>

                            <!-- BOTÃO CUSTOMIZADO -->
                            <input type="file" name="imagem" id="imagem" accept="image/*" style="display:none;">
                            <button type="button" class="btn btn-primary btn-upload" id="btnUpload">Escolher Imagem</button>
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

  // Validação do formulário
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

  // BOTÃO CUSTOMIZADO E PREVIEW DE IMAGEM
  const inputImagem = document.getElementById('imagem');
  const previewContainer = document.getElementById('preview-container');
  const btnUpload = document.getElementById('btnUpload');

  btnUpload.addEventListener('click', function() {
      inputImagem.click();
  });

  inputImagem.addEventListener('change', function() {
      const file = this.files[0];
      if(file) {
          const reader = new FileReader();
          reader.onload = function(e) {
              if(document.getElementById('preview')){
                  document.getElementById('preview').src = e.target.result;
              } else {
                  document.getElementById('placeholder')?.remove();
                  const img = document.createElement('img');
                  img.id = 'preview';
                  img.src = e.target.result;
                  previewContainer.appendChild(img);
              }
          }
          reader.readAsDataURL(file);
      }
  });

})();
</script>
@endpush
@endsection
