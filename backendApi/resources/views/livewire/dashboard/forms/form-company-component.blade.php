@section('title', 'Dashboard | Empresa')
<div>
     <div id="wrapper">
       <x-side-bar />
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <livewire:dashboard.top-bar-component />
                <div class="container-fluid">

                 <div class="">
                    <form  wire:submit="{{$uuid ? 'Update' : 'Store'}}">
                    <div>
                        <label for="">Nome:</label>
                        <input wire:model='name' class="form-control py-2" type="text" />
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">NIF:</label>
                        <input wire:model='nif' class="form-control py-2" type="text" />
                        @error('nif') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">Email:</label>
                        <input wire:model='email' class="form-control py-2" type="email" />
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">Telefone:</label>
                        <input wire:model='phone' class="form-control py-2" type="text" />
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">Endereço:</label>
                        <input wire:model='address' class="form-control py-2" type="text" />
                        @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">Referência:</label>
                        <input wire:model='reference' class="form-control py-2" type="text" />
                        @error('reference') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">Status:</label>
                        <select wire:model='status' class="form-control py-2">
                            <option value="active">Ativo</option>
                            <option value="inactive">Inativo</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-1 my-2">
                        <button class="rounded text-white px-2 py-2 {{ $uuid ? 'bg-green-500' : 'bg-blue-500'}} "> {{$uuid ? 'Atualizar' : 'Cadastrar'}} </button>
                        <a href="{{route('app.dashboard.companies')}}" class="btn btn-secondary rounded text-white px-2 py-2 ">Empresas</a>
                    </div>
                    </form>
                 </div>

                </div>

            </div>

