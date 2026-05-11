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

class UnitComponent extends Component
{
    use HandlesHttpErrors, WithPagination;

    protected $listeners = ['confirmUnitDeletion'];

    public string $uuid;
    public $unit_id;
    public $name;
    public $searcher;
    public $acronym;
    public $company_id;
    public $search;
    public $search_company_id;
    public $search_user_id;
    public $perPage = 10;
    public $start_date;
    public $end_date;
    public $apiBaseUrl;
    public $status;
    public $successfulMessage;
    public $unitItems = [];
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
            $this->GetUnits();
        } catch (\Throwable $th) {
            report($th);
            $this->errorAlert('Ocorreu um erro ao realizar a operação. Contacte o administrador do sistema.');
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.dashboard.unit-component')->with([
            'units' => $this->makePaginator(),
            'companies' => $this->GetCompanies(),
            'users' => $this->GetUsers(),
        ]);
    }

    protected function makePaginator(): LengthAwarePaginator
    {
        try {
            $currentPage = $this->paginationMeta['current_page'] ?? $this->getPage();
            $perPage = $this->paginationMeta['per_page'] ?? $this->perPage;
            $total = $this->paginationMeta['total'] ?? count($this->unitItems);

            return new LengthAwarePaginator(
                $this->unitItems,
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

    public function GetUnits(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/units", [
                'searcher' => $this->searcher,
                'created_by' => $this->search_user_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'page' => $this->getPage(),
                'per_page' => $this->perPage,
            ]);

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->unitItems = [];
                $this->paginationMeta = [
                    'current_page' => $this->getPage(),
                    'per_page' => $this->perPage,
                    'total' => 0,
                ];
                return;
            }

            $payload = $response->json() ?? [];
            $this->unitItems = $payload['data'] ?? [];
            $this->paginationMeta = $payload['meta'] ?? [
                'current_page' => $payload['current_page'] ?? $this->getPage(),
                'per_page' => $payload['per_page'] ?? $this->perPage,
                'total' => $payload['total'] ?? count($this->unitItems),
            ];
        } catch (\Throwable $e) {
            report($e);
            $this->unitItems = [];
            $this->paginationMeta = [
                'current_page' => $this->getPage(),
                'per_page' => $this->perPage,
                'total' => 0,
            ];
            $this->errorAlert('Ocorreu um erro ao buscar as unidades. Contacte o administrador do sistema.');
        }
    }

    public function Store(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->post("{$this->apiBaseUrl}/units", [
                'name' => $this->pull('name'),
                'acronym' => $this->pull('acronym'),
                'company_id' => $this->pull('company_id'),
            ]);

            if ($response->status() === 422) {
                $this->GetUnits();
                $errors = $response->json('errors');
                foreach ($errors as $field => $messages) {
                    $this->addError($field, $messages[0]);
                }
                return;
            }

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->GetUnits();
                return;
            }

            if ($response->successful()) {
                $this->successfulMessage = $response->json('message');
                $this->resetValidation();
                LivewireAlert::title('Sucesso')
                    ->text($this->successfulMessage ?? '')
                    ->success()
                    ->withConfirmButton()
                    ->timer(0)
                    ->confirmButtonText('Fechar')
                    ->show();
            }
            $this->GetUnits();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar a unidade. Contacte o administrador do sistema.');
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

    public function Edit(string | null $uuid = null)
    {
        try {
            $this->uuid = $uuid ?? '';
            $this->status = true;
            return redirect()->route('app.dashboard.edit.unit', [
                'uuid' => $uuid
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
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->put("{$this->apiBaseUrl}/units/{$this->unit_id}", [
                'name' => $this->name,
                'acronym' => $this->acronym,
                'company_id' => $this->company_id,
            ]);

            if ($response->status() === 422) {
                $this->GetUnits();
                $errors = $response->json('errors');
                foreach ($errors as $field => $messages) {
                    $this->addError($field, $messages[0]);
                }
                return;
            }

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->GetUnits();
                return;
            }

            if ($response->successful()) {
                $this->GetUnits();
                $this->successfulMessage = $response->json('message');
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
            $this->errorAlert('Ocorreu um erro ao atualizar a unidade. Contacte o administrador do sistema.');
        }
    }

    public function Delete(string $uuid): void
    {
        $this->unit_id = $uuid;
        $this->GetUnits();

        LivewireAlert::title('Atenção')
            ->text('Deseja realmente, eliminar este registo?')
            ->warning()
            ->withDenyButton()
            ->withConfirmButton()
            ->confirmButtonText('Sim, confirmar')
            ->denyButtonText('Não, cancelar')
            ->withOptions(['allowOutsideClick' => false])
            ->timer(0)
            ->onConfirm('confirmUnitDeletion')
            ->show();
    }

    public function confirmUnitDeletion(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->delete("{$this->apiBaseUrl}/units/{$this->unit_id}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            $data = $response->json();
            if (isset($data['success']) && $data['success'] === false) {
                $this->errorAlert($data['message'] ?? 'Não foi possível deletar a unidade.');
                return;
            }

            if ($response->successful()) {
                $this->GetUnits();
                $this->successAlert($data['message'] ?? '');
            }
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao deletar a unidade. Contacte o administrador do sistema.');
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

    public function updatedSearcher(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetUnits();
    }


    public function updatedSearchCompanyId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetUnits();
    }

    public function updatedSearchUserId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetUnits();
    }

    public function updatedStartDate(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetUnits();
    }

    public function updatedEndDate(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetUnits();
    }

    public function CancelEdit(): void
    {
        $this->status = false;
        $this->resetValidation();
        $this->reset([
            'unit_id',
            'name',
            'acronym',
            'company_id'
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
        $this->GetUnits();
    }
}

