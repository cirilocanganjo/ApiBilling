@props([
     'uuid' => '',
    'id' => null,
   'ownerAddressTitleDetail' => null,
   'complement' => null,
    'neighborhood' => null,
    'postal_code' => null,
    'city' => null,
    'province' => null,
    'country' => null,
    'notes' => null,
    'cities' => [],
    'provinces' => [],
    'countries' => [],
])


<div wire:ignore.self class="modal fade" id="user-clone-items" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background-color: #f5f7fa;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addressModalLabel">Clonar Utilizador </h5>
                <button wire:click='close' type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form wire:submit='StoreUserClone'>
                <div class="row">
                    <!-- Left Column: Inputs -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Nome</label>
                                    <input wire:model='clone_user_name' type="text"  class="form-control"  />
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input wire:model='clone_user_email' type="text"  class="form-control"  />
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Selects -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tipo</label>
                                     <select wire:model='clone_user_type' class="form-control" >
                                        <option  value="">Selecionar</option>
                                        <option value='SUPER_ADMIN'>Super Admin</option>
                                        <option value='ADMIN'>Admin</option>
                                        <option value='USER'>Utilizador</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Estado</label>
                                    <select wire:model='clone_user_status' class="form-control">
                                        <option value="">Selecionar estado</option>
                                        <option value="active">Ativo</option>
                                        <option value="inactive">Inativo</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-bs-dismiss="modal" type="submit" type="button" class="btn btn-primary" >Gravar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
    </form>
</div>
