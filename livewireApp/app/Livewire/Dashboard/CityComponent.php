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

class CityComponent extends Component
{
    use HandlesHttpErrors, WithPagination;

    protected $listeners = ['confirmCityDeletion'];

    public string $uuid;
    public $city_id;
    public $name;
    public $province_id;
    public $search;
    public $iso_code;
    public $search_province_id;
    public $search_user_id;
    public $perPage = 10;
    public $start_date;
    public $end_date;
    public $apiBaseUrl;
    public $status;
    public $successfulMessage;
    public $cityItems = [];
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
            $this->GetCities();
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
        return view('livewire.dashboard.city-component')->with([
            'cities' => $this->makePaginator(),
            'provinces' => $this->GetProvinces(),
            'users' => $this->GetUsers(),
        ]);
    }

    protected function makePaginator(): LengthAwarePaginator
    {
        try {
            $currentPage = $this->paginationMeta['current_page'] ?? $this->getPage();
            $perPage = $this->paginationMeta['per_page'] ?? $this->perPage;
            $total = $this->paginationMeta['total'] ?? count($this->cityItems);

            return new LengthAwarePaginator(
                $this->cityItems,
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

    public function GetCities(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/cities", [
                'name' => $this->search,
                'province_id' => $this->search_province_id,
                'created_by' => $this->search_user_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'page' => $this->getPage(),
                'per_page' => $this->perPage,
            ]);

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->cityItems = [];
                $this->paginationMeta = [
                    'current_page' => $this->getPage(),
                    'per_page' => $this->perPage,
                    'total' => 0,
                ];
                return;
            }

            $payload = $response->json() ?? [];
            $this->cityItems = $payload['data'] ?? [];
            $this->paginationMeta = $payload['meta'] ?? [
                'current_page' => $payload['current_page'] ?? $this->getPage(),
                'per_page' => $payload['per_page'] ?? $this->perPage,
                'total' => $payload['total'] ?? count($this->cityItems),
            ];
        } catch (\Throwable $e) {
            report($e);
            $this->cityItems = [];
            $this->paginationMeta = [
                'current_page' => $this->getPage(),
                'per_page' => $this->perPage,
                'total' => 0,
            ];
            $this->errorAlert('Ocorreu um erro ao buscar as cidades. Contacte o administrador do sistema.');
        }
    }

    public function Store(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->post("{$this->apiBaseUrl}/cities", [
                'name' => $this->pull('name'),
                'iso_code' => $this->pull('iso_code'),
                'province_id' => $this->pull('province_id'),
            ]);

            if ($response->status() === 422) {
                $this->GetCities();
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

            $this->GetCities();
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao criar a cidade. Contacte o administrador do sistema.');
        }
    }

    public function Edit(string | null $uuid = null)
    {
        try {
            $this->uuid = $uuid ?? '';
            $this->status = true;
            return redirect()->route('app.dashboard.edit.city', [
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
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->put("{$this->apiBaseUrl}/cities/{$this->city_id}", [
                'name' => $this->name,
                'iso_code' => $this->iso_code,
                'province_id' => $this->province_id,
            ]);

            if ($response->status() === 422) {
                $this->GetCities();
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

            $this->GetCities();
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
            $this->errorAlert('Ocorreu um erro ao atualizar a cidade. Contacte o administrador do sistema.');
        }
    }

    public function Delete(string $uuid): void
    {
        $this->city_id = $uuid;
        $this->GetCities();

        LivewireAlert::title('Atenção')
            ->text('Deseja realmente, eliminar este registo?')
            ->warning()
            ->withDenyButton()
            ->withConfirmButton()
            ->confirmButtonText('Sim, confirmar')
            ->denyButtonText('Não, cancelar')
            ->withOptions(['allowOutsideClick' => false])
            ->timer(0)
            ->onConfirm('confirmCityDeletion')
            ->show();
    }

    public function confirmCityDeletion(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->delete("{$this->apiBaseUrl}/cities/{$this->city_id}");

            if ($response->failed()) {
                $this->handleHttpError($response);
                return;
            }

            $data = $response->json();
            if (isset($data['success']) && $data['success'] === false) {
                $this->errorAlert($data['message'] ?? 'Não foi possível deletar a cidade.');
                return;
            }

            $this->GetCities();
            $this->successAlert($data['message'] ?? '');
        } catch (\Throwable $e) {
            report($e);
            $this->errorAlert('Ocorreu um erro ao deletar a cidade. Contacte o administrador do sistema.');
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

        $this->GetCities();
    }

    public function updatedSearchProvinceId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCities();
    }

    public function updatedSearchUserId(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCities();
    }

    public function updatedStartDate(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCities();
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
        $this->GetCities();
    }

    public function updatedEndDate(): void
    {
        if ($this->status) {
            return;
        }

        $this->GetCities();
    }

    public function CancelEdit(): void
    {
        $this->status = false;
        $this->resetValidation();
        $this->reset([
            'city_id',
            'name',
            'iso_code',
            'province_id',
        ]);
    }


}
