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

<div wire:ignore.self class="modal fade" id="supplyAddressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background-color: #f5f7fa;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addressModalLabel">Fornecedor - Endereço </h5>
                <button wire:click='close' type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form wire:submit='InsertTextFieldValuesInsideTextArea'>
                <div class="row">
                    <!-- Left Column: Inputs -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Endereço</label>
                                    <input wire:model='supply_address' type="text" id="modal_address" class="form-control"  />
                                </div>
                                <div class="form-group">
                                    <label>Complemento</label>
                                    <input wire:model='supply_complement' type="text" id="modal_complement" class="form-control"  />
                                </div>
                                <div class="form-group">
                                    <label>Bairro</label>
                                    <input wire:model='supply_neighborhood' type="text" id="modal_neighborhood" class="form-control" >
                                </div>
                                <div class="form-group">
                                    <label>Código Postal</label>
                                    <input wire:model='supply_postal_code' type="text" id="modal_postal_code" class="form-control" >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Selects -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>País</label>
                                    <select wire:model='supply_country' id="modal_country" class="form-control">
                                        <option value="">Selecionar País</option>
                                        @if ($countries)
                                        @foreach($countries as $country)
                                            <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Província</label>
                                    <select wire:model='supply_province' id="modal_province" class="form-control">
                                        <option value="">Selecionar Província</option>
                                        @if ($provinces)
                                        @foreach($provinces as $province)
                                            <option value="{{ $province['id'] }}">
                                                {{ $province['name'] }}
                                            </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <select wire:model='supply_city' id="modal_city" class="form-control">
                                        <option value="">Selecionar Cidade</option>
                                        @if ($cities)
                                        @foreach($cities as $city)
                                            <option value="{{ $city['id'] }}">{{ $city['name'] }} </option>
                                        @endforeach
                                        @endif
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
