<?php

namespace App\Livewire\Dashboard;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;

class FormCompany extends Component
{
    use HandlesHttpErrors;
    public string $uuid;
    public $name;
    public $address;
    public $email;
    public $phone;
    public $nif;
    public $reference;
    public $status;
    public $successfulMessage;
    public $apiBaseUrl;


    public function mount(string | null $uuid = null) : void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->uuid = $uuid ?? '';
            $this->Edit();
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }


     #[Layout('layouts.dashboard.app')]
    public function render() : View
    {
        return view('livewire.dashboard.form-company')->with([

        ]);
    }

     public function Store () : void
    {
        try {
            if (!$this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->post("{$this->apiBaseUrl}/empresas", [
                    'name' => $this->name,
                    'address' => $this->address,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'nif' => $this->nif,
                    'reference' => $this->reference,
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
                        $this->errorAlert($data['message'] ?? 'Não foi possível criar a empresa.');
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
                        'address',
                        'email',
                        'phone',
                        'nif',
                        'reference',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar a empresa. Contacte o administrador do sistema.');
        }
    }


    public function Edit () : void
    {
        try {
            if ($this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/empresas/{$this->uuid}");

                if ($response->failed()) {
                    $this->handleHttpError($response);
                    return;
                }

                if ($response->successful()) {
                    $company = $response->json('data');
                    $this->name = $company['name'] ?? '';
                    $this->address = $company['address'] ?? '';
                    $this->email = $company['email'] ?? '';
                    $this->phone = $company['phone'] ?? '';
                    $this->nif = $company['nif'] ?? '';
                    $this->reference = $company['reference'] ?? '';
                    $this->status = $company['status'] ?? 'active';
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os dados da empresa. Contacte o administrador do sistema.');
        }
    }

    public function Update () : void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->put("{$this->apiBaseUrl}/empresas/{$this->uuid}",[
                'name' => $this->name,
                'address' => $this->address,
                'email' => $this->email,
                'phone' => $this->phone,
                'nif' => $this->nif,
                'reference' => $this->reference,
                'status' => $this->status,
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
                    $this->errorAlert($data['message'] ?? 'Não foi possível atualizar a empresa.');
                    return;
                }

                $successfulMessage = $data['message'] ?? '';
                LivewireAlert::title('Sucesso')
                    ->text($successfulMessage ?? '')
                    ->success()
                    ->withConfirmButton()
                    ->timer(0)
                    ->confirmButtonText('Fechar')
                    ->show();
                return;
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao atualizar a empresa. Contacte o administrador do sistema.');
        }
    }

}

