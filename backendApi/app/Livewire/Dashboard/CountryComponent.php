<?php

namespace App\Livewire\Dashboard;

use App\Support\Http\HandlesHttpErrors;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CountryComponent extends Component
{
    use HandlesHttpErrors;

    public $countries = [];
    public $paginationMeta = [
        'current_page' => 1,
        'per_page' => 10,
        'total' => 0,
    ];
    public $perPage = 10;
    public $apiBaseUrl;

    public function mount(): void
    {
        try {
            $this->apiBaseUrl = config('services.api.base_url');
            $this->loadCountries();
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
        return view('livewire.dashboard.country-component')->with([
            'countries' => $this->countries,
        ]);
    }

    public function loadCountries(): void
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/countries", [
                'page' => $this->paginationMeta['current_page'] ?? 1,
                'per_page' => $this->perPage,
            ]);

            if ($response->failed()) {
                $this->handleHttpError($response);
                $this->countries = [];
                $this->paginationMeta = [
                    'current_page' => 1,
                    'per_page' => $this->perPage,
                    'total' => 0,
                ];
                return;
            }

            $payload = $response->json() ?? [];
            $this->countries = $payload['data'] ?? [];
            $this->paginationMeta = $payload['meta'] ?? [
                'current_page' => $payload['current_page'] ?? 1,
                'per_page' => $payload['per_page'] ?? $this->perPage,
                'total' => $payload['total'] ?? count($this->countries),
            ];
        } catch (\Throwable $e) {
            report($e);
            $this->countries = [];
            $this->paginationMeta = [
                'current_page' => 1,
                'per_page' => $this->perPage,
                'total' => 0,
            ];
            $this->errorAlert('Ocorreu um erro ao buscar os países. Contacte o administrador do sistema.');
        }
    }
}
