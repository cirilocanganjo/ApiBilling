@extends('layouts.app')
@section('title', isset($usuario) ? 'Editar Usuário' : 'Novo Usuário')
@section('content')
<form action="{{ isset($usuario) ? route('usuarios.update',$usuario->id) : route('usuarios.store') }}" method="POST" novalidate>
    @csrf
    @if(isset($usuario)) @method('PUT') @endif

    <div class="form-group">
        <label>Empresa <span class="text-danger">*</span></label>
        <select name="empresa_id" class="form-control" required>
            @foreach($empresas as $empresa)
            <option value="{{ $empresa->id }}" {{ (isset($usuario) && $usuario->empresa_id==$empresa->id)?'selected':'' }}>{{ $empresa->nome }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Selecione uma empresa.</div>
    </div>

    <div class="form-group">
        <label>Nome <span class="text-danger">*</span></label>
        <input type="text" name="nome" class="form-control" value="{{ $usuario->nome ?? '' }}" required>
        <div class="invalid-feedback">Informe o nome.</div>
    </div>

    <div class="form-group">
        <label>Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" value="{{ $usuario->email ?? '' }}" required>
        <div class="invalid-feedback">Informe um email válido.</div>
    </div>

    <div class="form-group">
        <label>Senha {{ isset($usuario) ? '(deixe em branco para não alterar)' : '*' }}</label>
        <input type="password" name="senha" class="form-control" {{ isset($usuario) ? '' : 'required' }}>
        <div class="invalid-feedback">Informe a senha.</div>
    </div>

    <div class="form-group">
        <label>Tipo <span class="text-danger">*</span></label>
        <select name="tipo" class="form-control" required>
            <option value="SUPER_ADMIN" {{ (isset($usuario) && $usuario->tipo=='SUPER_ADMIN')?'selected':'' }}>SUPER_ADMIN</option>
            <option value="ADMIN" {{ (isset($usuario) && $usuario->tipo=='ADMIN')?'selected':'' }}>ADMIN</option>
            <option value="USER" {{ (isset($usuario) && $usuario->tipo=='USER')?'selected':'' }}>USER</option>
        </select>
    </div>

    <div class="form-group">
        <label>Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control" required>
            <option value="ATIVO" {{ (isset($usuario) && $usuario->status=='ATIVO')?'selected':'' }}>ATIVO</option>
            <option value="INATIVO" {{ (isset($usuario) && $usuario->status=='INATIVO')?'selected':'' }}>INATIVO</option>
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
