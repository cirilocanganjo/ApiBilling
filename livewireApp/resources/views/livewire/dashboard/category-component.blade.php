@section('title', 'Categorias')
@section('page-url', route('app.dashboard.suppliers'))
@section('page-breadcrumb-group', 'Produtos')
@section('page-breadcrumb', 'Categorias / Listagem')
@section('page-title', 'Categorias - Listagem')
@section('homepage-group-url', route('app.dashboard.categories'))

@section('styles')
<style>
.box-content {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.divSearch {
    background-color: #FAFAFA;
    padding: 15px;
    border: 1px solid #DDDDDD;
    margin-bottom: 15px;
}
#categoriasTable_length, #categoriasTable_info {
    text-align: left !important;
}
#categoriasTable_length label {
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>
@endsection

<section class="content">
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
                                            placeholder="Pesquisar por nome, descrição"
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
                                                            <thead>
                                                                <tr>
                                                                    <th>Categoria</th>
                                                                    <th>Descrição</th>
                                                                    <th>Cadastrada por</th>
                                                                    <th>Atualizada por</th>
                                                                    <th>Opções</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            @forelse ($categories as $category)
                                                                <tr>
                                                                    <td>{{ $category['name'] }}</td>
                                                                    <td>{{ $category['description'] }}</td>
                                                                    <td>
                                                                        <div class="d-flex flex-column justify-content-center">
                                                                          <span>{{ $category['stored_by_user']['name'] ?? '' }}</span>
                                                                          <span class='text-blue-500 my-2'>{{date('d-m-Y H:i', strtotime($category['created_at']))}}</span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                         <div class="d-flex flex-column justify-content-center">
                                                                            <span>{{ $category['updated_by_user']['name'] ?? '' }}</span>
                                                                            <span class='text-blue-500 my-2'>{{date('d-m-Y H:i', strtotime($category['updated_at']))}}</span>
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

                                                                                <li><hr class="dropdown-divider"></li>

                                                                                <li>
                                                                                    <a
                                                                                        wire:click="Edit('{{ $category['uuid'] }}')"
                                                                                        class="dropdown-item"
                                                                                        href="#">
                                                                                        <i class="fa fa-edit me-2"></i> Editar
                                                                                    </a>
                                                                                </li>

                                                                                <li>
                                                                                    <a
                                                                                        wire:click="Delete('{{ $category['uuid'] }}')"
                                                                                        class="dropdown-item"
                                                                                        href="#">
                                                                                        <i class="fa fa-trash-alt me-2"></i> Eliminar categoria
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

                    </div>
                </div>

                            <!-- Pagination -->
                            <div>
                                {{ $categories instanceof \Illuminate\Pagination\LengthAwarePaginator ? $categories->links() : '' }}
                            </div>
                            <!-- End of Pagination -->


</div>
</section>

@section('scripts')
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
@endsection
