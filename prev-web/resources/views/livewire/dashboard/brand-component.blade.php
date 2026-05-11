@section('title', 'Dashboard | Marcas')
<div>
    <div id="wrapper">
        <x-side-bar />
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <livewire:dashboard.top-bar-component />
                <div class="container-fluid">
                    <x-modal-brand :companies="$companies ?? []" :status="$status ?? false" />

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Marcas</h3>
                        </div>

                        <div class="card-body">
                            <div x-data="xCarouselItems()" x-init="init()" class="w-full overflow-hidden">
                                <x-add-and-navigation-buttons />

                                <div id="inputWrapper" class="flex gap-2 py-2 overflow-hidden max-w-[980px]">
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

                                    <!-- Empresa -->
                                    <select
                                        wire:model.live="search_company_id"
                                        x-on:change="lockButtonsIfFilled()"
                                        class="input-item p-2 border rounded"
                                    >
                                        <option value="">Empresa</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company['id'] }}">{{ $company['name'] }}</option>
                                        @endforeach
                                    </select>

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

                                    <input
                                        type="date"
                                        wire:model.live="start_date"
                                        x-on:input="lockButtonsIfFilled()"
                                        class="input-item p-2 border rounded"
                                    >

                                    <input
                                        type="date"
                                        wire:model.live="end_date"
                                        x-on:input="lockButtonsIfFilled()"
                                        class="input-item p-2 border rounded"
                                    >
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class=''>
                                        <tr>
                                            <th>Data</th>
                                            <th>Nome</th>
                                            <th>Empresa</th>
                                            <th>Cadastrado por</th>
                                            <th>Atualizado por</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($brands as $brand)
                                        <tr>
                                            <td>{{ date('d-m-Y H:i', strtotime($brand['created_at'])) }}</td>
                                            <td>{{ $brand['name'] }}</td>
                                            <td>{{ $brand['company']['name'] }}</td>
                                            <td>{{ $brand['stored_by_user']['name'] ?? '' }}</td>
                                            <td>{{ $brand['updated_by_user']['name'] ?? '' }}</td>
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
                                                                wire:click="Edit('{{ $brand['uuid'] }}')"
                                                                class="dropdown-item"
                                                                href="#">
                                                                <i class="fa fa-edit me-2"></i> Editar
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a
                                                                wire:click="Delete('{{ $brand['uuid'] }}')"
                                                                class="dropdown-item"
                                                                href="#">
                                                                <i class="fa fa-trash-alt me-2"></i> Eliminar marca
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="999">
                                                <div class="alert alert-secondary text-center">
                                                    Nenhum resultado encontrado!
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div>
                                {{ $brands instanceof \Illuminate\Pagination\LengthAwarePaginator ? $brands->links() : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
            <script>
                function xCarouselItems() {
                    return {
                        index: 0,
                        itemWidth: 168,
                        atStart: true,
                        atEnd: false,
                        init() { this.update(); },
                        update() {
                            const wrapper = document.getElementById("inputWrapper");
                            wrapper.scrollTo({ left: this.index * this.itemWidth, behavior: 'smooth' });
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
                            this.atStart = filled;
                            this.atEnd = filled;
                        },
                        openModal() { $('#modal').modal('show'); }
                    }
                }

                function openEditModal () {
                    $('#modal').modal('show');
                }
            </script>
            @endpush
        </div>
    </div>
</div>
