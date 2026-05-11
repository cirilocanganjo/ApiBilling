@section('title', $uuid ? 'Edit Unit' : 'New Unit')
@section('page-url', route('app.dashboard.units'))
@section('page-breadcrumb', $uuid ? 'Unidade / Editar' : 'Unidade / Adicionar')
@section('page-title', $uuid ? 'Unidade - Editar' : 'Unidade - Adicionar')

<div>
     <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">

                     <div class="row">
                            <div class="col-sm-12">
                                    <div class="box-content">
                                        <x-form :uuid="$uuid ?? '' " >
                                                <div class="form-group row">
                                                         <label class="col-sm-3 col-form-label control-label">Nome <span class="text-danger">*</span></label>
                                                          <div class="col-sm-6">
                                                            <input wire:model='name' class="form-control py-2" type="text" />
                                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                          </div>
                                                </div>

                                                <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Sigla <span class="text-danger">*</span></label>
                                                        <div class="col-sm-6">
                                                            <input wire:model='acronym' class="form-control py-2" type="text" />
                                                             @error('acronym') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                </div>


                                        </x-form>
                                    </div>
                            </div>
                     </div>

                 {{-- <div class="">
                    <x-form :uuid="$uuid ?? '' " :isCloning="$isCloning ?? false">
                    <div>
                        <label for="">Nome:</label>
                        <input wire:model='name' class="form-control py-2" type="text" />
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">Sigla:</label>
                        <input wire:model='acronym' class="form-control py-2" type="text" />
                        @error('acronym') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">Empresa:</label>
                        <select wire:model='company_id' class="form-control py-2">
                            <option value="">Selecionar empresa</option>
                            @php
                                $apiQueries = new \App\Services\ApiQueries();
                                $companies = $apiQueries->GetCompanyFromService();
                            @endphp
                            @if($companies)
                                @foreach($companies as $company)
                                    <option value="{{ $company['id'] }}">{{ $company['name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                         @error('company_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    </x-form>
                 </div> --}}

                </div>

            </div>

