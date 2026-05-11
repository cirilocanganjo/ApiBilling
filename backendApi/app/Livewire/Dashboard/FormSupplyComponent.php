<?php

namespace App\Livewire\Dashboard;

use \App\Services\ApiQueries;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;

class FormSupplyComponent extends Component
{
    use HandlesHttpErrors;
    public int | null $id;

    public string $uuid;
    public $apiBaseUrl;
    public $successfulMessage;
    public bool $natural_person = false;
    public $tax_id;
    public $country_id;
    public $province_id;
    public $city_id;
    public $address;
    public $complement;
    public $neighborhood;
    public $postal_code;
    public $contact_person;
    public $notes;
    public $phone;
    public $email;
    public $name;
    public $supply_address;
    public $supply_complement;
    public $supply_neighborhood;
    public $supply_postal_code;
    public $supply_country;
    public $supply_province;
    public $supply_city;
    public $supply_country_textarea;
    public $supply_province_textarea;
    public $supply_city_textarea;
    public $supply_all_address_info;


    protected $rules = [
            'name' => 'required',
            'tax_id' => 'required',
            'country_id' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'complement' => 'required',
            'neighborhood' => 'required',
            'postal_code' => 'required',
            'phone' => 'required',
            'email' => 'required',
    ];

     public function mount(string | null $uuid = null, int | null $id = null): void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->uuid = $uuid ?? '';
            $this->id = $id;
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
        return view('livewire.dashboard.form-supply-component')->with([
            'countries' =>$this->GetCountries(),
            'provinces' =>$this->GetProvinces(),
            'cities' =>$this->GetCities(),
        ]);
    }

     public function Store(): void
    {
        try {
            if (!$this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token')])->post("{$this->apiBaseUrl}/suppliers", [
                    'name' => $this->name,
                    'natural_person' => $this->natural_person,
                    'tax_id' => $this->tax_id,
                    'country_id' => $this->country_id,
                    'province_id' => $this->province_id,
                    'city_id' => $this->city_id,
                    'address' => $this->address,
                    'complement' => $this->complement,
                    'neighborhood' => $this->neighborhood,
                    'postal_code' => $this->postal_code,
                    'contact_person' => $this->contact_person,
                    'notes' => $this->notes,
                    'phone' => $this->phone,
                    'email' => $this->email,
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
                        $this->errorAlert($data['message'] ?? 'Não foi possível criar o fornecedor.');
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
                        'natural_person',
                        'tax_id',
                        'country_id',
                        'province_id',
                        'city_id',
                        'address',
                        'complement',
                        'neighborhood',
                        'postal_code',
                        'contact_person',
                        'notes',
                        'phone',
                        'email',
                        'name',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar o fornecedor. Contacte o administrador do sistema.');
        }
    }

      public function Update(): void
    {
        try {
            if ($this->uuid) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('access_token'),
                ])->put("{$this->apiBaseUrl}/suppliers/{$this->uuid}", [
                    'name' => $this->name,
                    'natural_person' => $this->natural_person,
                    'tax_id' => $this->tax_id,
                    'country_id' => $this->country_id,
                    'province_id' => $this->province_id,
                    'city_id' => $this->city_id,
                    'address' => $this->address,
                    'complement' => $this->complement,
                    'neighborhood' => $this->neighborhood,
                    'postal_code' => $this->postal_code,
                    'contact_person' => $this->contact_person,
                    'notes' => $this->notes,
                    'phone' => $this->phone,
                    'email' => $this->email,
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
                        $this->errorAlert($data['message'] ?? 'Não foi possível atualizar o fornecedor.');
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
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao atualizar o fornecedor. Contacte o administrador do sistema.');
        }
    }


public function Edit(string | null $uuid = null): void
{
    try {
        if ($this->uuid) {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/suppliers/{$this->uuid}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            if ($response->successful()) {
                $supplier = $response->json('data');
                $this->name = $supplier['name'] ?? '';
                $this->natural_person = (bool) ($supplier['natural_person'] ?? false);
                $this->tax_id  = $supplier['tax_id'] ?? '';
                $this->country_id  = $supplier['country_id'] ?? '';
                $this->province_id = $supplier['province_id'] ?? '';
                $this->city_id  = $supplier['city_id'] ?? '';
                $this->address  = $supplier['address'] ?? '';
                $this->complement = $supplier['complement'] ?? '';
                $this->neighborhood  = $supplier['neighborhood'] ?? '';
                $this->postal_code = $supplier['postal_code'] ?? '';
                $this->contact_person  = $supplier['contact_person'] ?? '';
                $this->notes  = $supplier['notes'] ?? '';
                $this->phone = $supplier['phone'] ?? '';
                $this->email = $supplier['email'] ?? '';
            }
        }
    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao buscar os dados do fornecedor. Contacte o administrador do sistema.');
    }
}

public function GetSupplyAdressDetailsOnTextFields ($uuid) {
    try {
        $this->uuid = $uuid;
        $response = null;

        if ($this->uuid) {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])
                ->get("{$this->apiBaseUrl}/suppliers/{$this->uuid}");
        } else if ($this->id) {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])
                ->get("{$this->apiBaseUrl}/suppliers/{$this->id}");
        }

        if ($response && $response->failed()) {
            $this->handleHttpError($response);
            return;
        }

        if ($response && $response->successful()) {
            $supplier = $response->json('data');
            $this->supply_address = $supplier['address'] ?? '';
            $this->supply_complement = $supplier['complement'] ?? '';
            $this->supply_neighborhood = $supplier['neighborhood'] ?? '';
            $this->supply_postal_code = $supplier['postal_code'] ?? '';
            $this->supply_country = $supplier['country_id'] ?? '';
            $this->supply_province = $supplier['province_id'] ?? '';
            $this->supply_city = $supplier['city_id'] ?? '';
        }
    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao buscar os detalhes do endereço. Contacte o administrador do sistema.');
    }
}

public function InsertTextFieldValuesInsideTextArea (): void
    {
        $this->supply_all_address_info = implode("\n", array_filter([ // So ira adicionar valores nao nulos as quebras de linhas
        $this->supply_address,
        $this->supply_complement,
        $this->supply_neighborhood,
        $this->supply_postal_code,
        $this->supply_country_textarea,
        $this->supply_province_textarea,
        $this->supply_city_textarea,
    ], fn ($value) => !is_null($value) && trim($value) !== ''));

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
                    ->get("{$this->apiBaseUrl}/suppliers/{$this->id}");

                if ($response->failed()) {
                    $this->handleHttpError($response);
                    return;
                }

                if ($response->successful()) {
                    $user = $response->json('data');
                    $this->name = $user['name'];
                    $this->tax_id = $user['tax_id'];
                    $this->contact_person = $user['contact_person'];
                    $this->email = $user['email'];
                    $this->natural_person = $user['natural_person'];
                    $this->phone = $user['phone'];
                    $this->notes = $user['notes'];
                }
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os valores para clonar. Contacte o administrador do sistema.');
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

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }
}
