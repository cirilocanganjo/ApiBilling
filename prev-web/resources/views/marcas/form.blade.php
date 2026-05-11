@extends('layouts.app')
@section('title', isset($marca) ? 'Editar Marca' : 'Nova Marca')
@section('content')
<form action="{{ isset($marca) ? route('marcas.update',$marca->id) : route('marcas.store') }}" method="POST" novalidate>
    @csrf
    @if(isset($marca)) @method('PUT') @endif

    <div class="form-group">
        <label>Nome <span class="text-danger">*</span></label>
        <input type="text" name="nome" class="form-control" value="{{ $marca->nome ?? '' }}" required>
        <div class="invalid-feedback">O nome da marca é obrigatório.</div>
    </div>

    <button class="btn btn-success">Salvar</button>
</form>

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
