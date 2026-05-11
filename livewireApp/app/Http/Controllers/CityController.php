<?php

namespace App\Http\Controllers;

use \App\Models\City;
use \App\Http\Requests\CityFormRequest;
use App\Services\ApiQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CityController extends Controller
{
     use AuthorizesRequests;
    
    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  
    }


   public function GetCities(Request $request) {    
        $this->authorize('viewAny', City::class);
        $cities = $this->queryService->GetCitiesFromService($request);                                   

        return response()->json([
            'data' => $cities->items(),
            'meta' => [
                'current_page' => $cities->currentPage(),
                'total' => $cities->total(),
                'per_page' => $cities->perPage(),
                'last_page' => $cities->lastPage(),
                'from'  => $cities->firstItem(),
                'to' => $cities->lastItem(),
            ],
            'links' => [
                'first' => $cities->url(1),
                'last'  => $cities->url($cities->lastPage()),
                'prev'  => $cities->previousPageUrl(),
                'next'  => $cities->nextPageUrl(),
            ]
        ]);    
}

  public function StoreCity (CityFormRequest $request, City $city) {
        try { 
            $this->authorize('create', City::class);        
            $data = [
                    'province_id' => intval($request->province_id),
                    'name' => strip_tags($request->name),
                    'iso_code'  => strip_tags($request->iso_code),   
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];               

                        
            $city = $this->queryService->CreateCity($data); 
            return response()->json([
              'message' => 'Cidade cadastrada com sucesso!',
               'data' => $city
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

      public function EditCity ($uuid) {        
            $city = $this->queryService->GetCityFromServiceById($uuid);
            $this->authorize('view', $city);
             return response()->json([
            'data' => $city ?? []
            ], 200);       
    }

    public function DeleteCity($uuid)
    {
    try {       
        $city = $this->queryService->GetCityFromServiceById($uuid);      
        $this->authorize('delete', $city);      
        $deleted = $this->queryService->DeleteCityFromService($uuid, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'Cidade eliminada com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Não foi possível eliminar a cidade.'
        ], 400);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

public function UpdateCity(CityFormRequest $request, $uuid)
{
    try {
        $city = City::where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $city);
        
        if ($request->filled('iso_code') && $request->iso_code != $city->iso_code) { // Verificar se pede alteração de iso_code e se já existe em outra cidade
            $exists = City::where('iso_code', $request->iso_code)
                ->where('uuid', '!=', $city->uuid)
                ->exists();           

            $city->iso_code = strip_tags($request->iso_code) ?? $city->iso_code;
        }

        $city->province_id = intval($request->province_id);
        $city->name = strip_tags($request->name);
        $city->updated_by = auth()->user()->id;
        $city->save();


        return response()->json([
            'message' => 'Cidade atualizada com sucesso!',
            'data' => $city
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}



}
