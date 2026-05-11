<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use \App\Services\ApiQueryService;
use Illuminate\Http\Request;
use \App\Http\Requests\SupplyFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SupplyController extends Controller
{

    use AuthorizesRequests;
    
    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  // Injeção automática
    }


   public function GetSuppliers(Request $request) 
   {    
        $this->authorize('viewAny', Supply::class);
        $suppliers = $this->queryService->GetSupplyFromService($request);                                   

        return response()->json([
            'data' => $suppliers->items(),
            'meta' => [
                'current_page' => $suppliers->currentPage(),
                'total' => $suppliers->total(),
                'per_page' => $suppliers->perPage(),
                'last_page' => $suppliers->lastPage(),
                'from'  => $suppliers->firstItem(),
                'to' => $suppliers->lastItem(),
            ],
            'links' => [
                'first' => $suppliers->url(1),
                'last'  => $suppliers->url($suppliers->lastPage()),
                'prev'  => $suppliers->previousPageUrl(),
                'next'  => $suppliers->nextPageUrl(),
            ]
        ]);    
}

  public function StoreSupplier (SupplyFormRequest $request, Supply $supply) {
        try { 
            $this->authorize('create', Supply::class);  

            $data = [
                    'name' => strip_tags($request->name),
                    'natural_person' => strip_tags($request->natural_person),
                    'tax_id' => strip_tags($request->tax_id),
                    'country_id' => strip_tags($request->country_id),
                    'province_id' => strip_tags($request->province_id),
                    'city_id' => strip_tags($request->city_id),
                    'address' => strip_tags($request->address),
                    'complement' => strip_tags($request->complement),
                    'neighborhood' => strip_tags($request->neighborhood),
                    'postal_code' => strip_tags($request->postal_code),
                    'contact_person' => strip_tags($request->contact_person),
                    'notes' => strip_tags($request->notes),
                    'phone' => strip_tags($request->phone),
                    'email' => strip_tags($request->email),
                    'company_id'  => auth()->user()->company_id,   
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];
               
                        
            $supply = $this->queryService->CreateSupply($data); 
            return response()->json([
              'message' => 'Fornecedor cadastrado com sucesso!',
               'data' => $supply
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

    public function EditSupplier ($uuid) {        
         $supply = $this->queryService->GetSupplyFromServiceById($uuid);
         $this->authorize('view', $supply);
             return response()->json([
            'data' => $supply ?? []
            ], 200);       
    }

    public function DeleteSupplier($uuid)
    {
    try {       
        $supply = $this->queryService->GetSupplyFromServiceById($uuid);      
        $this->authorize('delete', $supply);      
        $deleted = $this->queryService->DeleteSupplyFromService($uuid, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'Fornecedor eliminado com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Não foi possível eliminar o fornecedor.'
        ], 400);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

public function UpdateSupplier(SupplyFormRequest $request, $uuid)
{
    try {        
        $supply = Supply::query()->where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $supply);  

        $userData = [
            'name' => strip_tags($request->name) ?? $supply->name, 
            'natural_person' => strip_tags($request->natural_person) ?? $supply->natural_person, 
            'tax_id' => strip_tags($request->tax_id) ?? $supply->tax_id, 
            'country_id' => strip_tags($request->country_id) ?? $supply->country_id, 
            'province_id' => strip_tags($request->province_id) ?? $supply->province_id, 
            'city_id' => strip_tags($request->city_id) ?? $supply->city_id, 
            'address' => strip_tags($request->address) ?? $supply->address, 
            'complement' => strip_tags($request->complement) ?? $supply->complement, 
            'neighborhood' => strip_tags($request->neighborhood) ?? $supply->neighborhood, 
            'postal_code' => strip_tags($request->postal_code) ?? $supply->postal_code, 
            'contact_person' => strip_tags($request->contact_person) ?? $supply->contact_person, 
            'notes' => strip_tags($request->notes) ?? $supply->notes, 
            'phone' => strip_tags($request->phone) ?? $supply->phone, 
            'email' => strip_tags($request->email) ?? $supply->email, 
            'company_id' => auth()->user()->company_id, 
            'updated_by' => auth()->user()->id
        ];

        $supply = $this->queryService->UpdateSupplyFromService($uuid, $userData);
        return response()->json([
            'message' => 'Fornecedor atualizado com sucesso!',
            'data' => $supply
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()]);
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

public function CloneSupplier($id) {
        $supply = $this->queryService->GetSupplierFromServiceByPrimaryKey($id);
        $this->authorize('view', $supply);
        return response()->json([
            'data' => $supply ?? []
        ], 200);
    }

   
}
