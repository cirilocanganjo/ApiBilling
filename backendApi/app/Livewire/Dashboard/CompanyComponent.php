<?php

namespace App\Livewire\Dashboard;

use App\Services\ApiQueries;
use App\Support\Http\HandlesHttpErrors;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyComponent extends Component
{
    use HandlesHttpErrors, WithPagination;

    protected $listeners = ['confirmCompanyDeletion'];

    public string $uuid;
    public $company_id;
    public $search;
    public $search_address;
    public $name;
    public $nif;
    public $search_user_id;
    public $search_nif;
    public $company_status;
    public $email;
    public $phone;
    public $address;
    public $reference;
    public $perPage = 10;
    public $start_date;
    public $end_date;
    public $apiBaseUrl;
    public $status;
    public $successfulMessage;
    public $companyItems = [];
    public $paginationMeta = [
        'current_page' => 1,
        'per_page' => 10,
        'total' => 0,
    ];

    protected $rules = [
                'name' => 'required',
                'email' => 'required',
                'nif' => 'required|min:14',                
                'phone' => 'required',               
                'address' => 'required',                
                'reference' => 'required', 
    ];

    protected $messages = [
            'name.required' => 'Campo obrigatório',
            'email.required' => 'Campo obrigatório',
            'email.unique' => 'Email já cadastrado',
            'nif.required' => 'Campo obrigatório',
            'nif.min' => 'O NIF deve ter no mínimo 15 caracteres',
            'phone.required' => 'Campo obrigatório',
            'address.required' => 'Campo obrigatório',
            'reference.required' => 'Campo obrigatório',
    ];



    public function mount(): void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->status = false;
            $this->GetCompanies();
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

    #[Layout('layouts.dashboard.app')]
    public function render(): View
    {
        return view('livewire.dashboard.company-component')->with([
            'companies' => $this->makePaginator(),
            'users' => $this->GetUsers(),
        ]);
    }

    protected function makePaginator(): LengthAwarePaginator
    {
        try {
            $currentPage = $this->paginationMeta['current_page'] ?? $this->getPage();
            $perPage = $this->paginationMeta['per_page'] ?? $this->perPage;
            $total = $this->paginationMeta['total'] ?? count($this->companyItems);

            return new LengthAwarePaginator(
                $this->companyItems,
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

    public function GetCompanies(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/empresas", [
                'name' => $this->search,
                'address' => $this->search_address,
                'nif' => $this->search_nif,
                'created_by' => $this->search_user_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'page' => $this->getPage(),
                'per_page' => $this->perPage,
            ]);

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->companyItems = [];
                $this->paginationMeta = [
                    'current_page' => $this->getPage(),
                    'per_page' => $this->perPage,
                    'total' => 0,
                ];
                return;
            }

            $payload = $response->json() ?? [];
            $this->companyItems = $payload['data'] ?? [];
            $this->paginationMeta = $payload['meta'] ?? [
                'current_page' => $payload['current_page'] ?? $this->getPage(),
                'per_page' => $payload['per_page'] ?? $this->perPage,
                'total' => $payload['total'] ?? count($this->companyItems),
            ];
        } catch (\Throwable $e) {
            report($e);
            $this->companyItems = [];
            $this->paginationMeta = [
                'current_page' => $this->getPage(),
                'per_page' => $this->perPage,
                'total' => 0,
            ];
            $this->errorAlert('Ocorreu um erro ao buscar as empresas. Contacte o administrador do sistema.');
        }
    }

    public function Store(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->post("{$this->apiBaseUrl}/empresas", [
                'name' => $this->pull('name'),
                'nif' => $this->pull('nif'),
                'email' => $this->pull('email'),
                'phone' => $this->pull('phone'),
                'address' => $this->pull('address'),
                'reference' => $this->pull('reference'),
            ]);

            if ($response->status() === 422) {
                $this->GetCompanies();
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

            $this->GetCompanies();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar a empresa. Contacte o administrador do sistema.');
        }
    }

    public function Edit(string | null $uuid = null)
    {

        try {
            $this->uuid = $uuid ?? '';
            $this->status = true;

            return redirect()->route('app.dashboard.edit.company', [
                'uuid' => $this->uuid
            ]);
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return redirect()->back();
        }
    }

    public function Update(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->put("{$this->apiBaseUrl}/empresas/{$this->company_id}", [
                'name' => $this->name,
                'nif' => $this->nif,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'reference' => $this->reference,
                'reference' => $this->reference,
                'status' =>$this->company_status,
            ]);

            if ($response->status() === 422) {
                $this->GetCompanies();
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

            $this->GetCompanies();
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
            $this->errorAlert('Ocorreu um erro ao atualizar a empresa. Contacte o administrador do sistema.');
        }
    }

    public function Delete(string $uuid): void
    {
        $this->company_id = $uuid;
        $this->GetCompanies();

        LivewireAlert::title('Atenção')
            ->text('Deseja realmente, eliminar este registo?')
            ->warning()
            ->withDenyButton()
            ->withConfirmButton()
            ->confirmButtonText('Sim, confirmar')
            ->denyButtonText('Não, cancelar')
            ->withOptions(['allowOutsideClick' => false])
            ->timer(0)
            ->onConfirm('confirmCompanyDeletion')
            ->show();
    }

    public function confirmCompanyDeletion(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->delete("{$this->apiBaseUrl}/empresas/{$this->company_id}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            $data = $response->json();
            if (isset($data['success']) && $data['success'] === false) {
                $this->errorAlert($data['message'] ?? 'Não foi possível deletar a empresa.');
                return;
            }

            $this->GetCompanies();
            $this->successAlert($data['message'] ?? '');
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao deletar a empresa. Contacte o administrador do sistema.');
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

    public function updatedSearch(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCompanies();
    }

    public function updatedSearchUserId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCompanies();
    } 

    public function updatedSearchAddress(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCompanies();
    }

    public function updatedSearchNif(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCompanies();
    }

    public function updatedStartDate(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCompanies();
    }

    public function updatedEndDate(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCompanies();
    }

    public function CancelEdit(): void
    {
        $this->status = false;
        $this->resetValidation();
        $this->reset([
            'name',
            'nif',
            'company_status',
            'email',
            'phone',
            'address',
            'reference',
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
        $this->GetCompanies();
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }
}
