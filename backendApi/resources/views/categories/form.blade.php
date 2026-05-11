@extends('layouts.app')
@section('title', isset($categories) ? 'Editar Categoria' : 'Nova Categoria')
@section('content')
<section class="content">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="box-content">
            <form method="POST"
                action="{{ isset($category)
                      ? route('categories.update', Crypt::encrypt($category->id))
                      : route('categories.store') }}">

              @csrf

              @isset($category)
                  @method('PUT')
              @endisset

              <div class="mb-3">
                  <label>Nome</label>
                  <input type="text" name="name" class="form-control"
                        value="{{ old('name', $category->name ?? '') }}">
              </div>

              <div class="mb-3">
                  <label>Descrição</label>
                  <textarea name="description" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
              </div>

              <button type="submit" class="btn btn-primary">
                  {{ isset($category) ? 'Salvar Alterações' : 'Criar Categoria' }}
              </button>

          </form>

           <!--  <form action="{{ isset($category) ? route('categories.update', encrypt($category->id)) : route('categories.store') }}" method="POST" novalidate>
              @csrf
              @if(isset($category)) @method('PUT') @endif

              <div class="form-group row">
                  <label class="col-sm-3 col-form-label control-label">Nome <span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                      <input type="text" name="name" class="form-control" value="{{ $category->name ?? '' }}" required>
                      <div class="invalid-feedback">O nome da categoria é obrigatório.</div>
                  </div>
              </div>

              <div class="form-group row">
                  <label class="col-sm-3 col-form-label control-label">Descrição</label>
                  <div class="col-sm-6">
                      <textarea name="description" class="form-control">{{ $category->description ?? '' }}</textarea>
                  </div>
              </div>

              <div class="form-group row">
                  <div class="col-sm-6 offset-sm-3">
                      <button type="submit" class="btn btn-lg btn-success">
                          Gravar
                      </button>
                  </div>
              </div>
          </form> -->

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
