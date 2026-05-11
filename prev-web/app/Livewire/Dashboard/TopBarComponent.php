<?php

namespace App\Livewire\Dashboard;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class TopBarComponent extends Component
{
    public $user;
    protected $listeners = ['confirmLogout'];

    public function mount () {
        $this->user = session('user');
    }

    public function render()
    {
        return view('livewire.dashboard.top-bar-component');
    }

     public function logout()
    {
        LivewireAlert::title('ATENÇÃO')
        ->text('Deseja realmente terminar sessão?')
        ->withConfirmButton()
        ->confirmButtonText('Confirmar')
        ->warning()
        ->withDenyButton()
        ->denyButtonText('Cancelar')
        ->withOptions(['allowOutsideClick' => false])
        ->timer('30000')
        ->onConfirm('confirmLogout')
        ->show();
    }

    public function confirmLogout () {
        session()->flush(); // Limpar a sessão por completo.
        return redirect()->route('app.login');
    }
}
