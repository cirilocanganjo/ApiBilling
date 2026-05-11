<?php

namespace App\Livewire\Dashboard;

use \App\Services\ApiQueries;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;
use Livewire\WithPagination;

class SupplyComponent extends Component
{
    use HandlesHttpErrors, WithPagination;

    protected $listeners = ['confirmSupplyDeletion'];
    public string $uuid;
    public string $ownerAddressTitleDetail;

    public $supply_id;
    public $searcher;
    public $name;
    public $company_id;
    public $natural_person;
    public $search_company_id;
    public $search_country_id;
    public $search_city_id;
    public $search_province_id;
    public $tax_id;
    public $country_id;
    public $province_id;
    public $city_id;
    public $address;
    public $complement;
    public $neighborhood;
    public $postal_code;
    public $city;
    public $province;
    public $country;
    public $recipient;
    public $contact_person;
    public $notes;
    public $phone;
    public $email;
    public $search;
    public $search_user_id;
    public $perPage = 10;
    public $start_date;
    public $end_date;
    public $apiBaseUrl;
    public $status;
    public $successfulMessage;
    public $supplyItems = [];
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
            $this->GetSupplies();
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.dashboard.supply-component')->with([
            'supplies' => $this->makePaginator(),
            'companies' => $this->GetCompanies(),
            'countries' =>$this->GetCountries(),
            'provinces' =>$this->GetProvinces(),
            'cities' =>$this->GetCities(),
            'users' => $this->GetUsers(),
        ]);
    }

    protected function makePaginator(): LengthAwarePaginator
    {
        try {
            $currentPage = $this->paginationMeta['current_page'] ?? $this->getPage();
            $perPage = $this->paginationMeta['per_page'] ?? $this->perPage;
            $total = $this->paginationMeta['total'] ?? count($this->supplyItems);

            return new LengthAwarePaginator(
                $this->supplyItems,
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

    public function GetSupplies(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/suppliers", [
                'searcher' => $this->searcher,
                'created_by' => $this->search_user_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'page' => $this->getPage(),
                'per_page' => $this->perPage,
            ]);

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->supplyItems = [];
                $this->paginationMeta = [
                    'current_page' => $this->getPage(),
                    'per_page' => $this->perPage,
                    'total' => 0,
                ];
                return;
            }

            $payload = $response->json() ?? [];
            $this->supplyItems = $payload['data'] ?? [];
            $this->paginationMeta = $payload['meta'] ?? [
                'current_page' => $payload['current_page'] ?? $this->getPage(),
                'per_page' => $payload['per_page'] ?? $this->perPage,
                'total' => $payload['total'] ?? count($this->supplyItems),
            ];
        } catch (\Throwable $e) {
            report($e);
            $this->supplyItems = [];
            $this->paginationMeta = [
                'current_page' => $this->getPage(),
                'per_page' => $this->perPage,
                'total' => 0,
            ];
            $this->errorAlert('Ocorreu um erro ao buscar os fornecedores. Contacte o administrador do sistema.');
        }
    }

    public function GetSupplyAddressDetails  ($uuid): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/suppliers/{$uuid}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            if ($response->successful()) {
                $supply = $response->json('data');
                $this->ownerAddressTitleDetail = 'Fornecedor';
                $this->complement = $supply['complement'] ?? '';
                $this->neighborhood = $supply['neighborhood'] ?? '';
                $this->postal_code = $supply['postal_code'] ?? '';
                $this->city = $supply['city']['name'] ?? '';
                $this->country = $supply['country']['name'] ?? '';
                $this->province = $supply['province']['name'] ?? '';
                $this->notes = $supply['notes'] ?? '';
                $this->neighborhood = $supply['neighborhood'] ?? '';
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao buscar os detalhes do endereço. Contacte o administrador do sistema.');
        }
    }



    public function closeAddressDetailModal (): void
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

    public function Edit(string | null $uuid = null)
    {
        try {
            $this->uuid = $uuid ?? '';
            $this->status = true;
            return redirect()->route('app.dashboard.form.supplier', [
                'uuid' => $this->uuid
            ]);
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return redirect()->back();
        }
    }



    public function Delete(string $uuid): void
    {
        $this->supply_id = $uuid;
        $this->GetSupplies();

        LivewireAlert::title('Atenção')
            ->text('Deseja realmente, eliminar este registo?')
            ->warning()
            ->withDenyButton()
            ->withConfirmButton()
            ->confirmButtonText('Sim, confirmar')
            ->denyButtonText('Não, cancelar')
            ->withOptions(['allowOutsideClick' => false])
            ->timer(0)
            ->onConfirm('confirmSupplyDeletion')
            ->show();
    }

    public function confirmSupplyDeletion(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->delete("{$this->apiBaseUrl}/suppliers/{$this->supply_id}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            $data = $response->json();
            if (isset($data['success']) && $data['success'] === false) {
                $this->errorAlert($data['message'] ?? 'Não foi possível deletar o fornecedor.');
                return;
            }

            if ($response->successful()) {
                $this->GetSupplies();
                $this->successAlert($data['message'] ?? '');
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao deletar o fornecedor. Contacte o administrador do sistema.');
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


    public function updatedSearcher(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetSupplies();
    }


    public function updatedSearchCompanyId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetSupplies();
    }


    public function updatedSearchCountryId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetSupplies();
    }

     public function updatedSearchCityId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetSupplies();
    }

        public function Clone ($id) {
           // dd();
        $this->GetSupplies();
        return redirect()->route('app.dashboard.clone.supplier',[
            'id' => $id
        ]);
    }


     public function updatedSearchProvinceId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetSupplies();
    }

     public function updatedSearchUserId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetSupplies();
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
        $this->GetSupplies();
    }


    public function updatedStartDate(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetSupplies();
    }

    public function updatedEndDate(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetSupplies();
    }

    public function CancelEdit(): void
    {
        $this->status = false;
        $this->resetValidation();
        $this->reset([
            'supply_id',
            'name',
            'company_id',
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
        ]);
    }

}

