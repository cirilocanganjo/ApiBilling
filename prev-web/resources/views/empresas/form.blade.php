@extends('layouts.app')
@section('title', isset($empresa) ? 'Editar Empresa' : 'Nova Empresa')
@section('content')
<form action="{{ isset($empresa) ? route('empresas.update',$empresa->id) : route('empresas.store') }}" method="POST" novalidate>
    @csrf
    @if(isset($empresa)) @method('PUT') @endif

    <div class="form-group">
        <label>Nome <span class="text-danger">*</span></label>
        <input type="text" name="nome" class="form-control" value="{{ $empresa->nome ?? '' }}" required>
        <div class="invalid-feedback">Informe o nome da empresa.</div>
    </div>

    <div class="form-group">
        <label>NIF</label>
        <input type="text" name="nif" class="form-control" value="{{ $empresa->nif ?? '' }}">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $empresa->email ?? '' }}">
    </div>

    <div class="form-group">
        <label>Telefone</label>
        <input type="text" name="telefone" class="form-control" value="{{ $empresa->telefone ?? '' }}">
    </div>

    <div class="form-group">
        <label>Endereço</label>
        <input type="text" name="endereco" class="form-control" value="{{ $empresa->endereco ?? '' }}">
    </div>

    <div class="form-group">
        <label>Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control" required>
            <option value="ATIVA" {{ (isset($empresa) && $empresa->status=='ATIVA')?'selected':'' }}>ATIVA</option>
            <option value="INATIVA" {{ (isset($empresa) && $empresa->status=='INATIVA')?'selected':'' }}>INATIVA</option>
        </select>
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
