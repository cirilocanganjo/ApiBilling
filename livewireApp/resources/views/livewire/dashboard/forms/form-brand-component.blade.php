@section('title', 'Dashboard | Marca')
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
                        <label for="">Marca:</label>
                        <input wire:model='name' class="form-control py-2" type="text" />
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
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

                    <div class="flex gap-1 my-2">
                        <button class="rounded text-white px-2 py-2 {{ $uuid ? 'bg-green-500' : 'bg-blue-500'}} "> {{$uuid ? 'Atualizar' : 'Cadastrar'}} </button>
                        <a href="{{route('app.dashboard.brands')}}" class="btn btn-secondary rounded text-white px-2 py-2 ">Marcas</a>
                    </div>
                    </form>
                 </div>

                </div>

            </div>

