@section('title', 'Utilizadores')
@section('page-url', route('app.dashboard.users'))
@section('page-breadcrumb', 'Utilizadores / Listagem')
@section('page-inner-url', route('app.dashboard.users'))
@section('page-title', 'Utilizadores -  Listagem')


<div>

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <x-modal-user-clone-items
                    :uuid="$uuid ?? '' " :id="$id ?? null"  :complement="$complement ?? '' "
                    :neighborhood="$neighborhood ?? '' "
                    :postal_code="$postal_code ?? '' "
                    :city="$city ?? '' "  :province="$province ?? '' "
                    :country="$country ?? '' "
                    :notes="$notes ?? '' "
                    :cities="$cities ?? [] "
                    :provinces="$provinces ?? [] "
                    :countries="$countries ?? [] ">
                </x-modal-user-clone-items>

                <div class="container-fluid">

                    <div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm bg-white">
            <div class="card-body">

                <!-- 🔍 LINHA 1: PESQUISA -->
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <div class="input-group">
                            <input
                                type="text"
                                id="searchInput"
                                wire:model.live.debounce.250ms="searcher"
                                class="form-control"
                                placeholder="Pesquisar por nome, email"
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

                <!-- 🧩 LINHA 2: FILTROS -->

                <div class="row g-3 align-items-center">

                         <div class="col-12 col-md">
                             <select
                            class="form-control"
                            wire:model.live="search_status"
                        >
                            <option value="">Selecionar estado</option>
                            <option value="active">Ativo</option>
                            <option value="inactive">Inativo</option>
                        </select>

                         </div>

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
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Estado</th>
                                            <th>Cadastrado por</th>
                                            <th>Atualizado por</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            <tr>
                                                <td>{{ $user['name'] }}</td>
                                                <td>{{ $user['email'] }}</td>
                                                <td>
                                                    @if ($user['status'] == 'active')
                                                    <span class="text-blue-500">Ativo</span>
                                                    @else
                                                    <span class="text-red-400">Inativo</span>
                                                    @endif

                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column justify-content-center">
                                                    <span>{{ $user['stored_by_user']['name'] ?? '' }}</span>
                                                    <span class='text-blue-500 my-2'>{{date('d-m-Y H:i', strtotime($user['created_at']))}}</span>
                                                    </div>

                                                </td>
                                                <td>
                                                     <div class="d-flex flex-column justify-content-center">
                                                         <span>{{ $user['updated_by_user']['name'] ?? '' }}</span>
                                                        <span class='text-blue-500 my-2'>{{date('d-m-Y H:i', strtotime($user['created_at']))}}</span>
                                                </div>

                                                </td>
                                                 <td class="text-center">
                                                <div class="dropdown">
                                                    <button
                                                        class="clsButtonOptions btn-sm btn dropdown-toggle"
                                                        type="button"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Opções
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="text-gray-400 cursor-not-allowed pointer-events-none dropdown-item">
                                                                <i class="fas fa-file-alt me-2"></i> Detalhes
                                                            </a>
                                                        </li>


                                                        <li>
                                                            <a
                                                                wire:click="Edit('{{ $user['uuid'] }}')"
                                                                class="dropdown-item">
                                                                <i class="fa fa-edit me-2"></i> Editar
                                                            </a>
                                                        </li>

                                                         <li>
                                                            <a
                                                                wire:click="RenamePassword('{{ $user['uuid'] }}')"
                                                                class=" dropdown-item">
                                                                <i class="fa fa-edit me-2"></i> Renomear senha
                                                            </a>
                                                        </li>

                                                        <li><hr class="dropdown-divider"></li>

                                                         <li>
                                                            <a
                                                                wire:click="Clone({{ $user['id'] }})"
                                                                class="dropdown-item">
                                                                <i class="fa fa-copy me-2"></i> Clonar
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a
                                                                wire:click="Delete('{{ $user['uuid'] }}')"
                                                                class="dropdown-item">
                                                                <i class="fa fa-trash-alt me-2"></i> Eliminar utilizador
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </td>
                                            </tr>
                                        @empty
                                            <tr>
                                               <td colspan="10">
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
                                {{ $users instanceof \Illuminate\Pagination\LengthAwarePaginator ? $users->links() : '' }}
                            </div>
                        </div>
                    </div>
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
