@section('title', $uuid ? 'Edit Subcategory' : 'New Subcategory')
@section('page-url', route('app.dashboard.subcategories'))
@section('page-breadcrumb', $uuid ? 'Subcategoria / Editar' : 'Subcategoria / Adicionar')
@section('page-title', $uuid ? 'Subcategoria - Editar' : 'Subcategoria - Adicionar')
@section('page-breadcrumb-group', 'Produtos')
@section('homepage-group-url', route('app.dashboard.products'))





 <div>
     <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                 <div class="container-fluid">
<div class="row">
        <div class="col-sm-12">
        <div class="box-content">

                <x-form :uuid="$uuid ?? '' " >

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label control-label">Nome <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                            <input type="text" wire:model='name' class="form-control"  >
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                                <label class="col-sm-3 col-form-label control-label">Categoria <span class="text-danger">*</span> </label>
                                <div class="col-sm-6">
                                      <select wire:model='category_id' class="form-control py-2">
                                            <option value="">Selecionar categoria</option>
                                            @if($categories)
                                                @foreach($categories as $category)
                                                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                </div>
                        </div>

                </x-form>
        </div>
        </div>
        </div>


                </div>

            </div>

