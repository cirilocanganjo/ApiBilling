    <!-- Main content -->
   <div class="container-fluid p-4">

    <div class="tabs">
        <a href="#cadastro" class="active">Cadastro</a>
        <a href="#kit">Kit / Combo</a>
        <a href="#taxa">Taxa</a>
        <a href="#fornecedores">Fornecedores</a>
        <a href="#opcoes">Opções</a>
        <a href="#validade">Controle de validade</a>
    </div>

    <!-- Conteúdo das Abas -->
    <div id="cadastro" class="tab-content">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Código</label>
                    <div class="col-md-4">
                        <input class="form-control" type="text">
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <input type="checkbox" class="mr-2"> Automático
                    </div>
                    <label class="col-md-2 col-form-label">Código Extra</label>
                    <div class="col-md-2">
                        <input class="form-control" type="text">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">EAN / GTIN</label>
                    <div class="col-md-4">
                        <input class="form-control" type="text">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Nome</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Categoria</label>
                    <div class="col-md-4">
                        <select class="form-control"></select>
                    </div>
                    <label class="col-md-2 col-form-label">Peso Líquido</label>
                    <div class="col-md-4">
                        <input class="form-control" type="text">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Subcategoria</label>
                    <div class="col-md-4">
                        <select class="form-control"></select>
                    </div>
                    <label class="col-md-2 col-form-label">Peso Bruto</label>
                    <div class="col-md-4">
                        <input class="form-control" type="text">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Marca</label>
                    <div class="col-md-4">
                        <select class="form-control"></select>
                    </div>
                    <label class="col-md-2 col-form-label">Localização</label>
                    <div class="col-md-4">
                        <input class="form-control" type="text">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Preço de Venda</label>
                    <div class="col-md-4">
                        <input class="form-control" type="text">
                        <small class="text-muted">PROMO</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <input type="checkbox" class="mr-2"> Automático
                        <input type="checkbox" class="ml-4 mr-2"> Preço alterável na venda
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Preço de Custo</label>
                    <div class="col-md-4">
                        <input class="form-control" type="text">
                    </div>
                    <label class="col-md-2 col-form-label">Markup %</label>
                    <div class="col-md-4">
                        <input class="form-control" type="text" disabled value="30%">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <input type="checkbox"> Seguir Markup Padrão
                    </div>
                </div>

                <hr>
                <div class="form-group row">
                    <div class="col-md-2">
                        <input type="checkbox"> Controlar Estoque
                    </div>
                    <div class="col-md-10">
                        <img src="premium.png" width="25"> Recurso PREMIUM
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Estoque Atual</label>
                    <div class="col-md-4">
                        <input class="form-control" type="number" value="0" disabled>
                    </div>
                    <label class="col-md-2 col-form-label">Limites estoque</label>
                    <div class="col-md-4">
                        <select class="form-control"></select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Unidade Medida</label>
                    <div class="col-md-4">
                        <select class="form-control"></select>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <input type="checkbox" class="mr-2"> Permite fracionamento (Ex: venda por peso/kg)
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="image-box">
                    <div style="font-size: 32px;">📷</div>
                    <small>Adicionar imagem</small>
                </div>
                <a href="#" class="mt-2 d-block">Adicionar Observação – F4</a>
            </div>
		    </div>
			</div>

			<!-- Aba Taxa -->
			<div id="taxa" class="tab-content" style="display:none;">
				<div class="form-group row">
					<label class="col-md-1 col-form-label">Taxa</label>
					<div class="col-md-3">
						<input type="text" class="form-control">
					</div>
					<div class="col-md-2">
						<input type="checkbox" checked> Seguir Taxa Padrão
					</div>
					<div class="col-md-3">
						<a href="#">Definir taxa padrão</a>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-12">
						<input type="checkbox"> Sem Taxa
					</div>
				</div>
			</div>

			<div class="row mt-4">
				<div class="col-md-12 text-right">
					<button class="btn btn-primary">SALVAR - F2</button>
					<button class="btn btn-light">CANCELAR</button>
				</div>
			</div>

		</div>
			 
    <!-- /.content -->


<script>
function previewImg(event) {
    const img = document.getElementById('fotoPreview');
    img.src = URL.createObjectURL(event.target.files[0]);
}
</script>
