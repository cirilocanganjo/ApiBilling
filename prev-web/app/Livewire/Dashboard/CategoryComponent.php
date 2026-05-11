<?php

namespace App\Livewire\Dashboard;

use \App\Services\ApiQueries;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Support\Http\HandlesHttpErrors;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryComponent extends Component
{
    use HandlesHttpErrors,WithPagination;
    public string $uuid;

    public $searcher;
    public $category_id;
    public $name;
    public $search;
    public $search_company_id;
    public $user_id;
    public $search_user_id;
    public $apiBaseUrl;
    public $status;
    public $description;
    public $company_id;
    public $start_date;
    public $end_date;
    public $successfulMessage;
    public $perPage = 10;
    protected $listeners = ['confirmCategoryDeletion'];
    protected $categoryItems = [];
    protected $paginationMeta = [
        'current_page' => 1,
        'per_page' => 10,
        'total' => 0,
    ];

    public function mount() : void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->status = false;
            $this->GetCategories();
        } catch (\Throwable $th) {
           report($th);
           $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

    #[Layout('layouts.app')]
    public function render() : View
    {
            return view('livewire.dashboard.category-component')->with([
                'categories' => $this->makePaginator(),
                'users' =>$this->GetUsers(),
                'companies' =>$this->GetCompanies(),
            ]);
    }

    protected function makePaginator(): LengthAwarePaginator
    {
        try {
            $currentPage = $this->paginationMeta['current_page'] ?? $this->getPage();
            $perPage = $this->paginationMeta['per_page'] ?? $this->perPage;
            $total = $this->paginationMeta['total'] ?? count($this->categoryItems);

            return new LengthAwarePaginator(
                $this->categoryItems,
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

    public function GetCategories(): void
{
    try {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('access_token')
        ])->get("{$this->apiBaseUrl}/categories", [
            'searcher' => $this->searcher,
            'created_by' => $this->search_user_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'page' => $this->getPage(),
            'per_page' => $this->perPage,
        ]);

        if ($response->failed()) {
            $this->handleHttpError($response);
            $this->categoryItems = [];
            $this->paginationMeta = [
                'current_page' => $this->getPage(),
                'per_page' => $this->perPage,
                'total' => 0,
            ];
            return;
        }

        $payload = $response->json() ?? [];
        $this->categoryItems = $payload['data'] ?? [];
        $this->paginationMeta = $payload['meta'] ?? [
            'current_page' => $payload['current_page'] ?? $this->getPage(),
            'per_page' => $payload['per_page'] ?? $this->perPage,
            'total' => $payload['total'] ?? count($this->categoryItems),
        ];
    } catch (\Throwable $e) {
        report($e);
        $this->categoryItems = [];
        $this->paginationMeta = [
            'current_page' => $this->getPage(),
            'per_page' => $this->perPage,
            'total' => 0,
        ];
        $this->errorAlert('Ocorreu um erro ao buscar as categories. Contacte o administrador do sistema.');
    }
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

    public function Edit (string | null $uuid = null)
    {
        try {
            $this->uuid = $uuid ?? '';
            $this->status = true;
            return redirect()->route('app.dashboard.edit.category', [
                'uuid' => $this->uuid
            ]);
        } catch (\Throwable $th) {
        report($th);
        $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        return redirect()->back();
        }
    }


    public function Delete ($uuid)
    {
        try {
            $this->category_id = $uuid;
            $this->GetCategories();
            LivewireAlert::title('Atenção')
                ->text('Deseja realmente, eliminar este registo?')
                ->warning()
                ->withDenyButton()
                ->withConfirmButton()
                ->confirmButtonText('Sim, confirmar')
                ->denyButtonText('Não, cancelar')
                ->withOptions(['allowOutsideClick' => false])
                ->timer(0)
                ->onConfirm('confirmCategoryDeletion')
                ->show();
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');

        }
    }

    public function confirmCategoryDeletion(): void
{
    try {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('access_token'),
        ])->delete("{$this->apiBaseUrl}/categories/{$this->category_id}");

        if ($response->failed()) {
            $this->handleHttpError($response);
            return;
        }

        $data = $response->json();
        if (isset($data['success']) && $data['success'] === false) {
            $this->errorAlert($data['message'] ?? 'Não foi possível deletar a categoria.');
            return;
        }

        $this->GetCategories();
        $this->successAlert($data['message'] ?? '');

    } catch (\Throwable $e) {
        report($e);
        $this->errorAlert('Ocorreu um erro ao deletar a categoria. Contacte o administrador do sistema.');
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
        $this->GetCategories();
    }

    public function updatedSearcher()
    {
        if ($this->status) {
            return;
        }
        $this->GetCategories();
    }

    public function updatedSearchUserId()
    {
        if ($this->status) {
            return;
        }
        $this->GetCategories();
    }

    public function updatedSearchCompanyId()
    {
        if ($this->status) {
            return;
        }
        $this->GetCategories();
    }


    public function updatedStartDate()
    {
        if ($this->status) {
            return;
        }
        $this->GetCategories();
    }

    public function updatedEndDate()
    {
        if ($this->status) {
            return;
        }
        $this->GetCategories();
    }
}
