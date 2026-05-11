<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitFormRequest;
use \App\Models\Unit;
use App\Services\ApiQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  // Injeção automática
    }


   public function GetUnits(Request $request) {    
        $this->authorize('viewAny', Unit::class);
        $units = $this->queryService->GetUnitFromService($request);                                   

        return response()->json([
            'data' => $units->items(),
            'meta' => [
                'current_page' => $units->currentPage(),
                'total' => $units->total(),
                'per_page' => $units->perPage(),
                'last_page' => $units->lastPage(),
                'from'  => $units->firstItem(),
                'to' => $units->lastItem(),
            ],
            'links' => [
                'first' => $units->url(1),
                'last'  => $units->url($units->lastPage()),
                'prev'  => $units->previousPageUrl(),
                'next'  => $units->nextPageUrl(),
            ]
        ]);    
}

  public function StoreUnit (UnitFormRequest $request, Unit $unit) {
        try { 
            $this->authorize('create', Unit::class);        
            $data = [
                    'name' => strip_tags($request->name),
                    'acronym' => strip_tags($request->acronym),
                    'company_id'  => auth()->user()->company_id,   
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];
               
                        
            $unit = $this->queryService->CreateUnit($data); 
            return response()->json([
              'message' => 'Unidade cadastrada com sucesso!',
               'data' => $unit
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

      public function EditUnit ($uuid) {        
            $unit = $this->queryService->GetUnitFromServiceById($uuid);
            $this->authorize('view', $unit);
             return response()->json([
            'data' => $unit ?? []
            ], 200);       
    }

    public function DeleteUnit($uuid)
    {
    try {       
        $unit = $this->queryService->GetUnitFromServiceById($uuid);      
        $this->authorize('delete', $unit);      
        $deleted = $this->queryService->DeleteUnitFromService($uuid, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'Unidade eliminada com sucesso!'
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

public function UpdateUnit(UnitFormRequest $request, $uuid)
{
    try {        
        $unit = Unit::where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $unit);     
        $userData = [
            'name' => strip_tags($request->name) ?? $unit->name, 
            'acronym' => strip_tags($request->acronym) ?? $unit->acronym, 
            'company_id' => auth()->user()->company_id, 
            'updated_by' => auth()->user()->id
        ];

        $unit = $this->queryService->UpdateUnitFromService($uuid, $userData);
        return response()->json([
            'message' => 'Unidade atualizada com sucesso!',
            'data' => $unit
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()]);
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}
}
