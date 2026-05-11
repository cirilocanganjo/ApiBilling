<?php

namespace App\Http\Controllers;
use App\Http\Requests\ClientFormRequest;
use App\Models\{Client};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\ApiQueryService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
   use AuthorizesRequests;   
    

  public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  
    }


   public function GetClients(Request $request) {    
        $this->authorize('viewAny', Client::class);
        $clients = $this->queryService->GetClientFromService($request);                                   

        return response()->json([
            'data' => $clients->items(),
            'meta' => [
                'current_page' => $clients->currentPage(),
                'total' => $clients->total(),
                'per_page' => $clients->perPage(),
                'last_page' => $clients->lastPage(),
                'from'  => $clients->firstItem(),
                'to' => $clients->lastItem(),
            ],
            'links' => [
                'first' => $clients->url(1),
                'last'  => $clients->url($clients->lastPage()),
                'prev'  => $clients->previousPageUrl(),
                'next'  => $clients->nextPageUrl(),
            ]
        ]);    
}

  public function StoreClient (ClientFormRequest $request, Client $client) {
        try { 

            $this->authorize('create', Client::class);        
            $data = [
                    'name' => strip_tags($request->name),
                    'country_id' => strip_tags($request->country_id),
                    'province_id' => strip_tags($request->province_id),
                    'address' => strip_tags($request->address),
                    'city_id' => strip_tags($request->city_id),
                    'complement' => strip_tags($request->complement),
                    'neighborhood' => strip_tags($request->neighborhood),
                    'postal_code' => strip_tags($request->postal_code),
                    'recipient' => strip_tags($request->recipient),
                    'notes' => strip_tags($request->notes),
                    'email' => strip_tags($request->email),
                    'company_id'  => auth()->user()->company_id,   
                    'phone' => strip_tags($request->phone),  
                    'tax_id' => strip_tags($request->tax_id),
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];               
                        
            $client = $this->queryService->CreateClient($data); 
            return response()->json([
              'message' => 'Cliente cadastrado com sucesso!',
               'data' => $client
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

      public function EditClient ($uuid) {        
            $client = $this->queryService->GetClientFromServiceById($uuid);
            $this->authorize('view', $client);
             return response()->json([
            'data' => $client ?? []
            ], 200);       
    }

    public function DeleteClient($uuid)
    {
    try {       
        $client = $this->queryService->GetClientFromServiceById($uuid);      
        $this->authorize('delete', $client);      
        $deleted = $this->queryService->DeleteClientFromService($uuid, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'Cliente eliminado com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Não foi possível eliminar o cliente.'
        ], 400);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

 public function CloneClient($id) {
        $client = $this->queryService->GetClientFromServiceByPrimaryKey($id);
        $this->authorize('view', $client);
        return response()->json([
            'data' => $client ?? []
        ], 200);
    }

public function UpdateClient(ClientFormRequest $request, $uuid)
{
    try {        
        $client = Client::query()->where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $client);     
        $userData = [
                    'name' => strip_tags($request->name),
                    'country_id' => strip_tags($request->country_id),
                    'province_id' => strip_tags($request->province_id),
                    'address' => strip_tags($request->address),
                    'city_id' => strip_tags($request->city_id),
                    'complement' => strip_tags($request->complement),
                    'neighborhood' => strip_tags($request->neighborhood),
                    'postal_code' => strip_tags($request->postal_code),
                    'recipient' => strip_tags($request->recipient),
                    'notes' => strip_tags($request->notes),
                    'email' => strip_tags($request->email),
                    'company_id'  => auth()->user()->company_id,   
                    'phone' => strip_tags($request->phone),  
                    'tax_id' => strip_tags($request->tax_id),
                    'updated_by' => auth()->user()->id
        ];
       
        $client = $this->queryService->UpdateClientFromService($uuid, $userData);
        return response()->json([
            'message' => 'Cliente atualizado com sucesso!',
            'data' => $client
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}   

}
