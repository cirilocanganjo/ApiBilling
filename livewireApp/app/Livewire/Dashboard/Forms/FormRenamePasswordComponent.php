<?php

namespace App\Livewire\Dashboard\Forms;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FormRenamePasswordComponent extends Component
{
    use HandlesHttpErrors;
    public $apiBaseUrl;
    public string $uuid;
    public $user_name;
    public $password;
    public $old_password;
    public $password_confirmation;

    protected $rules = [
        "password" => 'required',
        "password_confirmation" => 'required',
    ];


    public function mount(string | null $uuid = null): void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->uuid = $uuid ?? '';
            $this->GetUserName();

        } catch (\Throwable $th) {
            report($th);
            $this->alert('Ocorreu um erro ao realizar a operação, contacte o administrador do sistema.');
        }
    }

     #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.dashboard.forms.form-rename-password-component');
    }

    public function RenamePassword(): void
{
    try {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('access_token')])->put("{$this->apiBaseUrl}/users/rename/password/{$this->uuid}", [
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ]);

        if ($response->status() === 422) {
            foreach ($response->json('errors', []) as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
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

        $this->resetValidation();
        $this->reset(['password', 'password_confirmation']);
        $this->successAlert($data['message'] ?? '');

    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
    }
}

public function GetUserName(): void
{
    try {
        if (!$this->uuid) {
            return;
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/users/{$this->uuid}");

        if ($response->status() === 422) {
            foreach ($response->json('errors', []) as $field => $messages) {
                $this->addError($field, $messages[0]);
        }
            return;
        }

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['success']) && $data['success'] === false) {
                $this->errorAlert($data['message'] ?? '');
                return;
            }

            $user = $data['data'] ?? [];
            $this->user_name = $user['name'] ?? '';
            $this->old_password = $user['password'] ?? '';

            return;
        }

        $this->handleHttpError($response);

    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao obter os dados do utilizador. Contacte o administrador do sistema.');
    }
}

     public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
