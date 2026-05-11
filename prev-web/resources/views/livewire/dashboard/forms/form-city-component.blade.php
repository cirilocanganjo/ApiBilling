@section('title', 'Dashboard | Cidade')
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
                        <label for="">Província:</label>
                        <select wire:model='province_id' class="form-control py-2">
                            <option value="">Selecionar província</option>
                            @php
                                $apiQueries = new \App\Services\ApiQueries();
                                $provinces = $apiQueries->GetProvinceFromService();
                            @endphp
                            @if($provinces)
                                @foreach($provinces as $province)
                                    <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('province_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">Nome:</label>
                        <input wire:model='name' class="form-control py-2" type="text" />
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                     <div>
                        <label for="">Código ISO:</label>
                        <input wire:model='iso_code' class="form-control py-2" type="text" />
                        @error('iso_code') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-1 my-2">
                        <button class="rounded text-white px-2 py-2 {{ $uuid ? 'bg-green-500' : 'bg-blue-500'}} "> {{$uuid ? 'Atualizar' : 'Cadastrar'}} </button>
                        <a href="{{route('app.dashboard.cities')}}" class="btn btn-secondary rounded text-white px-2 py-2 ">Cidades</a>
                    </div>
                    </form>
                 </div>

                </div>

            </div>

