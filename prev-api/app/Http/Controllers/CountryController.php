<?php

namespace App\Http\Controllers;
use \App\Http\Requests\CountryFormRequest;
use App\Models\Country;
use App\Services\ApiQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  
    }


   public function GetCountries(Request $request) {    
        $this->authorize('viewAny', Country::class);
        $countries = $this->queryService->GetCountriesFromService($request);                                   

        return response()->json([
            'data' => $countries->items(),
            'meta' => [
                'current_page' => $countries->currentPage(),
                'total' => $countries->total(),
                'per_page' => $countries->perPage(),
                'last_page' => $countries->lastPage(),
                'from'  => $countries->firstItem(),
                'to' => $countries->lastItem(),
            ],
            'links' => [
                'first' => $countries->url(1),
                'last'  => $countries->url($countries->lastPage()),
                'prev'  => $countries->previousPageUrl(),
                'next'  => $countries->nextPageUrl(),
            ]
        ]);    
}

  public function StoreCountry (CountryFormRequest $request, Country $country) {
        try { 
            $this->authorize('create', Country::class);        
            $data = [
                    'name' => strip_tags($request->name),
                    'iso_code'  => strip_tags($request->iso_code),   
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];               
                        
            $country = $this->queryService->CreateCountry($data); 
            return response()->json([
              'message' => 'País cadastrado com sucesso!',
               'data' => $country
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }

      public function EditCountry ($id) {        
            $country = $this->queryService->GetCountryFromServiceById($id);
            $this->authorize('view', $country);
             return response()->json([
            'data' => $country ?? []
            ], 200);       
    }

    public function DeleteCountry($id)
    {
    try {       
        $country = $this->queryService->GetCountryFromServiceById($id);      
        $this->authorize('delete', $country);      
        $deleted = $this->queryService->DeleteCountryFromService($id, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'País eliminado com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Não foi possível eliminar o país.'
        ], 400);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

public function UpdateCountry(CountryFormRequest $request, $id)
{
    try {
        $country = Country::findOrFail($id);
        $this->authorize('update', $country);
        
        if ($request->filled('iso_code') && $request->iso_code != $country->iso_code) {  // Verificar se pede alteração de iso_code e se já existe em outra cidade
            $exists = Country::where('iso_code', $request->iso_code)
                ->where('id', '!=', $country->id)
                ->exists();           

            $country->iso_code = strip_tags($request->iso_code) ?? $country->iso_code;
        }

        $country->name = strip_tags($request->name);
        $country->updated_by = auth()->user()->id;
        $country->save();


        return response()->json([
            'message' => 'País atualizado com sucesso!',
            'data' => $country
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}



}
