@section('title', 'Fornecedores')
@section('page-url', route('app.dashboard.suppliers'))
@section('page-breadcrumb-group', 'Produtos')
@section('page-breadcrumb', 'Fornecedores / Listagem')
@section('page-title', 'Fornecedores - Listagem')
@section('homepage-group-url', route('app.dashboard.products'))
<div>

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <x-modal-address-details
                    :ownerAddressTitleDetail="$ownerAddressTitleDetail ?? '' "
                    :complement="$complement ?? '' "
                    :neighborhood="$neighborhood ?? '' "
                    :postal_code="$postal_code ?? '' "
                    :city="$city ?? '' "
                    :province="$province ?? '' "
                    :country="$country ?? '' "
                    :notes="$notes ?? '' "
                 />

                <div class="container-fluid">

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card shadow-sm bg-white">
                                <div class="card-body">

                                    <!--  LINHA 1: PESQUISA -->
                                    <div class="row g-3 mb-3">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    id="searchInput"
                                                    wire:model.live.debounce.250ms="searcher"
                                                    class="form-control"
                                                    placeholder="Pesquisar por nome, email, endereço, bairro"
                                                >

                                                <button
                                                    wire:click="$set('searcher', '')"
                                                    type="button"
                                                    class="btn btn-outline-secondary"
                                                >
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  LINHA 2: DATAS -->
                                    <div class="row g-2 align-items-center">

                                <!-- Start Date -->
                                <div class="col-12 col-md">
                                    <input
                                        type="date"
                                        wire:model.live="start_date"
                                        class="form-control"
                                    >
                                </div>

                                <!-- Texto "Até" -->
                                <div class="col-12 col-md-auto text-center text-md-start px-md-1">
                                    <span class="fw-semibold d-inline-block">Até</span>
                                </div>

                                <!-- End Date -->
                                <div class="col-12 col-md">
                                    <input
                                        type="date"
                                        wire:model.live="end_date"
                                        class="form-control"
                                    >
                                </div>

                        </div>

                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="card">

                        <div class="card-body">
                            <div  class="w-full overflow-hidden">
                                <x-add-and-navigation-buttons />
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class=''>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Pessoa física</th>
                                            <th>Tax ID</th>
                                            <th>Endereço</th>
                                            <th>Contacto pessoal</th>
                                            <th>Telefone</th>
                                            <th>Email</th>
                                            <th>Cadastrado por</th>
                                            <th>Atualizado por</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($supplies as $supply)
                                        <tr>
                                            <td>{{ $supply['name'] }}</td>
                                            <td>
                                                <div class="flex justify-center">
                                                    <input {{ $supply['natural_person'] ? 'checked' : '' }}  disabled class="" type="checkbox" name="" />
                                                </div>
                                            </td>
                                            <td>{{ $supply['tax_id'] ?? '' }}</td>
                                            <td>
                                                <div class="flex flex-col justify-center text-center">
                                                    <div>
                                                    {{ $supply['address'] ?? '' }}
                                                    </div>

                                                    <div wire:click="GetSupplyAddressDetails('{{ $supply['uuid'] }}')" data-bs-target='#modal-address-details' data-bs-toggle='modal' class=" text-red-400 cursor-pointer  my-2 text-[12px]">
                                                        <i class="fa fa-plus"></i>
                                                        <span>Detalhes do endereço</span>
                                                    </div>

                                                </div>
                                            </td>
                                            <td>{{ $supply['contact_person'] ?? '' }}</td>
                                            <td>{{ $supply['phone'] ?? '' }}</td>
                                            <td>{{ $supply['email'] ?? '' }}</td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <span>{{ $supply['stored_by_user']['name'] ?? '' }}</span>
                                                    <span class='text-blue-500 my-2'>{{date('d-m-Y H:i', strtotime($supply['created_at']))}}</span>
                                                </div>

                                            </td>
                                            <td>
                                                 <div class="d-flex flex-column justify-content-center">
                                                    <span>{{ $supply['updated_by_user']['name'] ?? '' }}</span>
                                                    <span class='text-blue-500 my-2'>{{date('d-m-Y H:i', strtotime($supply['updated_at']))}}</span>
                                                </div>

                                            </td>
                                             <td class="text-center">
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-sm clsButtonOptions dropdown-toggle"
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


                                                        <li>
                                                            <a
                                                                wire:click="Edit('{{ $supply['uuid'] }}')"
                                                                class="dropdown-item">
                                                                <i class="fa fa-edit me-2"></i> Editar
                                                            </a>
                                                        </li>

                                                        <li><hr class="dropdown-divider"></li>

                                                         <li>
                                                            <a
                                                                wire:click="Clone({{ $supply['id'] }})"
                                                                class="dropdown-item">
                                                                <i class="fa fa-copy me-2"></i> Clonar
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a
                                                                wire:click="Delete('{{ $supply['uuid'] }}')"
                                                                class="dropdown-item"
                                                                href="#">
                                                                <i class="fa fa-trash-alt me-2"></i> Eliminar fornecedor
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
                                {{ $supplies instanceof \Illuminate\Pagination\LengthAwarePaginator ? $supplies->links() : '' }}
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
