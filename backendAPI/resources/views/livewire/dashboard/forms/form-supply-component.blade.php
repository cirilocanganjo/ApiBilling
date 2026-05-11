@if ($uuid && !$id)
 @section('title', 'Edit Supply')
 @elseif (!$uuid && !$id)
 @section('title', 'New Supply')
 @elseif ($id && !$uuid)
 @section('title', 'Clone Supply')
@endif

@if ($uuid && !$id)
 @section('page-breadcrumb', 'Fornecedor / Editar')
 @elseif (!$uuid && !$id)
 @section('page-breadcrumb', 'Fornecedor / Adicionar')
 @elseif ($id && !$uuid)
 @section('page-breadcrumb', 'Fornecedor / Clonar')
@endif

@if ($uuid && !$id)
 @section('page-title', 'Fornecedor - Editar')
 @elseif (!$uuid && !$id)
 @section('page-title', 'Fornecedor - Adicionar')
 @elseif ($id && !$uuid)
 @section('page-title', 'Fornecedor - Clonagem')
@endif

@section('page-breadcrumb-group', 'Produtos')
@section('homepage-group-url', route('app.dashboard.products'))
@section('page-url', route('app.dashboard.suppliers'))





<div>
     <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <x-modal-supply-address :uuid="$uuid ?? '' " :id="$id ?? null" :complement="$complement ?? '' " :neighborhood="$neighborhood ?? '' " :postal_code="$postal_code ?? '' "  :city="$city ?? '' "  :province="$province ?? '' "
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
                                                    <input type="text" wire:model='name' class="form-control"  >
                                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">TAX ID <span class="text-danger">*</span>  </label>
                                                        <div class="col-sm-6">
                                                        <input wire:model='tax_id' type="text" class="form-control" />
                                                        @error('tax_id') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                </div>

                                                <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Pessoa responsável pelo contacto</label>
                                                        <div class="col-sm-6">
                                                        <input wire:model='contact_person' type="text" class="form-control" />
                                                        </div>
                                                </div>

                                                <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Endereço</label>
                                                        <div class="col-sm-6">
                                                            <textarea wire:model='supply_all_address_info' wire:click="GetSupplyAdressDetailsOnTextFields('{{ $uuid ?? ''  }}')"  class="form-control" rows="4" readonly data-bs-toggle="modal" data-bs-target="#supplyAddressModal" style="cursor: pointer;">
                                                            </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Email <span class="text-danger">*</span> </label>
                                                        <div class="col-sm-6">
                                                        <input wire:model='email' type="tel" class="form-control" />
                                                        @error('contact_person') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group d-flex align-items-center row">
                                                        <label class="col-sm-3 col-form-label control-label">Pessoa física? </label>
                                                        <div class="col-sm-6">
                                                        <input value="1" type="checkbox" wire:model.live="natural_person" checked  />
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label control-label">Notas <span class="text-danger"></span></label>
                                                            <div class="col-sm-6">
                                                            <textarea type="text" wire:model='notes' class="form-control" ></textarea>
                                                            </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Telefone <span class="text-danger">*</span> </label>
                                                        <div class="col-sm-6">
                                                        <input type="text" wire:model="phone" class="form-control" />
                                                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                        </x-form>
                                    </div>
                                </div>
                        </div>

                </div>

            </div>

