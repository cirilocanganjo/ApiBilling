@if ($uuid && !$id)
 @section('title', 'Edit Client')
 @elseif (!$uuid && !$id)
 @section('title', 'New Client')
 @elseif ($id && !$uuid)
 @section('title', 'Clone Client')
@endif

@if ($uuid && !$id)
 @section('page-breadcrumb', 'Cliente / Editar')
 @elseif (!$uuid && !$id)
 @section('page-breadcrumb', 'Cliente / Adicionar')
 @elseif ($id && !$uuid)
 @section('page-breadcrumb', 'Cliente / Clonar')
@endif

@if ($uuid && !$id)
 @section('page-title', 'Cliente - Editar')
 @elseif (!$uuid && !$id)
 @section('page-title', 'Cliente - Adicionar')
 @elseif ($id && !$uuid)
 @section('page-title', 'Cliente - Clonagem')
@endif

@section('page-url', route('app.dashboard.clients'))


<section class="content">
        <x-modal-client-address :uuid="$uuid ?? '' " :id="$id ?? null"   :complement="$complement ?? '' " :neighborhood="$neighborhood ?? '' " :postal_code="$postal_code ?? '' "  :city="$city ?? '' "  :province="$province ?? '' "
                    :country="$country ?? '' "
                    :notes="$notes ?? '' "
                    :cities="$cities ?? [] "
                    :provinces="$provinces ?? [] "
                    :countries="$countries ?? [] ">
        </x-modal-address>

        <div class="container-fluid">
                        <div class="row">
                                <div class="col-sm-12">
                                        <div class="box-content">
                                                <x-form :uuid="$uuid ?? '' " >
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Nome <span class="text-danger">*</span></label>
                                                        <div class="col-sm-6">
                                                        <input wire:model='name' type="text"  class="form-control" " >
                                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>


                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Email <span class="text-danger">*</span></label>
                                                        <div class="col-sm-6">
                                                        <input type="email" wire:model="email" class="form-control" value="{{ $cliente->email ?? '' }}" required>
                                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">TAX ID <span class="text-danger">*</span></label>
                                                        <div class="col-sm-6">
                                                        <input type="text" wire:model='tax_id' class="form-control" >
                                                        @error('tax_id') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label control-label">Recipiente <span class="text-danger">*</span></label>
                                                        <div class="col-sm-6">
                                                            <input wire:model='recipient' class="form-control py-2" type="text" />
                                                            @error('recipient') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>


                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Telefone <span class="text-danger">*</span> </label>
                                                        <div class="col-sm-6">
                                                        <input type="text" wire:model="phone" class="form-control" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Endereço</label>
                                                        <div class="col-sm-6">
                                                            <textarea wire:model='client_all_address_info' wire:click="GetClientAdressDetailsOnTextFields('{{ $uuid ?? ''  }}')"  class="form-control" rows="4" readonly data-bs-toggle="modal" data-bs-target="#clientAddressModal" style="cursor: pointer;">
                                                            </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Notas <span class="text-danger"></span></label>
                                                        <div class="col-sm-6">
                                                        <textarea type="text" wire:model='notes' class="form-control" ></textarea>
                                                        @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>

                                                </x-form>

                                        </div>
                                </div>
                        </div>
        </div>

</section>

@push('scripts')
<script>
    $(document).ready(() => {
        //$('#')
    })
</script>
@endpush


