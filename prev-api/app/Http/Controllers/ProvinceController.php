<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Services\ApiQueryService;
use App\Http\Requests\ProvinceFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  
    }


   public function GetProvinces(Request $request) {    
        $this->authorize('viewAny', Province::class);
        $provinces = $this->queryService->GetProvincesFromService($request);                                   

        return response()->json([
            'data' => $provinces->items(),
            'meta' => [
                'current_page' => $provinces->currentPage(),
                'total' => $provinces->total(),
                'per_page' => $provinces->perPage(),
                'last_page' => $provinces->lastPage(),
                'from'  => $provinces->firstItem(),
                'to' => $provinces->lastItem(),
            ],
            'links' => [
                'first' => $provinces->url(1),
                'last'  => $provinces->url($provinces->lastPage()),
                'prev'  => $provinces->previousPageUrl(),
                'next'  => $provinces->nextPageUrl(),
            ]
        ]);    
}

  public function StoreProvince (ProvinceFormRequest $request, Province $rovince) {
        try { 
            $this->authorize('create', Province::class);        
            $data = [
                    'name' => strip_tags($request->name),
                    'country_id'  => strip_tags($request->country_id ),   
                    'iso_code'  => strip_tags($request->iso_code),   
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];               
                        
            $province = $this->queryService->CreateProvince($data); 
            return response()->json([
              'message' => 'Província cadastrada com sucesso!',
               'data' => $province
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => $th->getMessage()]);
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);

        }
    }

      public function EditProvince ($id) {        
            $province = $this->queryService->GetProvinceFromServiceById($id);
            $this->authorize('view', $province);
             return response()->json([
            'data' => $province ?? []
            ], 200);       
    }

    public function DeleteProvince($id)
    {
    try {       
        $province = $this->queryService->GetProvinceFromServiceById($id);      
        $this->authorize('delete', $province);      
        $deleted = $this->queryService->DeleteProvinceFromService($id, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'Província eliminada com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Não foi possível eliminar a província.'
        ], 400);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

public function UpdateProvince(ProvinceFormRequest $request, $id)
{
    try {
        $province = Province::findOrFail($id);
        $this->authorize('update', $province);
        
        if ($request->filled('iso_code') && $request->iso_code != $province->iso_code) { // Verificar se pede alteração de iso_code e se já existe em outra cidade
            $exists = Province::where('iso_code', $request->iso_code)
                ->where('id', '!=', $province->id)
                ->exists();           

            $province->iso_code = strip_tags($request->iso_code) ?? $province->iso_code;
        }

        $province->name = strip_tags($request->name);
        $province->country_id = strip_tags($request->country_id);
        $province->updated_by = auth()->user()->id;
        $province->save();


        return response()->json([
            'message' => 'Província atualizada com sucesso!',
            'data' => $province
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}
}
