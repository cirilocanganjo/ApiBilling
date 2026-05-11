<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryFormRequest;
use App\Models\Subcategory;
use App\Services\ApiQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
      use AuthorizesRequests;
    
    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  // Injeção automática
    }


   public function GetSubCategories(Request $request) {    
        $this->authorize('viewAny', Subcategory::class);
        $subCategories = $this->queryService->GetSubCategoryFromService($request);                                   

        return response()->json([
            'data' => $subCategories->items(),
            'meta' => [
                'current_page' => $subCategories->currentPage(),
                'total' => $subCategories->total(),
                'per_page' => $subCategories->perPage(),
                'last_page' => $subCategories->lastPage(),
                'from'  => $subCategories->firstItem(),
                'to' => $subCategories->lastItem(),
            ],
            'links' => [
                'first' => $subCategories->url(1),
                'last'  => $subCategories->url($subCategories->lastPage()),
                'prev'  => $subCategories->previousPageUrl(),
                'next'  => $subCategories->nextPageUrl(),
            ]
        ]);    
}

  public function StoreSubCategory (SubCategoryFormRequest $request, Subcategory $subCategory) {
        try { 
            $this->authorize('create', Subcategory::class);        
            $data = [
                    'name' => strip_tags($request->name),
                    'category_id' => strip_tags($request->category_id),
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];
               
                        
            $subCategory = $this->queryService->CreateSubCategory($data); 
            return response()->json([
              'message' => 'Subcategoria cadastrada com sucesso!',
               'data' => $subCategory
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

      public function EditSubCategory ($uuid) {        
            $subCategory = $this->queryService->GetSubCategoryFromServiceById($uuid);
            $this->authorize('view', $subCategory);
             return response()->json([
            'data' => $subCategory ?? []
            ], 200);       
    }

    public function DeleteSubCategory($uuid)
    {
    try {       
        $subCategory = $this->queryService->GetSubCategoryFromServiceById($uuid);      
        $this->authorize('delete', $subCategory);      
        $deleted = $this->queryService->DeleteSubCategoryFromService($uuid, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'Subcategoria eliminada com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Não foi possível eliminar a subcategoria.'
        ], 400);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

public function UpdateSubCategory(SubCategoryFormRequest $request, $uuid)
{
    try {        
        $subCategory = Subcategory::where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $subCategory);     
        $userData = [
            'name' => strip_tags($request->name) ?? $subCategory->name, 
            'category_id' => strip_tags($request->category_id) ?? $subCategory->category_id, 
            'updated_by' => auth()->user()->id
        ];

        $subCategory = $this->queryService->UpdateSubCategoryFromService($uuid, $userData);
        return response()->json([
            'message' => 'Subcategoria atualizada com sucesso!',
            'data' => $subCategory
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

}
