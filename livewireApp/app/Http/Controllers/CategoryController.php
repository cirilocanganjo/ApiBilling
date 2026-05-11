<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryFormRequest;
use App\Models\Category;
use App\Services\ApiQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
     use AuthorizesRequests;
    
    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  // Injeção automática
    }


   public function GetCategories(Request $request) {    
        $this->authorize('viewAny', Category::class);
        $categories = $this->queryService->GetCategoryFromService($request);                                   

        return response()->json([
            'data' => $categories->items(),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'last_page' => $categories->lastPage(),
                'from'  => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ],
            'links' => [
                'first' => $categories->url(1),
                'last'  => $categories->url($categories->lastPage()),
                'prev'  => $categories->previousPageUrl(),
                'next'  => $categories->nextPageUrl(),
            ]
        ]);    
}

  public function StoreCategory (CategoryFormRequest $request, Category $category) {
        try { 
            $this->authorize('create', Category::class);        
            $data = [
                    'name' => strip_tags($request->name),
                    'description' => strip_tags($request->description),
                    'company_id'  => auth()->user()->company_id,   
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];
               
                        
            $category = $this->queryService->CreateCategory($data); 
            return response()->json([
              'message' => 'Categoria cadastrada com sucesso!',
               'data' => $category
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

      public function EditCategory ($uuid) {        
            $category = $this->queryService->GetCategoryFromServiceById($uuid);
            $this->authorize('view', $category);
             return response()->json([
            'data' => $category ?? []
            ], 200);       
    }

    public function DeleteCategory($uuid)
    {
    try {       
        $category = $this->queryService->GetCategoryFromServiceById($uuid);      
        $this->authorize('delete', $category);      
        $deleted = $this->queryService->DeleteCategoryFromService($uuid, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'Categoria eliminada com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Não foi possível eliminar a categoria.'
        ], 400);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

public function UpdateCategory(CategoryFormRequest $request, $uuid)
{
    try {        
        $category = Category::where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $category);     
        $userData = [
            'name' => strip_tags($request->name) ?? $category->name, 
            'description' => strip_tags($request->description) ?? $category->description, 
            'company_id'  => auth()->user()->company_id,
            'updated_by' => auth()->user()->id
        ];

        $category = $this->queryService->UpdateCategoryFromService($uuid, $userData);
        return response()->json([
            'message' => 'Categoria atualizada com sucesso!',
            'data' => $category
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()]);
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

}
