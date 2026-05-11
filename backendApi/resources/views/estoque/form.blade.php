<div class="form-group">
    <label>Produto <span class="text-danger">*</span></label>
    <select name="produto_id" class="form-control" required>
        @foreach($produtos as $produto)
            <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
        @endforeach
    </select>
    <div class="invalid-feedback">Selecione um produto.</div>
</div>

<div class="form-group">
    <label>Tipo <span class="text-danger">*</span></label>
    <select name="tipo" class="form-control" required>
        <option value="ENTRADA">ENTRADA</option>
        <option value="SAIDA">SAIDA</option>
    </select>
    <div class="invalid-feedback">Selecione o tipo de movimento.</div>
</div>

<div class="form-group">
    <label>Quantidade <span class="text-danger">*</span></label>
    <input type="number" name="quantidade" class="form-control" required>
    <div class="invalid-feedback">Informe a quantidade.</div>
</div>
