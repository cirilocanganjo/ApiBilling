@extends('layouts.app')
@section('title', isset($venda) ? 'Editar Venda' : 'Nova Venda')
@section('content')
<form action="{{ isset($venda) ? route('vendas.update',$venda->id) : route('vendas.store') }}" method="POST" novalidate>
    @csrf
    @if(isset($venda)) @method('PUT') @endif

    <div class="form-group">
        <label>Cliente <span class="text-danger">*</span></label>
        <select name="cliente_id" class="form-control" required>
            @foreach($clientes as $cliente)
            <option value="{{ $cliente->id }}" {{ (isset($venda) && $venda->cliente_id==$cliente->id)?'selected':'' }}>{{ $cliente->nome }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Selecione um cliente.</div>
    </div>

    <div class="form-group">
        <label>Usuário <span class="text-danger">*</span></label>
        <select name="usuario_id" class="form-control" required>
            @foreach($usuarios as $user)
            <option value="{{ $user->id }}" {{ (isset($venda) && $venda->usuario_id==$user->id)?'selected':'' }}>{{ $user->nome }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Selecione um usuário.</div>
    </div>

    <div class="form-group">
        <label>Estado <span class="text-danger">*</span></label>
        <select name="estado" class="form-control" required>
            <option value="PENDENTE" {{ (isset($venda) && $venda->estado=='PENDENTE')?'selected':'' }}>PENDENTE</option>
            <option value="CONCLUIDA" {{ (isset($venda) && $venda->estado=='CONCLUIDA')?'selected':'' }}>CONCLUIDA</option>
            <option value="CANCELADA" {{ (isset($venda) && $venda->estado=='CANCELADA')?'selected':'' }}>CANCELADA</option>
        </select>
        <div class="invalid-feedback">Selecione o estado da venda.</div>
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
