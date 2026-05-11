@section('title', 'Dashboard | Cidades')
<div>      

     <div id="wrapper">
       <x-side-bar />
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <livewire:dashboard.top-bar-component />
                <div class="container-fluid">
                    <x-modal-city 
                        :provinces="$provinces ?? []" 
                        :status="$status ?? false" 
                    />
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cidades</h3>
                        </div>

                        <div class="card-body">

                            <div x-data="xCarouselItems()" x-init="init()" class="w-full overflow-hidden" >  
                           
                            <x-add-and-navigation-buttons />

                                <!-- wrapper -->
                                <div id="inputWrapper" class="flex gap-2 py-2 overflow-hidden max-w-[980px]">
                                    
                                    
                                <!-- Quantidade de dados na tela -->
                                    @php
                                     $perPageOptions = range(1, 100);
                                    @endphp
                                    <select title="Quantidade de resultados" wire:model.live='perPage' class="input-item p-2 border rounded">
                                        @foreach ($perPageOptions as $option)
                                            <option value="{{ $option }}">{{ $option }}</option> 
                                        @endforeach
                                    </select>

                                    <input 
                                        type="text" 
                                        placeholder="Nome" 
                                        wire:model.live="search"
                                        x-on:input="lockButtonsIfFilled()"
                                        class="input-item p-2 border rounded"
                                    />                               

                                  

                                    <!-- Província -->
                                    <select 
                                        wire:model.live="search_province_id"
                                        x-on:change="lockButtonsIfFilled()"
                                        class="input-item p-2 border rounded"
                                    >
                                        <option value="">Província</option>
                                        @foreach($provinces as $p)
                                            <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Usuário -->
                                    <select 
                                        wire:model.live="search_user_id"
                                        x-on:change="lockButtonsIfFilled()"
                                        class="input-item p-2 border rounded"
                                    >
                                        <option value="">Usuário</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Start Date -->
                                    <input 
                                        type="date" 
                                        wire:model.live="start_date"
                                        x-on:input="lockButtonsIfFilled()"
                                        class="input-item p-2 border rounded"
                                    >

                                    <!-- End Date -->
                                    <input 
                                        type="date" 
                                        wire:model.live="end_date"
                                        x-on:input="lockButtonsIfFilled()"
                                        class="input-item p-2 border rounded"
                                    >
                                </div>
                            </div>    
      

                            <div class="table-responsive">
                                <table class="table">
                                    <thead class='bg-gray-200'>
                                        <tr class=''>
                                            <th>Data</th>
                                            <th>Nome</th>
                                            <th>Província</th>
                                            <th>Cadastrado por</th>
                                            <th>Atualizado por</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($cities as $city)
                                        <tr>
                                            <td>{{ date('d-m-Y H:i', strtotime($city['created_at'])) }}</td>
                                            <td>{{ $city['name'] }}</td>
                                            <td>{{ $city['province']['name'] ?? '' }}</td>
                                            <td>{{ $city['stored_by_user']['name'] ?? '' }}</td>
                                            <td>{{ $city['updated_by_user']['name'] ?? '' }}</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-sm btn-primary dropdown-toggle"
                                                        type="button"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Opções
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                        <li >
                                                            <a class="text-gray-400 cursor-not-allowed pointer-events-none dropdown-item" href="#">
                                                                <i class="fas fa-file-alt me-2"></i> Detalhes
                                                            </a>
                                                        </li>

                                                        <li><hr class="dropdown-divider"></li>

                                                        <li>
                                                            <a
                                                                wire:click="Edit('{{ $city['uuid'] }}')"
                                                                class="dropdown-item"
                                                                href="#">
                                                                <i class="fa fa-edit me-2"></i> Editar
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a
                                                                wire:click="Delete('{{ $city['uuid'] }}')"
                                                                class="dropdown-item"
                                                                href="#">
                                                                <i class="fa fa-trash-alt me-2"></i> Eliminar cidade
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td  colspan="999">

                                                <div class=" alert alert-secondary text-center">
                                                    Nenhum resultado encontrado!
                                                </div>

                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>

                                </table>
                            </div>

                            <!-- Pagination -->
                            <div>
                                {{ $cities instanceof \Illuminate\Pagination\LengthAwarePaginator ? $cities->links() : '' }}
                            </div>
                            <!-- End of Pagination -->

                        </div>
                    </div>

                </div>

            </div>

    @push('scripts')
    <script>
        function xCarouselItems() {
            return {

                index: 0,
                itemWidth: 168, // 160 + margin-right
                atStart: true,
                atEnd: false,

                init() {
                    this.update();
                },

                update() {
                    const wrapper = document.getElementById("inputWrapper");

                    wrapper.scrollTo({
                        left: this.index * this.itemWidth,
                        behavior: 'smooth'
                    });

                    this.atStart = (this.index === 0);
                    this.atEnd = (this.index >= wrapper.children.length - 1);
                },

                next() {
                    const wrapper = document.getElementById("inputWrapper");
                    if (this.index < wrapper.children.length - 1) {
                        this.index++;
                        this.update();
                    }
                },

                prev() {
                    if (this.index > 0) {
                        this.index--;
                        this.update();
                    }
                },

                lockButtonsIfFilled() {
                    const wrapper = document.getElementById("inputWrapper");
                    const filled = [...wrapper.querySelectorAll("input, select")]
                        .some(el => el.value !== "");
                    
                    this.atStart = filled; // Se tem campos preenchidos → bloqueia
                    this.atEnd = filled;
                },

                openModal() {                
                  $('#modal').modal('show');
                }

            }
        }    

            function openEditModal () {
                $('#modal').modal('show');
            }
        
        </script>
    @endpush

