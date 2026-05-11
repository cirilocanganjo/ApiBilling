<?php

namespace App\Livewire\Dashboard;

use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;

class FormUserComponent extends Component
{
    use HandlesHttpErrors;
    public int | null $id;
    public string $uuid;
    public string $cloned_uuid;
    public bool $isCloning = false;
    public $apiBaseUrl;
    public $successfulMessage;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $type;

    protected $rules = [
    'name' => 'required',
    'email' => 'required',
    'type' => 'required',
    ];




    public function mount(string | null $uuid = null, int | null $id = null): void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->id = $id ?? null;
            $this->uuid = $uuid ?? '';
            $this->cloned_uuid = $cloned_uuid ?? '';
            $this->Edit();
            $this->GetValuesToClone();
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

    #[Layout('layouts.app')]
    public function render() : View
    {
        return view('livewire.dashboard.form-user-component');
    }

    public function GetValuesToClone() : void
    {
        try {
            if ($this->id) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token'), ])
                    ->get("{$this->apiBaseUrl}/users/{$this->id}");

                if ($response->failed()) {
                    $this->handleHttpError($response);
                    return;
                }

                if ($response->successful()) {
                    $user = $response->json('data');
                    $this->name = $user['name'];
                    $this->email = $user['email'];
                    $this->type = $user['type'];
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os valores para clonar. Contacte o administrador do sistema.');
        }
    }

     public function Store()
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])
                ->post("{$this->apiBaseUrl}/users", [
                'name' => $this->name,
                'email' => $this->email,
                'type' => $this->type,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ]);

            if ($response->status() === 422) {
                $errors = $response->json('errors');
                foreach ($errors as $field => $messages) {
                    $this->addError($field, $messages[0]);
                }
                return;
            }

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === false) {
                    $this->errorAlert($data['message'] ?? 'Não foi possível criar o usuário.');
                    return;
                }

                $this->successfulMessage = $data['message'] ?? '';
                LivewireAlert::title('Sucesso')
                    ->text($this->successfulMessage ?? '')
                    ->success()
                    ->withConfirmButton()
                    ->timer(0)
                    ->confirmButtonText('Fechar')
                    ->show();

                $this->resetValidation();
                $this->reset([
                    'name',
                    'email',
                    'password',
                    'password_confirmation',
                    'type',
                ]);
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar o usuário. Contacte o administrador do sistema.');
        }
    }

     public function Update() : void
    {
        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'type' => $this->type,
            ];

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->put("{$this->apiBaseUrl}/users/{$this->uuid}", $data);

            if ($response->status() === 422) {
                $errors = $response->json('errors');
                foreach ($errors as $field => $messages) {
                    $this->addError($field, $messages[0]);
                }
                return;
            }

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === false) {
                    $this->errorAlert($data['message'] ?? 'Não foi possível atualizar o usuário.');
                    return;
                }

                $this->successfulMessage = $data['message'] ?? '';
                LivewireAlert::title('Sucesso')
                    ->text($this->successfulMessage ?? '')
                    ->success()
                    ->withConfirmButton()
                    ->timer(0)
                    ->confirmButtonText('Fechar')
                    ->show();
                return;
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao atualizar o usuário. Contacte o administrador do sistema.');
        }
    }

    public function Edit () : void
    {
        try {
            if ($this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/users/{$this->uuid}");

                if ($response->failed()) {
                    $this->handleHttpError($response);
                    return;
                }

                if ($response->successful()) {
                    $user = $response->json('data');
                    $this->name = $user['name'];
                    $this->email = $user['email'];
                    $this->type = $user['type'];
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os dados do usuário. Contacte o administrador do sistema.');
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
