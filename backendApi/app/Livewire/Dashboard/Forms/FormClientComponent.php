<?php

namespace App\Livewire\Dashboard\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use \App\Services\ApiQueries;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;

class FormClientComponent extends Component
{
    use HandlesHttpErrors;
    public string $uuid;
    public int | null $id;
    public $name;
    public $email;
    public $tax_id;
    public $phone;
    public $address;
    public $company_id;
    public $country_id;
    public $province_id;
    public $city_id;
    public $complement;
    public $neighborhood;
    public $postal_code;
    public $recipient;
    public $notes;
    public $successfulMessage;
    public $apiBaseUrl;
    public $client_address;
    public $client_complement;
    public $client_neighborhood;
    public $client_postal_code;
    public $client_country;
    public $client_province;
    public $client_city;
    public $client_all_address_info;
    public $client_country_textarea;
    public $client_province_textarea;
    public $client_city_textarea;
     protected $rules = [
        'name' => 'required',
        'tax_id' => 'required',
        'phone' => 'required',
        'email' => 'required',
        //'address' => 'required',
        'recipient' => 'required',
        //'notes' => 'required',
        //'country_id' => 'required',
        //'city_id' => 'required',
        //'province_id' => 'required',
        //'complement' => 'required',
        //'neighborhood' => 'required',
        //'postal_code' => 'required',
    ];


    public function mount(string | null $uuid = null, int | null $id = null) : void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->uuid = $uuid ?? '';
            $this->id = $id ?? null;
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
        return view('livewire.dashboard.forms.form-client-component')->with([
            'countries' =>$this->GetCountries(),
            'provinces' =>$this->GetProvinces(),
            'cities' =>$this->GetCities(),
        ]);
    }

     public function Store () : void
    {
        try {
            if (!$this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->post("{$this->apiBaseUrl}/clients", [
                    'name' => $this->name,
                    'email' => $this->email,
                    'tax_id' => $this->tax_id,
                    'phone' => $this->phone,
                    'address' => $this->client_address ?? '',
                    'country_id' => $this->client_country ?? '',
                    'province_id' => $this->client_province ?? '',
                    'city_id' => $this->client_city ?? '',
                    'complement' => $this->client_complement ?? '',
                    'neighborhood' => $this->client_neighborhood ?? '',
                    'postal_code' => $this->client_postal_code ?? '',
                    'recipient' => $this->recipient,
                    'notes' => $this->notes,
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
                        $this->errorAlert($data['message'] ?? 'Não foi possível criar o cliente.');
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
                        'tax_id',
                        'phone',
                        'address',
                        'country_id',
                        'province_id',
                        'city_id',
                        'complement',
                        'neighborhood',
                        'postal_code',
                        'recipient',
                        'notes',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar o cliente. Contacte o administrador do sistema.');
        }
    }


    public function Edit () : void
    {
        try {
            if ($this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/clients/{$this->uuid}");

                if ($response->failed()) {
                    $this->handleHttpError($response);
                    return;
                }

                if ($response->successful()) {
                    $client = $response->json('data');
                    $this->name = $client['name'] ?? '';
                    $this->email = $client['email'] ?? '';
                    $this->tax_id = $client['tax_id'] ?? '';
                    $this->phone = $client['phone'] ?? '';
                    $this->address = $client['address'] ?? '';
                    $this->company_id = $client['company_id'] ?? '';
                    $this->country_id = $client['country_id'] ?? '';
                    $this->province_id = $client['province_id'] ?? '';
                    $this->city_id = $client['city_id'] ?? '';
                    $this->complement = $client['complement'] ?? '';
                    $this->neighborhood = $client['neighborhood'] ?? '';
                    $this->postal_code = $client['postal_code'] ?? '';
                    $this->recipient = $client['recipient'] ?? '';
                    $this->notes = $client['notes'] ?? '';
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os dados do cliente. Contacte o administrador do sistema.');
        }
    }

    public function Update () : void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->put("{$this->apiBaseUrl}/clients/{$this->uuid}",[
                'name' => $this->name,
                'email' => $this->email,
                'tax_id' => $this->tax_id,
                'phone' => $this->phone,
                'address' => $this->client_address ?? '',
                'company_id' => $this->company_id,
                'country_id' => $this->client_country ?? '',
                'province_id' => $this->client_province ?? '',
                'city_id' => $this->client_city ?? '',
                'complement' => $this->client_complement ?? '',
                'neighborhood' => $this->client_neighborhood ?? '',
                'postal_code' => $this->client_postal_code ?? '',
                'recipient' => $this->recipient,
                'notes' => $this->notes,
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
                    $this->errorAlert($data['message'] ?? 'Não foi possível atualizar o cliente.');
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
            $this->errorAlert('Ocorreu um erro ao atualizar o cliente. Contacte o administrador do sistema.');
        }
    }

    public function GetClientAdressDetailsOnTextFields ($uuid) {
        try {
            $this->uuid = $uuid;
            $response = null;

            if ($this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])
                    ->get("{$this->apiBaseUrl}/clients/{$this->uuid}");
            } else if ($this->id) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])
                    ->get("{$this->apiBaseUrl}/clients/{$this->id}");
            }

            if ($response && $response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            if ($response && $response->successful()) {
                $client = $response->json('data');
                $this->client_address = $client['address'] ?? '';
                $this->client_complement = $client['complement'] ?? '';
                $this->client_neighborhood = $client['neighborhood'] ?? '';
                $this->client_postal_code = $client['postal_code'] ?? '';
                $this->client_country = $client['country_id'] ?? '';
                $this->client_province = $client['province_id'] ?? '';
                $this->client_city = $client['city_id'] ?? '';

                $this->client_country_textarea = $client['country']['name'] ?? '';
                $this->client_province_textarea = $client['province']['name'] ?? '';
                $this->client_city_textarea = $client['city']['name'] ?? '';
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os detalhes do endereço. Contacte o administrador do sistema.');
        }
    }

    public function GetCountries()
    {
        try {
            $countries = new ApiQueries();
            return $countries->GetCountryFromService();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return collect();
        }
    }

    public function GetProvinces()
    {
        try {
            $provinces = new ApiQueries();
            return $provinces->GetProvinceFromService();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return collect();
        }
    }

    public function GetCities()
    {
        try {
            $cities = new ApiQueries();
            return $cities->GetCityFromService();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return collect();
        }
    }

    public function close (): void
    {
        $this->reset([
            'client_address',
            'client_complement',
            'client_neighborhood',
            'client_postal_code',
            'client_country',
            'client_province',
            'client_city',
        ]);
    }

    public function GetValuesToClone() : void
    {
        try {
            if ($this->id) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token'), ])
                    ->get("{$this->apiBaseUrl}/clients/{$this->id}");

                if ($response->failed()) {
                    $this->handleHttpError($response);
                    return;
                }

                if ($response->successful()) {
                    $user = $response->json('data');
                    $this->name = $user['name'];
                    $this->email = $user['email'];
                    $this->tax_id = $user['tax_id'];
                    $this->recipient = $user['recipient'];
                    $this->phone = $user['phone'];
                    $this->notes = $user['notes'];
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os valores para clonar. Contacte o administrador do sistema.');
        }
    }

    public function InsertTextFieldValuesInsideTextArea (): void
    {
        $this->client_all_address_info = implode("\n", array_filter([ // So ira adicionar valores nao nulos as quebras de linhas
        $this->client_address,
        $this->client_complement,
        $this->client_neighborhood,
        $this->client_postal_code,
        $this->client_country_textarea,
        $this->client_province_textarea,
        $this->client_city_textarea,
    ], fn ($value) => !is_null($value) && trim($value) !== ''));

    }

     public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

}

