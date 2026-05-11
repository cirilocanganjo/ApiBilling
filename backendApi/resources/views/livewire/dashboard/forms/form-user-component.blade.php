@if ($uuid && !$id)
 @section('title', 'Edit User')
 @elseif (!$uuid && !$id)
 @section('title', 'New User')
 @elseif ($id && !$uuid)
 @section('title', 'Clone User')
@endif

@if ($uuid && !$id)
 @section('page-breadcrumb', 'Utilizador / Editar')
 @elseif (!$uuid && !$id)
 @section('page-breadcrumb', 'Utilizador / Adicionar')
 @elseif ($id && !$uuid)
 @section('page-breadcrumb', 'Utilizador / Clonar')
@endif

@if ($uuid && !$id)
 @section('page-title', 'Utilizador - Editar')
 @elseif (!$uuid && !$id)
 @section('page-title', 'Utilizador - Adicionar')
 @elseif ($id && !$uuid)
 @section('page-title', 'Utilizador - Clonagem')
@endif

@section('page-url', route('app.dashboard.users'))

<div>
     <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">

                    <div class="row">
        <div class="col-sm-12">
        <div class="box-content">

                 <x-form :uuid="$uuid ?? '' ">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label control-label">Nome <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                            <input type="text" wire:model='name' class="form-control"  >
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                                <label class="col-sm-3 col-form-label control-label">Email <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                <input wire:model="email" type="email" class="form-control" />
                                 @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        </div>

                        <div class="form-group row">
                                <label class="col-sm-3 col-form-label control-label">Tipo <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                  <select class="form-control" wire:model='type'>
                                    <option  value="">Selecionar</option>
                                    <option value='SUPER_ADMIN'>Super Admin</option>
                                    <option value='ADMIN'>Admin</option>
                                    <option value='USER'>Utilizador</option>
                                </select>
                                @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                         <div  class="{{ $uuid ? 'd-none' : '' }} form-group row">
                                <label class="col-sm-3 col-form-label control-label">Palavra-passe <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                <input wire:model="password" type="password" class="form-control"  />
                                </div>
                        </div>

                         <div  class="{{ $uuid ? 'd-none' : '' }} form-group row">
                                <label class="col-sm-3 col-form-label control-label">Confirmar palavra-passe <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                <input wire:model="password_confirmation" type="password" class="form-control"  />
                                </div>
                        </div>

                </x-form>

                </div>
                </div>
                </div>

                </div>

            </div>


            @push('scripts')
            <script>
                let uuid = @json($uuid);
                if (uuid) {

                }

            </script>
            @endpush
