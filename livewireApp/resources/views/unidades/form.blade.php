@extends('layouts.app')
@section('title', isset($unidade) ? 'Editar Unidade' : 'Nova Unidade')
@section('content')
<form action="{{ isset($unidade) ? route('unidades.update',$unidade->id) : route('unidades.store') }}" method="POST" novalidate>
    @csrf
    @if(isset($unidade)) @method('PUT') @endif

    <div class="form-group">
        <label>Nome <span class="text-danger">*</span></label>
        <input type="text" name="nome" class="form-control" value="{{ $unidade->nome ?? '' }}" required>
        <div class="invalid-feedback">O nome da unidade é obrigatório.</div>
    </div>

    <div class="form-group">
        <label>Sigla <span class="text-danger">*</span></label>
        <input type="text" name="sigla" class="form-control" value="{{ $unidade->sigla ?? '' }}" required>
        <div class="invalid-feedback">A sigla é obrigatória.</div>
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
