<?php

namespace App\Livewire\Dashboard;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class AuthenticatedUserInfoComponent extends Component
{
    protected $listeners = ['whenConfirm' =>'confirmLogout'];


    public function render()
    {
        return view('livewire.dashboard.authenticated-user-info-component');
    }

    public function logout() : void
    {
        try {
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

        } catch (\Throwable $th) {

           LivewireAlert::title('ERRO')
            ->text('Ocorreu um erro ao realizar operação, contacte o administrador do sistema.')
            ->withDenyButton()
            ->withDenyButton('red')
            ->denyButtonText('Fechar')
            ->error()
            ->withOptions(['allowOutsideClick' => false])
            ->timer(0)
            ->show();
            
        }
    }

    public function confirmLogout () {
        session()->flush(); // Limpar a sessão por completo.
        return redirect()->route('app.login');
    }
}
