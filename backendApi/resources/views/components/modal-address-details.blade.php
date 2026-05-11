@props([
   'ownerAddressTitleDetail' => null,
   'complement' => null,
    'neighborhood' => null,
    'postal_code' => null,
    'city' => null,
    'province' => null,
    'country' => null,
    'notes' => null,
])

<div wire:ignore.self class="modal fade" id="modal-address-details" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalhes de Endereço do <span class="text-capitalize">{{ $ownerAddressTitleDetail }}</span></h5>
                    <button wire:click='closeAddressDetailModal()'  class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='d-flex col-md-12'>

                        <div class="col-md-6">
                            <div class="rounded  ">
                                <div class="p-1 rounded-sm text-white  bg-indigo-300">
                                    <p class="text-lg font-semibold">Informações de endereço</p>
                                </div>

                                <div class="my-2">
                                    <p class="text-base text-semibold">Complemento: {{ $complement  }}</p>
                                    <p class="text-base text-semibold">Bairro: {{ $neighborhood  }}</p>
                                    <p class="text-base text-semibold">Código postal: {{ $postal_code  }}</p>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                             <div class="p-1 rounded-sm text-white  bg-indigo-200">
                                    <p class="text-lg font-semibold">Informações de localização</p>
                                </div>

                                <div class="my-2">
                                    <p class="text-base text-semibold">Cidade: {{ $city  }}</p>
                                    <p class="text-base text-semibold">Província: {{ $province  }}</p>
                                    <p class="text-base text-semibold">País: {{ $country  }}</p>
                                    <p class="text-base text-semibold">Notas: {{ $notes  }}</p>
                                </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">
                     <button data-bs-dismiss="modal" class="px-2 py-2 bg-slate-300 rounded">Fechar</button>
                </div>
            </div>
        </div>
    </div>



