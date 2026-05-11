@extends('layouts.app')
@section('title', isset($licenca) ? 'Editar Licença' : 'Nova Licença')
@section('content')
<form action="{{ isset($licenca) ? route('licencas.update',$licenca->id) : route('licencas.store') }}" method="POST" novalidate>
    @csrf
    @if(isset($licenca)) @method('PUT') @endif

    <div class="form-group">
        <label>Empresa <span class="text-danger">*</span></label>
        <select name="empresa_id" class="form-control" required>
            @foreach($empresas as $empresa)
            <option value="{{ $empresa->id }}" {{ (isset($licenca) && $licenca->empresa_id==$empresa->id)?'selected':'' }}>{{ $empresa->nome }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Selecione uma empresa.</div>
    </div>

    <div class="form-group">
        <label>Código Licença <span class="text-danger">*</span></label>
        <input type="text" name="codigo_licenca" class="form-control" value="{{ $licenca->codigo_licenca ?? '' }}" required>
        <div class="invalid-feedback">Informe o código da licença.</div>
    </div>

    <div class="form-group">
        <label>Tipo <span class="text-danger">*</span></label>
        <select name="tipo" class="form-control" required>
            <option value="PRO" {{ (isset($licenca) && $licenca->tipo=='PRO')?'selected':'' }}>PRO</option>
            <option value="TRL" {{ (isset($licenca) && $licenca->tipo=='TRL')?'selected':'' }}>TRL</option>
        </select>
        <div class="invalid-feedback">Selecione o tipo.</div>
    </div>

    <div class="form-group">
        <label>Data Ativação</label>
        <input type="date" name="data_ativacao" class="form-control" value="{{ $licenca->data_ativacao ?? '' }}">
    </div>

    <div class="form-group">
        <label>Data Expiração</label>
        <input type="date" name="data_expiracao" class="form-control" value="{{ $licenca->data_expiracao ?? '' }}">
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="PENDENTE" {{ (isset($licenca) && $licenca->status=='PENDENTE')?'selected':'' }}>PENDENTE</option>
            <option value="ATIVA" {{ (isset($licenca) && $licenca->status=='ATIVA')?'selected':'' }}>ATIVA</option>
            <option value="EXPIRADA" {{ (isset($licenca) && $licenca->status=='EXPIRADA')?'selected':'' }}>EXPIRADA</option>
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
