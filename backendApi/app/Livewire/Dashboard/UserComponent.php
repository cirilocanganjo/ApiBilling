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

class UserComponent extends Component
{
    use HandlesHttpErrors, WithPagination;
    protected ApiQueries $apiCall;
    public $user_id;
    public string $uuid;
    public $searcher;
    public $search_status;
    public $companies = [];
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $search;
    public $search_company_id;
    public $company_id;
    public $start_date;
    public $end_date;
    public $apiBaseUrl;
    public $status;
    public $type;
    public $successfulMessage;
    public $clone_user_name;
    public $clone_user_email;
    public $clone_user_type;
    public $clone_user_status;
    public bool $isCloning = false;
    public $perPage = 10;

    protected $listeners = ['confirmUserDeletion'];
    protected $userItems = [];
    protected $paginationMeta = [
        'current_page' => 1,
        'per_page' => 10,
        'total' => 0,
    ];


    public function mount(): void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->GetUsers();
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.dashboard.user-component')->with( [
            'users' => $this->makePaginator(),
            'companies' =>$this->GetCompanies()
        ]);
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
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')])->get("{$this->apiBaseUrl}/users", [
                'searcher' => $this->searcher,
                'status' => $this->search_status,
                'start_date' =>$this->start_date,
                'end_date' =>$this->end_date,
                'page' => $this->getPage(),
                'per_page' => $this->perPage,
            ]);

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->userItems = [];
                $this->paginationMeta = [
                    'current_page' => $this->getPage(),
                    'per_page' => $this->perPage,
                    'total' => 0,
                ];
                return;
            }

            $payload = $response->json() ?? [];
            $this->userItems = $payload['data'] ?? [];
            $this->paginationMeta = $payload['meta'] ?? [
                'current_page' => $payload['current_page'] ?? $this->getPage(),
                'per_page' => $payload['per_page'] ?? $this->perPage,
                'total' => $payload['total'] ?? count($this->userItems),
            ];
        } catch (\Throwable $e) {
            report($e);
            $this->userItems = [];
            $this->paginationMeta = [
                'current_page' => $this->getPage(),
                'per_page' => $this->perPage,
                'total' => 0,
            ];
            $this->errorAlert('Ocorreu um erro ao buscar os usuários. Contacte o administrador do sistema.');
        }
    }

    public function Clone ($id) {
        $this->GetUsers();
        return redirect()->route('app.dashboard.clone.user',[
            'id' => $id
        ]);
    }

    public function RenamePassword ($uuid) {
        return redirect()->route('app.dashboard.rename.password',[
            'uuid' => $uuid
        ]);
    }

    public function StoreUserClone () {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token')
            ])->post("{$this->apiBaseUrl}/users/clone", [
                'name' => $this->clone_user_name,
                'email' => $this->clone_user_email,
                'type' => $this->clone_user_type,
                'status' => $this->clone_user_status,
                'password' => $this->password,
            ]);

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            if ($response->successful()) {
                $this->GetUsers();
                LivewireAlert::title('Sucesso')
                    ->text("" )
                    ->success()
                    ->withConfirmButton()
                    ->timer(0)
                    ->confirmButtonText('Fechar')
                    ->show();
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao clonar o usuário. Contacte o administrador do sistema.');
        }
    }

    public function close (){
        $this->GetUsers();
    }

    public function Edit(string | null $uuid = null)
    {
        try {
            $this->uuid = $uuid ?? '';
            return redirect()->route('app.dashboard.form.user', [
                'uuid' => $this->uuid
            ]);
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return redirect()->back();
        }
    }

    public function CancelEdit() : void
    {
        $this->status = false;
        $this->GetUsers();
        $this->resetValidation();
        $this->reset(['user_id', 'name', 'email', 'password','company_id', 'password_confirmation', 'successfulMessage']);
    }


    public function Delete($id): void
    {
        $this->user_id = $id;
        $this->GetUsers();
        LivewireAlert::title('Atenção')
            ->text('Deseja realmente, eliminar este registo?')
            ->warning()
            ->withDenyButton()
            ->withConfirmButton()
            ->confirmButtonText('Sim, confirmar')
            ->denyButtonText('Não, cancelar')
            ->withOptions(['allowOutsideClick' => false])
            ->timer(0)
            ->onConfirm('confirmUserDeletion')
            ->show();
    }

    public function confirmUserDeletion(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->delete("{$this->apiBaseUrl}/users/{$this->user_id}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            $data = $response->json();
            if (isset($data['success']) && $data['success'] === false) {
                $this->errorAlert($data['message'] ?? 'Não foi possível deletar o usuário.');
                return;
            }

            if ($response->successful()) {
                $this->GetUsers();
                $this->successAlert($data['message'] ?? '');
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao deletar o usuário. Contacte o administrador do sistema.');
        }
    }

    protected function makePaginator(): LengthAwarePaginator
    {
        try {
            $currentPage = $this->paginationMeta['current_page'] ?? $this->getPage();
            $perPage = $this->paginationMeta['per_page'] ?? $this->perPage;
            $total = $this->paginationMeta['total'] ?? count($this->userItems);

            return new LengthAwarePaginator(
                $this->userItems,
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



    public function updatedPerPage(): void
    {
        if ($this->status) {
            return;
        }
        $this->resetPage();
    }

    public function updatedPage(): void
    {
        $this->GetUsers();
    }

    public function updatedSearcher(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetUsers();
    }

    public function updatedSearchCompanyId(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetUsers();
    }

    public function updatedStartDate(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetUsers();
    }

    public function updatedSearchStatus(): void
    {
        $this->GetUsers();
    }

    public function updatedEndDate(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetUsers();
    }


}
