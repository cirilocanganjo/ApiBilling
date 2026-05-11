<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class ApiQueries
{
    protected string $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.api.base_url');
    }

    protected function get(string $endpoint): Collection
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('access_token'),
            ])->get("{$this->apiBaseUrl}/{$endpoint}");

            if ($response->failed()) {
                return collect();
            }

            return collect($response->json('data', []));
        } catch (\Throwable $e) {
            report($e);
            return collect();
        }
    }

    public function GetUserFromService(): Collection
    {
        return $this->get('users');
    }

    public function GetCompanyFromService(): Collection
    {
        return $this->get('companies');
    }

    public function GetCountryFromService(): Collection
    {
        return $this->get('countries');
    }

    public function GetProvinceFromService(): Collection
    {
        return $this->get('provinces');
    }

    public function GetCityFromService(): Collection
    {
        return $this->get('cities');
    }
}
