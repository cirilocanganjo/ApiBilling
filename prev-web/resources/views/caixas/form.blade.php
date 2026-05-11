@extends('layouts.app')
@section('title', isset($caixa) ? 'Editar Caixa' : 'Abrir Caixa')
@section('content')
<form action="{{ isset($caixa) ? route('caixas.update',$caixa->id) : route('caixas.store') }}" method="POST" novalidate>
    @csrf
    @if(isset($caixa)) @method('PUT') @endif

    <div class="form-group">
        <label>Usuário <span class="text-danger">*</span></label>
        <select name="usuario_id" class="form-control" required>
            @foreach($usuarios as $user)
            <option value="{{ $user->id }}" {{ (isset($caixa) && $caixa->usuario_id==$user->id)?'selected':'' }}>{{ $user->nome }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Selecione um usuário.</div>
    </div>

    <div class="form-group">
        <label>Saldo Inicial <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="saldo_inicial" class="form-control" value="{{ $caixa->saldo_inicial ?? '' }}" required>
        <div class="invalid-feedback">Informe o saldo inicial.</div>
    </div>

    <div class="form-group">
        <label>Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control" required>
            <option value="ABERTO" {{ (isset($caixa) && $caixa->status=='ABERTO')?'selected':'' }}>ABERTO</option>
            <option value="FECHADO" {{ (isset($caixa) && $caixa->status=='FECHADO')?'selected':'' }}>FECHADO</option>
        </select>
        <div class="invalid-feedback">Selecione o status do caixa.</div>
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
