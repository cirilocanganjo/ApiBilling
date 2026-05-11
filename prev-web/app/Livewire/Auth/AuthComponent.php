<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AuthComponent extends Component
{
    use HandlesHttpErrors;

     public $email , $password, $apiBaseUrl;
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required'  => 'O campo email é obrigatório',
        'email.email'   => 'O email deve ser válido',
        'password.required' => 'O campo senha é obrigatório',
    ];

    public function mount()
    {
        try {
          $this->apiBaseUrl = config('services.api.base_url');
        } catch (\Throwable $th) {
         report($th);
         $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

    #[Layout('layouts.auth.app')]
    public function render() : View
    {
        return view('livewire.auth.auth-component');
    }

    public function authentication()
{
    try {
        $response = Http::withHeaders([
            'Accept' => 'application/json'])->post("{$this->apiBaseUrl}/login", [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if ($response->status() === 422) {
            $errors = $response->json('errors');
            foreach ($errors as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
            return;
        }

        if ($response->status() === 401) {
            LivewireAlert::title('Atenção')
                ->text($response->json('message'))
                ->error()
                ->withConfirmButton()
                ->confirmButtonText('Fechar')
                ->show();
            return;
        }

        if ($response->status() === 403) {
            LivewireAlert::title('Atenção')
                ->text($response->json('message'))
                ->warning()
                ->withConfirmButton()
                ->confirmButtonText('Fechar')
                ->timer(0)
                ->show();
            return;
        }

        if ($response->failed()) {
            $this->handleHttpError($response);
            return;
        }

        $data = $response->json();
        if (isset($data['success']) && $data['success'] === false) {
            $this->errorAlert($data['message'] ?? '');
            return;
        }

        if ($response->successful()) {
            session([
                'access_token' => $data['access_token'],
                'user' => $data['user'],
            ]);

            if (in_array($data['user']['type'], ['SUPER_ADMIN', 'ADMIN'])) {
                return redirect()->route('app.dashboard');
            }
        }
    } catch (\Throwable $th) {
        report($th);
        $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
    }
}




     public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
