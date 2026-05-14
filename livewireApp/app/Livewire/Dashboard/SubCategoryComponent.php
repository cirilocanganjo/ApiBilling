<?php

namespace App\Livewire\Dashboard;

use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;
use \App\Services\ApiQueries;
use App\Support\Http\HandlesHttpErrors;
use Livewire\WithPagination;

class SubCategoryComponent extends Component
{
    use HandlesHttpErrors, WithPagination;

    public $id;
    public string $uuid;
    public $categories = [];
    public $name;
    public $searcher;
    public $search;
    public $user_id;
    public $search_company_id;
    public $apiBaseUrl;
    public $status;
    public $description;
    public $search_user_id;
    public $category_id;
    public $search_category_id;
    public $start_date;
    public $end_date;
    public $successfulMessage;
    public $perPage = 10;

    protected $subCategoryItems = [];
    protected $paginationMeta = [
        'current_page' => 1,
        'per_page' => 10,
        'total' => 0,
    ];


    protected $listeners = ['confirmSubCategoryDeletion'];

    public function mount(): void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->status = false;
            $this->GetSubCategories();
            $this->GetCategories();
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.dashboard.sub-category-component')->with( [
            'subcategories' => $this->makePaginator(),
            'users' =>$this->GetUsers(),
            'companies' =>$this->GetCompanies()
        ]);
    }

    public function GetSubCategories(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),])->get("{$this->apiBaseUrl}/subcategories", [
                'searcher' => $this->searcher,
                'created_by' =>$this->search_user_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'page' => $this->getPage(),
                'per_page' => $this->perPage,
            ]);

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->subCategoryItems = [];
                $this->paginationMeta = [
                    'current_page' => $this->getPage(),
                    'per_page' => $this->perPage,
                    'total' => 0,
                ];
                return;
            }

            $payload = $response->json() ?? [];
            $this->subCategoryItems = $payload['data'] ?? [];
            $this->paginationMeta = $payload['meta'] ?? [
                'current_page' => $payload['current_page'] ?? $this->getPage(),
                'per_page' => $payload['per_page'] ?? $this->perPage,
                'total' => $payload['total'] ?? count($this->subCategoryItems),
            ];
        } catch (\Throwable $e) {
            report($e);
            $this->subCategoryItems = [];
            $this->paginationMeta = [
                'current_page' => $this->getPage(),
                'per_page' => $this->perPage,
                'total' => 0,
            ];
            $this->errorAlert('Ocorreu um erro ao buscar as subcategorias. Contacte o administrador do sistema.');
        }
    }

    public function GetCategories(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/categories");

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

    public function Edit(string | null $uuid = null)
    {
        try {
            $this->uuid = $uuid ?? '';
            $this->status = true;
            return redirect()->route('app.dashboard.edit.subcategory', [
                'uuid' => $uuid
            ]);
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
            return redirect()->back();
        }
    }

    public function CancelEdit(): void
    {
        $this->status = false;
        $this->GetSubCategories();
        $this->resetValidation();
        $this->reset(['id', 'name', 'description', 'category_id', 'successfulMessage']);
    }


    public function Delete($id): void
    {
        $this->id = $id;
        $this->GetSubCategories();
        LivewireAlert::title('Atenção')
            ->text('Deseja realmente, eliminar este registo?')
            ->warning()
            ->withDenyButton()
            ->withConfirmButton()
            ->confirmButtonText('Sim, confirmar')
            ->denyButtonText('Não, cancelar')
            ->withOptions(['allowOutsideClick' => false])
            ->timer(0)
            ->onConfirm('confirmSubCategoryDeletion')
            ->show();
    }

    public function confirmSubCategoryDeletion(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->delete("{$this->apiBaseUrl}/subcategories/{$this->id}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            $data = $response->json();
            if (isset($data['success']) && $data['success'] === false) {
                $this->errorAlert($data['message'] ?? 'Não foi possível deletar a subcategoria.');
                return;
            }

            if ($response->successful()) {
                $this->GetSubCategories();
                $this->successAlert($data['message'] ?? '');
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao deletar a subcategoria. Contacte o administrador do sistema.');
        }
    }

    protected function makePaginator(): LengthAwarePaginator
    {
        try {
            $currentPage = $this->paginationMeta['current_page'] ?? $this->getPage();
            $perPage = $this->paginationMeta['per_page'] ?? $this->perPage;
            $total = $this->paginationMeta['total'] ?? count($this->subCategoryItems);

            return new LengthAwarePaginator(
                $this->subCategoryItems,
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
        $this->GetSubCategories();
    }

    public function updatedSearcher(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetSubCategories();
    }

    public function updatedSearchUserId(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetSubCategories();
    }

    public function updatedSearchCompanyId()
    {
        if ($this->status) {
            return;
        }
        $this->GetSubCategories();
    }

    public function updatedSearchCategoryId(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetSubCategories();
    }

    public function updatedStartDate(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetSubCategories();
    }

    public function updatedEndDate(): void
    {
        if ($this->status) {
            return;
        }
        $this->GetSubCategories();
    }


}


