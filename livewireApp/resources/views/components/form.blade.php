@props(['uuid' => ''])
<form wire:submit="{{$uuid ? 'Update' : 'Store'}}">
        {{ $slot ?? '' }}

             <div class="form-group row">
                <div class="col-sm-6 offset-sm-3">
                    <button type="submit" class="btn btn-lg btn-success" data-loading-icon="<i class='fa fa-spinner fa-spin'></i>">
                        {{ $uuid ? 'Atualizar' : 'Gravar' }}
                    </button>
                </div>
            </div>
</form>
