  <div class="container-fluid">
    <div class="card shadow-none border-0">
        <div class="card-body">

            <h4 class="mb-4" style="border-bottom: 2px solid #007bff; width: fit-content;">
                Cadastro
            </h4>

            <form id="formCliente">

                <div class="row">

                    <!-- FOTO -->
                    <div class="col-md-3 text-center">
                        <div class="d-flex flex-column align-items-center">

                            <!-- Círculo Foto -->
                            <div style="width: 180px; height: 180px; border-radius: 50%; background: #e8f0fe;">
                                <img id="fotoPreview" 
                                     src="/img/user-placeholder.png" 
                                     alt="Foto" 
                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>

                            <input type="file" name="foto" id="foto" class="form-control mt-3" onchange="previewImg(event)">
                        </div>
                    </div>

                    <!-- CAMPOS -->
                    <div class="col-md-9">

                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label>Código</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-2 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="empresa">
                                    <label class="form-check-label">Empresa</label>
                                </div>
                            </div>

                            <div class="form-group col-md-8">
                                <label>Nome</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Data Nasc.</label>
                                <input type="date" class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Gênero</label>
                                <select class="form-control">
                                    <option>Não informado</option>
                                    <option>Masculino</option>
                                    <option>Feminino</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Telefone</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-1">
                                <label>DDI</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-2">
                                <label>Celular</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Num.Ident.</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Endereço</label>
                                <textarea class="form-control" rows="4"></textarea>

                                <div class="mt-1">
                                    <a href="#" class="mr-3 text-primary">Novo endereço - F4</a>
                                    <a href="#" class="mr-3 text-primary">Editar - F9</a>
                                    <a href="#" class="text-primary">Copiar - Ctrl-E</a>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Pai</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Mãe</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input type="email" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Observações</label>
                                <textarea class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Informações extras</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="form-group col-md-8">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control mr-2">
                                    <button class="btn btn-light border">...</button>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <hr>

                <!-- BOTÕES -->
                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary mr-3">
                        SALVAR - F2
                    </button>

                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                        CANCELAR
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>