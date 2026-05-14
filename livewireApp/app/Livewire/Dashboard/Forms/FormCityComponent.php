<?php

namespace App\Livewire\Dashboard\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;

class FormCityComponent extends Component
{
    use HandlesHttpErrors;
    public string $uuid;
    public $name;
    public $province_id;
    public $iso_code;
    public $successfulMessage;
    public $apiBaseUrl;

    protected $rules = [
        'name' => 'required',
        'iso_code' => 'required',
        'province_id' => 'required',
    ];


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
        return view('livewire.dashboard.forms.form-city-component')->with([

        ]);
    }

     public function Store () : void
    {
        try {
            if (!$this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->post("{$this->apiBaseUrl}/cities", [
                    'name' => $this->name,
                    'province_id' => $this->province_id,
                    'iso_code' => $this->iso_code,
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
                        $this->errorAlert($data['message'] ?? 'Não foi possível criar a cidade.');
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
                        'province_id',
                        'iso_code',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar a cidade. Contacte o administrador do sistema.');
        }
    }


    public function Edit () : void
    {
        try {
            if ($this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/cities/{$this->uuid}");

                if ($response->failed()) {
                    $this->handleHttpError($response);
                    return;
                }

                if ($response->successful()) {
                    $city = $response->json('data');
                    $this->name = $city['name'] ?? '';
                    $this->province_id = $city['province_id'] ?? '';
                    $this->iso_code = $city['iso_code'] ?? '';
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os dados da cidade. Contacte o administrador do sistema.');
        }
    }

    public function Update () : void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->put("{$this->apiBaseUrl}/cities/{$this->uuid}",[
                'name' => $this->name,
                'province_id' => $this->province_id,
                'iso_code' => $this->iso_code,
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
                    $this->errorAlert($data['message'] ?? 'Não foi possível atualizar a cidade.');
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
            $this->errorAlert('Ocorreu um erro ao atualizar a cidade. Contacte o administrador do sistema.');
        }
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

}

