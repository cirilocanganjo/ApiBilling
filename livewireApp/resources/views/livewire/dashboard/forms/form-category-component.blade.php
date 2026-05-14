@section('title', $uuid ? 'Edit Category' : 'New Category')
@section('page-url', route('app.dashboard.categories'))
@section('page-breadcrumb-group', 'Produtos')
@section('page-breadcrumb', $uuid ? 'Categoria / Editar' : 'Categoria / Adicionar')
@section('page-title', $uuid ? 'Categoria - Editar' : 'Categoria - Adicionar')
@section('homepage-group-url', route('app.dashboard.products'))

<section class="content">
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
                                <label class="col-sm-3 col-form-label control-label">Descrição</label>
                                <div class="col-sm-6">
                                <textarea wire:model="description" class="form-control"></textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        </div>

                </x-form>
        </div>
        </div>
        </div>

</div>
</section>



