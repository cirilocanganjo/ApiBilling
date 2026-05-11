  <div class="info">
            @if (session()->has('user'))
            <span wire:click='logout' class='cursor-pointer text-white'>{{ session('user.name') }}</span>
            @endif
 </div>
