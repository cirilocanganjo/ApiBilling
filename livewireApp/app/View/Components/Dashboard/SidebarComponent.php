<?php

namespace App\View\Components\Dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\Component;

class SidebarComponent extends Component
{
    public string $apiBaseUrl;
    public int $userId;
    public int $clientCounter;
    public int $userCounter;
    public int $unitCounter;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.api.base_url');
        $this->userId = session('user.id');
        $this->clientCounter = $this->GetClientCounter();
        $this->unitCounter = $this->GetUnitCounter();
        $this->userCounter = $this->GetUserCounter();
    }

    public function GetClientCounter(): int
    {
        try {
            return Cache::remember(
                "client_counter_{$this->userId}",
                60,
                function (): int {
                    $response = Http::timeout(10)
                        ->withToken(session('access_token'))
                        ->acceptJson()
                        ->get("{$this->apiBaseUrl}/clients");

                    if ($response->failed()) {
                        return 0;
                    }

                    $payload = $response->json() ?? [];

                    if (isset($payload['success']) && $payload['success'] === false) {
                        return 0;
                    }

                    return isset($payload['data']) && is_array($payload['data'])
                        ? count($payload['data'])
                        : 0;
                }
            );
        } catch (\Throwable $e) {
            report($e);
            return 0;
        }
    }

     public function GetUnitCounter(): int
    {
        try {
            return Cache::remember(
                "unit_counter_{$this->userId}",
                60,
                function (): int {
                    $response = Http::timeout(10)
                        ->withToken(session('access_token'))
                        ->acceptJson()
                        ->get("{$this->apiBaseUrl}/units");

                    if ($response->failed()) {
                        return 0;
                    }

                    $payload = $response->json() ?? [];

                    if (isset($payload['success']) && $payload['success'] === false) {
                        return 0;
                    }

                    return isset($payload['data']) && is_array($payload['data'])
                        ? count($payload['data'])
                        : 0;
                }
            );
        } catch (\Throwable $e) {
            report($e);
            return 0;
        }
    }

     public function GetUserCounter(): int
    {
        try {
            return Cache::remember(
                "user_counter_{$this->userId}",
                60,
                function (): int {
                    $response = Http::timeout(10)
                        ->acceptJson()
                        ->withToken(session('access_token'))
                        ->get("{$this->apiBaseUrl}/users");

                    if ($response->failed()) {
                        return 0;
                    }

                    $payload = $response->json() ?? [];

                    if (isset($payload['success']) && $payload['success'] === false) {
                        return 0;
                    }

                    $data = $payload['data'] ?? [];

                    return collect($data)
                        ->where('status', 'active')
                        ->count();
                }
            );
        } catch (\Throwable $e) {
            report($e);
            return 0;
        }
    }





    public function render(): View|Closure|string
    {
        return view('components.dashboard.sidebar-component');
    }
}
