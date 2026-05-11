<?php

namespace App\Livewire\Dashboard;

use App\Services\ApiQueries;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;
use Livewire\WithPagination;
use Termwind\Components\Li;

class BrandComponent extends Component
{
    use HandlesHttpErrors,WithPagination;

    protected $listeners = ['confirmBrandDeletion'];

    public string $uuid;
    public $brand_id;
    public $name;
    public $search;
    public $search_company_id;
    public $search_user_id;
    public $perPage = 10;
    public $start_date;
    public $end_date;
    public $apiBaseUrl;
    public $company_id;
    public $status;
    public $successfulMessage;
    public $brandItems = [];
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
            $this->GetBrands();

        } catch (\Throwable $th) {
           report($th);
           $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

    #[Layout('layouts.dashboard.app')]
    public function render(): View
    {
            return view('livewire.dashboard.brand-component')->with([
                'brands' => $this->makePaginator(),
                'companies' => $this->GetCompanies(),
                'users' => $this->GetUsers(),
            ]);
    }

 protected function makePaginator(): LengthAwarePaginator
{
    try {
        return new LengthAwarePaginator(
            $this->brandItems,
            $this->paginationMeta['total'] ?? 0,
            $this->paginationMeta['per_page'] ?? 10,
            $this->paginationMeta['current_page'] ?? 1,
            ['path' => url()->current()]
        );
    } catch (\Throwable $e) {
        report($e);
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


    public function GetBrands(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/brands", [
                'name' => $this->search,
                'company_id' => $this->search_company_id,
                'created_by' => $this->search_user_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'page' => $this->getPage(),
                'per_page' => $this->perPage,
            ]);

             if ($response->failed()) {
                $this->handleHttpError($response);
                    return;
            }

            $data = $response->json();
            if (isset($data['success']) && $data['success'] === false) {
                $this->errorAlert($data['message'] ?? '');
                return;
            }

            $payload = $response->json() ?? [];
            $this->brandItems = $payload['data'] ?? [];
            $this->paginationMeta = $payload['meta'] ?? [
                'current_page' => $payload['current_page'] ?? $this->getPage(),
                'per_page' => $payload['per_page'] ?? $this->perPage,
                'total' => $payload['total'] ?? count($this->brandItems),
            ];
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

    public function Store(): void
{
    try {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('access_token'),
        ])->post("{$this->apiBaseUrl}/brands", [
            'name' => $this->pull('name'),
            'company_id' => $this->pull('company_id'),
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
        $this->reset(['name', 'company_id']);
        $this->GetBrands();
        $this->successAlert($data['message'] ?? '');

    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
    }
}


   public function Edit(string|null $uuid = null)
{
    try {
        $this->uuid = $uuid ?? '';
        $this->status = true;

        return redirect()->route('app.dashboard.edit.brand', [
            'uuid' => $uuid
        ]);
    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        return redirect()->back();
    }
}


public function Update(): void
{
    try {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('access_token'),
        ])->put("{$this->apiBaseUrl}/brands/{$this->brand_id}", [
            'name' => $this->name,
            'company_id' => $this->company_id,
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
        $this->GetBrands();
        $this->successAlert($data['message'] ?? '');

    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
    }
}


    public function Delete(string $uuid): void
    {
        try {
            $this->brand_id = $uuid;
            $this->GetBrands();

            LivewireAlert::title('Atenção')
                ->text('Deseja realmente, eliminar este registo?')
                ->warning()
                ->withDenyButton()
                ->withConfirmButton()
                ->confirmButtonText('Sim, confirmar')
                ->denyButtonText('Não, cancelar')
                ->withOptions(['allowOutsideClick' => false])
                ->timer(0)
                ->onConfirm('confirmBrandDeletion')
                ->show();

        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

 public function confirmBrandDeletion(): void
{
    try {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('access_token')])->delete("{$this->apiBaseUrl}/brands/{$this->brand_id}");

        if ($response->failed()) {
            $this->handleHttpError($response);
            return;
        }

        $data = $response->json();

        if (isset($data['success']) && $data['success'] === false) {
            $this->errorAlert($data['message'] ?? '');
            return;
        }

        $this->GetBrands();
        $this->successAlert($data['message'] ?? '');

    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
    }
}

public function GetUsers()
{
    try {
        return (new ApiQueries())->GetUserFromService();
    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        return collect();
    }
}



    public function updatedSearch(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetBrands();
    }

    public function updatedSearchUserId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetBrands();
    }

    public function updatedSearchCompanyId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetBrands();
    }


    public function updatedStartDate(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetBrands();
    }

    public function updatedEndDate(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetBrands();
    }

    public function GetCompanies()
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


       public function updatedPerPage()
    {
        if ($this->status) {
            return;
        }
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->GetBrands();
    }
}
