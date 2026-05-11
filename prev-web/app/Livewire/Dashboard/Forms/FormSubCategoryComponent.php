<?php

namespace App\Livewire\Dashboard\Forms;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;

class FormSubCategoryComponent extends Component
{
    use HandlesHttpErrors;
    public string $uuid;
    public $name;
    public $category_id;
    public $apiBaseUrl;
    public $successfulMessage;
    public array $categories = [];

    protected $rules = [
      'name' => 'required',
      'category_id' => 'required',
    ];


    public function mount(string | null $uuid = null) : void
        {
            try {
                $this->apiBaseUrl = config('services.api.base_url');
                $this->uuid = $uuid ?? '';
                $this->Edit();
                $this->GetCategories();
            } catch (\Throwable $th) {
                report($th);
                $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            }
        }

    #[Layout('layouts.app')]
    public function render() : View
    {
        return view('livewire.dashboard.forms.form-sub-category-component')->with([

        ]);
    }

     public function Store(): void
    {
        try {
            if (!$this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->post("{$this->apiBaseUrl}/subcategories", [
                    'name' => $this->pull('name'),
                    'category_id' => $this->pull('category_id'),
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
                        $this->errorAlert($data['message'] ?? 'Não foi possível criar a subcategoria.');
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
                        'category_id',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar a subcategoria. Contacte o administrador do sistema.');
        }
    }

    public function Update(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->put("{$this->apiBaseUrl}/subcategories/{$this->uuid}", [
                'name' => $this->name,
                'category_id' => $this->category_id,
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
                    $this->errorAlert($data['message'] ?? 'Não foi possível atualizar a subcategoria.');
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
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao atualizar a subcategoria. Contacte o administrador do sistema.');
        }
    }


    public function Edit ()
    {
        try {
            if ($this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/subcategories/{$this->uuid}");

                if ($response->failed()) {
                    $this->handleHttpError($response);
                    return;
                }

                if ($response->successful()) {
                    $sub_category = $response->json('data');
                    $this->name = $sub_category['name'];
                    $this->category_id = $sub_category['category_id'];
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os dados da subcategoria. Contacte o administrador do sistema.');
        }
    }

    public function GetCategories(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/categorias");

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->categories = [];
                return;
            }

            if ($response->successful()) {
                $json = $response->json();
                $this->categories = $json['data'] ?? [];
            }
        } catch (\Throwable $e) {
            report($e);
            $this->categories = [];
            $this->errorAlert('Ocorreu um erro ao buscar as categorias. Contacte o administrador do sistema.');
        }
    }

     public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
