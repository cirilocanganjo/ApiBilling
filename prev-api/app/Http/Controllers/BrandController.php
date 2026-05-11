<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\ApiQueryService;
use Illuminate\Http\Request;
use \App\Models\{Brand};

class BrandController extends Controller
{
    use  AuthorizesRequests;


    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  // Injeção automática
    }


   public function GetBrands(Request $request) {    
        $this->authorize('viewAny', Brand::class);
        $brands = $this->queryService->GetBrandFromService($request);                                   

        return response()->json([
            'data' => $brands->items(),
            'meta' => [
                'current_page' => $brands->currentPage(),
                'total' => $brands->total(),
                'per_page' => $brands->perPage(),
                'last_page' => $brands->lastPage(),
                'from'  => $brands->firstItem(),
                'to' => $brands->lastItem(),
            ],
            'links' => [
                'first' => $brands->url(1),
                'last'  => $brands->url($brands->lastPage()),
                'prev'  => $brands->previousPageUrl(),
                'next'  => $brands->nextPageUrl(),
            ]
        ]);    
}

  public function StoreBrand (BrandFormRequest $request, Brand $brand) {
        try { 
            $this->authorize('create', Brand::class);        
            $data = [
                    'name' => strip_tags($request->name),
                    'company_id'  => auth()->user()->company_id,   
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];
               
                        
            $brand = $this->queryService->CreateBrand($data); 
            return response()->json([
              'message' => 'Marca cadastrada com sucesso!',
               'data' => $brand
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

      public function EditBrand ($uuid) {        
            $brand = $this->queryService->GetBrandFromServiceById($uuid);
            $this->authorize('view', $brand);
             return response()->json([
            'data' => $brand ?? []
            ], 200);       
    }

    public function DeleteBrand($uuid)
    {
    try {       
        $brand = $this->queryService->GetBrandFromServiceById($uuid);      
        $this->authorize('delete', $brand);      
        $deleted = $this->queryService->DeleteBrandFromService($uuid, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'Marca eliminada com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Não foi possível eliminar a marca.'
        ], 400);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

public function UpdateBrand(BrandFormRequest $request, $uuid)
{
    try {        
        $brand = Brand::where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $brand);     
        $userData = [
            'name' => strip_tags($request->name) ?? $brand->name, 
            'company_id' => auth()->user()->company_id, 
            'updated_by' => auth()->user()->id
        ];

        $brand = $this->queryService->UpdateBrandFromService($uuid, $userData);
        return response()->json([
            'message' => 'Marca atualizada com sucesso!',
            'data' => $brand
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}   

}
