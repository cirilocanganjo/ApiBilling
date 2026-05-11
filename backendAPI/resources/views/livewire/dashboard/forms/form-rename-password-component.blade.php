@section('title',  'Rename Password')
@section('page-url', route('app.dashboard.users'))
@section('page-breadcrumb', $uuid ? 'Utilizador / Renomear palavra-passe' : '')
@section('page-title', $uuid ? 'Utilizador - Renomear palavra-passe' : '')


<div>

     <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">

                    <div class="row">
                            <div class="col-sm-12">
                                <div class="box-content">
                                    <form wire:submit="RenamePassword">

                                                    <div  class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Nome</label>
                                                        <div class="col-sm-6">
                                                        <input wire:model="user_name" type="text" class="form-control" readonly  />
                                                        </div>
                                                    </div>


                                                    <div  class="form-group row">
                                                        <label class="col-sm-3 col-form-label control-label">Palavra-passe <span class="text-danger">*</span></label>
                                                        <div class="col-sm-6">
                                                        <input wire:model="password" type="password" class="form-control"  />
                                                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>

                                                    <div  class=" form-group row">
                                                            <label class="col-sm-3 col-form-label control-label">Confirmar palavra-passe <span class="text-danger">*</span></label>
                                                            <div class="col-sm-6">
                                                            <input wire:model="password_confirmation" type="password" class="form-control"  />
                                                            @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                    </div>

                                                    <div class="form-group row">
                                                    <div class="col-sm-6 offset-sm-3">
                                                        <button type="submit" class="btn btn-lg btn-success" data-loading-icon="<i class='fa fa-spinner fa-spin'></i>">
                                                            Gravar
                                                        </button>
                                                    </div>
                                                    </div>
                                    </form>


                                </div>
                            </div>
                    </div>
                </div>

            </div>

</div>

