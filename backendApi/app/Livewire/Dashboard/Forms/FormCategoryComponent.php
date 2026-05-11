<?php

namespace App\Livewire\Dashboard\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;

class FormCategoryComponent extends Component
{
    use HandlesHttpErrors;
    public string $uuid;
    public $name;
    public $description;
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

    protected $rules = [
        "name" => 'required',
        'description' => 'nullable',
    ];


     #[Layout('layouts.app')]
    public function render() : View
    {
        return view('livewire.dashboard.forms.form-category-component')->with([

        ]);
    }

     public function Store () : void
    {
        try {
            if (!$this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->post("{$this->apiBaseUrl}/categories", [
                    'name' => $this->name,
                    'description' => $this->description,
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
                        $this->errorAlert($data['message'] ?? 'Não foi possível criar a categoria.');
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
                        'description',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar a categoria. Contacte o administrador do sistema.');
        }
    }


    public function Edit () : void
    {
        try {
            if ($this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/categories/{$this->uuid}");

                if ($response->failed()) {
                    $this->handleHttpError($response);
                    return;
                }

                if ($response->successful()) {
                    $category = $response->json('data');
                    $this->name = $category['name'];
                    $this->description = $category['description'];
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os dados da categoria. Contacte o administrador do sistema.');
        }
    }

    public function Update () : void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->put("{$this->apiBaseUrl}/categories/{$this->uuid}",[
                'name' => $this->name,
                'description' => $this->description,
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
                    $this->errorAlert($data['message'] ?? 'Não foi possível atualizar a categoria.');
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
            $this->errorAlert('Ocorreu um erro ao atualizar a categoria. Contacte o administrador do sistema.');
        }
    }

     public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

}
