@extends('layouts.app')
@section('title', isset($movimento) ? 'Editar Movimento' : 'Novo Movimento')
@section('content')
<form action="{{ isset($movimento) ? route('movimentos_estoque.update',$movimento->id) : route('movimentos_estoque.store') }}" method="POST" novalidate>
    @csrf
    @if(isset($movimento)) @method('PUT') @endif

    <div class="form-group">
        <label>Produto <span class="text-danger">*</span></label>
        <select name="produto_id" class="form-control" required>
            @foreach($produtos as $produto)
            <option value="{{ $produto->id }}" {{ (isset($movimento) && $movimento->produto_id==$produto->id)?'selected':'' }}>{{ $produto->nome }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">Selecione um produto.</div>
    </div>

    <div class="form-group">
        <label>Tipo <span class="text-danger">*</span></label>
        <select name="tipo" class="form-control" required>
            <option value="ENTRADA" {{ (isset($movimento) && $movimento->tipo=='ENTRADA')?'selected':'' }}>ENTRADA</option>
            <option value="SAIDA" {{ (isset($movimento) && $movimento->tipo=='SAIDA')?'selected':'' }}>SAIDA</option>
        </select>
        <div class="invalid-feedback">Selecione o tipo.</div>
    </div>

    <div class="form-group">
        <label>Quantidade <span class="text-danger">*</span></label>
        <input type="number" name="quantidade" class="form-control" value="{{ $movimento->quantidade ?? '' }}" required>
        <div class="invalid-feedback">Informe a quantidade.</div>
    </div>

    <div class="form-group">
        <label>Motivo</label>
        <input type="text" name="motivo" class="form-control" value="{{ $movimento->motivo ?? '' }}">
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
