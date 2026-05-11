<?php

namespace App\Livewire\Dashboard;

use App\Services\ApiQueries;
use App\Support\Http\HandlesHttpErrors;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class ClientComponent extends Component
{
    use HandlesHttpErrors, WithPagination;
    protected $listeners = ['confirmClientDeletion'];
    public string $uuid;
    public string $ownerAddressTitleDetail;
    public $client_id;
    public $name;
    public $email;
    public $searcher;
    public $tax_id;
    public $search;
    public $search_company_id;
    public $country_id;
    public $company_id;
    public $province_id;
    public $city_id;
    public $phone;
    public $address;
    public $complement;
    public $neighborhood;
    public $postal_code;
    public $city;
    public $country;
    public $province;
    public $recipient;
    public $notes;
    public $start_date;
    public $end_date;
    public $apiBaseUrl;
    public $search_user_id;
    public $search_country_id;
    public $search_province_id;
    public $status;
    public $successfulMessage;
    public $perPage = 10;
    public $clientItems = [];
    public $paginationMeta = [
        'current_page' => 1,
        'per_page' => 10,
        'total' => 0,
    ];



    public function mount(): void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->status = false;
            $this->GetClients();
        } catch (\Throwable $th) {
           report($th);
           LivewireAlert::title('ERRO')
            ->text('Ocorreu um erro ao realizar operação, contacte o administrador do sistema.')
            ->withDenyButton()
            ->withConfirmButton()
            ->confirmButtonText('Fechar')
            ->timer(0)
            ->show();
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.dashboard.client-component')->with([
            'clients' => $this->makePaginator(),
            'users' => $this->GetUsers(),
            'companies' => $this->GetCompanies(),
            'countries' =>$this->GetCountries(),
            'provinces' =>$this->GetProvinces(),
            'cities' =>$this->GetCities(),
            'phone' =>$this->GetCities(),
        ]);
    }

    protected function makePaginator(): LengthAwarePaginator // Cria o paginador manualmente para evitar problemas com Livewire
    {
        try {
            $currentPage = $this->paginationMeta['current_page'] ?? $this->getPage();
            $perPage = $this->paginationMeta['per_page'] ?? $this->perPage;
            $total = $this->paginationMeta['total'] ?? count($this->clientItems);

            return new LengthAwarePaginator(
                $this->clientItems,
                $total,
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } catch (\Throwable $th) {
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');

            return new LengthAwarePaginator(
                collect(),
                0,
                10,
                1,
                ['path' => url()->current()]
            );
        }
    }

    public function GetClients(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/clients", [
                'searcher' => $this->searcher,
                'created_by' =>$this->search_user_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'page' => $this->getPage(),
                'per_page' => $this->perPage,
            ]);

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->clientItems = [];
                $this->paginationMeta = [
                    'current_page' => $this->getPage(),
                    'per_page' => $this->perPage,
                    'total' => 0,
                ];
                return;
            }

            $payload = $response->json() ?? [];
            $this->clientItems = $payload['data'] ?? [];
            $this->paginationMeta = $payload['meta'] ?? [
                'current_page' => $payload['current_page'] ?? $this->getPage(),
                'per_page' => $payload['per_page'] ?? $this->perPage,
                'total' => $payload['total'] ?? count($this->clientItems),
            ];
        } catch (\Throwable $e) {
            report($e);
            $this->clientItems = [];
            $this->paginationMeta = [
                'current_page' => $this->getPage(),
                'per_page' => $this->perPage,
                'total' => 0,
            ];
            $this->errorAlert('Ocorreu um erro ao buscar os clientes. Contacte o administrador do sistema.');
        }
    }

    public function Store () : void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->post("{$this->apiBaseUrl}/clients", [
                'name' => $this->pull('name'),
                'company_id' => $this->pull('company_id'),
                'tax_id' => $this->pull('tax_id'),
                'country_id' => $this->pull('country_id'),
                'province_id' => $this->pull('province_id'),
                'city_id' => $this->pull('city_id'),
                'phone' => $this->pull('phone'),
                'email' => $this->pull('email'),
                'address' => $this->pull('address'),
                'complement' => $this->pull('complement'),
                'neighborhood' => $this->pull('neighborhood'),
                'postal_code' => $this->pull('postal_code'),
                'recipient' => $this->pull('recipient'),
                'notes' => $this->pull('notes'),
            ]);

            if ($response->status() === 422) {
                $this->GetClients();
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

            $this->successfulMessage = $response->json('message');
            $this->resetValidation();
            LivewireAlert::title('Sucesso')
                ->text($this->successfulMessage ?? '')
                ->success()
                ->withConfirmButton()
                ->timer(0)
                ->confirmButtonText('Fechar')
                ->show();

            $this->GetClients();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar o cliente. Contacte o administrador do sistema.');
        }
    }

     public function GetClientAddressDetails  ($uuid): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/clients/{$uuid}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            $this->ownerAddressTitleDetail = 'Cliente';
            $client = $response->json('data');
            $this->complement = $client['complement'] ?? '';
            $this->neighborhood = $client['neighborhood'] ?? '';
            $this->postal_code = $client['postal_code'] ?? '';
            $this->city = $client['city']['name'] ?? '';
            $this->country = $client['country']['name'] ?? '';
            $this->province = $client['province']['name'] ?? '';
            $this->notes = $client['notes'] ?? '';
            $this->neighborhood = $client['neighborhood'] ?? '';
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao carregar os detalhes do endereço do cliente. Contacte o administrador do sistema.');
        }

    }

     public function closeAddressDetailModal ()
    {
        $this->reset([
            'neighborhood',
            'postal_code',
            'city',
            'province',
            'country',
            'recipient',
            'notes'
        ]);

    }



    public function Edit (string | null $uuid = null)
    {
        try {
            $this->uuid = $uuid ?? '';
            $this->status = true;
            return redirect()->route('app.dashboard.form.client', [
                'uuid' => $this->uuid
            ]);
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return redirect()->back();
        }
    }


    public function Update () : void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->put("{$this->apiBaseUrl}/clients/{$this->client_id}",[
                'name' => $this->name,
                'company_id' => $this->company_id,
                'tax_id' => $this->tax_id,
                'country_id' => $this->country_id,
                'province_id' => $this->province_id,
                'city_id' => $this->city_id,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'complement' => $this->complement,
                'neighborhood' => $this->neighborhood,
                'postal_code' => $this->postal_code,
                'recipient' => $this->recipient,
                'notes' => $this->notes,
            ]);

            if ($response->status() === 422) {
                $this->GetClients();
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

            $this->GetClients();
            $this->successfulMessage = $response->json('message');
            LivewireAlert::title('Sucesso')
                ->text($this->successfulMessage ?? '')
                ->success()
                ->withConfirmButton()
                ->timer(0)
                ->confirmButtonText('Fechar')
                ->show();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao atualizar o cliente. Contacte o administrador do sistema.');
        }

    }

    public function Delete (string $uuid)
    {
        $this->client_id = $uuid;
        $this->GetClients();
        LivewireAlert::title('Atenção')
            ->text('Deseja realmente, eliminar este registo?')
            ->warning()
            ->withDenyButton()
            ->withConfirmButton()
            ->confirmButtonText('Sim, confirmar')
            ->denyButtonText('Não, cancelar')
            ->withOptions(['allowOutsideClick' => false])
            ->timer(0)
            ->onConfirm('confirmClientDeletion')
            ->show();
    }

    public function confirmClientDeletion ()

    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->delete("{$this->apiBaseUrl}/clients/{$this->client_id}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            $data = $response->json();
            if (isset($data['success']) && $data['success'] === false) {
                $this->errorAlert($data['message'] ?? 'Não foi possível deletar o cliente.');
                return;
            }

            $this->GetClients();
            $this->successAlert($data['message'] ?? '');
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao deletar o cliente. Contacte o administrador do sistema.');
        }
        }

      public function Clone ($id) {
        $this->GetClients();
        return redirect()->route('app.dashboard.clone.client',[
            'id' => $id
        ]);
    }

    public function updatedPerPage()
    {
        if ($this->status) {
            return;
        }
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->GetClients();
    }

     public function GetCompanies ()
     {
        try {
            $companies = new ApiQueries();
            return $companies->GetCompanyFromService();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return collect();
        }
    }


    public function GetUsers()
    {
        try {
            $users = new ApiQueries();
            return $users->GetUserFromService();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return collect();
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


    public function updatedSearcher() : void
    {
        if ($this->status) {
            return;
        }
        $this->GetClients();
    }

    public function updatedSearchUserId() : void
    {
        if ($this->status) {
            return;
        }
        $this->GetClients();
    }

    public function updatedSearchProvinceId() : void
    {
        if ($this->status) {
            return;
        }
        $this->GetClients();
    }

    public function updatedSearchCompanyId() : void
    {
        if ($this->status) {
            return;
        }
        $this->GetClients();
    }


    public function CancelEdit ()
    {
        $this->status = false;
        $this->resetValidation();
        $this->reset([
            'client_id','name','company_id','tax_id', 'country_id',
             'province_id','city_id', 'phone', 'email', 'address',
             'complement','neighborhood','postal_code', 'recipient',
             'notes'
        ]);
    }

   public function updatedSearchCountryId() : void
   {
        if ($this->status) {
            return;
        }
        $this->GetClients();
    }

        public function updatedStartDate() : void
        {
            if ($this->status) {
                return;
            }
            $this->GetClients();
        }

    public function updatedEndDate() : void
    {
        if ($this->status) {
            return;
        }
        $this->GetClients();
    }

}
