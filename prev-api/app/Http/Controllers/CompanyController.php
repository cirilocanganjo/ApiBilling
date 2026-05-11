<?php

namespace App\Http\Controllers;
use App\Http\Requests\CompanyFormRequest;
use App\Models\Company;
use App\Services\ApiQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use AuthorizesRequests;
    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  
    }

    
   public function GetCompanies(Request $request) {    
        $this->authorize('viewAny', Company::class);
        $companies = $this->queryService->GetCompanyFromService($request);                                   

        return response()->json([
            'data' => $companies->items(),
            'meta' => [
                'current_page' => $companies->currentPage(),
                'total' => $companies->total(),
                'per_page' => $companies->perPage(),
                'last_page' => $companies->lastPage(),
                'from'  => $companies->firstItem(),
                'to' => $companies->lastItem(),
            ],
            'links' => [
                'first' => $companies->url(1),
                'last'  => $companies->url($companies->lastPage()),
                'prev'  => $companies->previousPageUrl(),
                'next'  => $companies->nextPageUrl(),
            ]
        ]);    
}

    public function StoreCompany (CompanyFormRequest $request, Company $company) {
        try { 
            $this->authorize('create', Company::class);        
            $data = [
                    'name' => strip_tags($request->name),
                    'address' => strip_tags($request->address),
                    'email'  => filter_var($request->email, FILTER_SANITIZE_EMAIL),
                    'nif'  => strip_tags($request->nif),   
                    'phone' => strip_tags($request->phone),  
                    'reference' => strip_tags($request->reference),
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];
                        
            $company = $this->queryService->CreateCompany($data); 
            return response()->json([
              'message' => 'Empresa cadastrada com sucesso!',
               'data' => $company
            ],200);
         
        } catch (\Throwable $th) {           
           return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

     public function EditCompany ($uuid) {
            $company = $this->queryService->GetCompanyFromServiceById($uuid);
            $this->authorize('view', $company);
             return response()->json([
            'data' => $company ?? []
            ], 200);     
    }

     public function DeleteCompany($uuid) {
        try {
            $company = $this->queryService->GetCompanyFromServiceById($uuid);
            $this->authorize('delete', $company);
            $deleted = $this->queryService->DeleteCompanyFromService($uuid, auth()->user()->id);
            if ($deleted) {
              return response()->json(['message' => 'Empresa eliminada com sucesso!'],200);
            }
        
        } catch (\Throwable $th) {        
         return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

    public function UpdateCompany(CompanyFormRequest $request, $uuid)
   {
    try {        
        $company = Company::where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $company);

        $companyData = [
            'name' => strip_tags($request->name) ?? $company->name,          
            'address' => strip_tags($request->address) ?? $company->address,       
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'phone' => strip_tags($request->phone) ?? $company->phone,         
            'nif'  => strip_tags($request->nif) ?? $company->nif,           
            'reference' => strip_tags($request->reference) ?? $company->reference,     
            'status' => strip_tags($request->status) ?? $company->status,                 
            'updated_by'=> auth()->user()->id ,
        ];
       
        $company = $this->queryService->UpdateCompanyFromService($uuid, $companyData);
        return response()->json([
            'message' => 'Empresa atualizada com sucesso!',
            'data' => $company
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

}
