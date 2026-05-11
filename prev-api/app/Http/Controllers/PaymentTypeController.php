<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentTypeFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\PaymentType;
use App\Services\ApiQueryService;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    use AuthorizesRequests;
     public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  
    }
    public function GetPaymentTypes (Request $request) {
         $this->authorize('viewAny', PaymentType::class);
         $payment_types = $this->queryService->GetPaymentTypeFromService($request);

         return response()->json([
            'data' => $payment_types->items(),
            'meta' => [
                'current_page' => $payment_types->currentPage(),
                'total' => $payment_types->total(),
                'per_page' => $payment_types->perPage(),
                'last_page' => $payment_types->lastPage(),
                'from'  => $payment_types->firstItem(),
                'to' => $payment_types->lastItem(),
            ],
            'links' => [
                'first' => $payment_types->url(1),
                'last'  => $payment_types->url($payment_types->lastPage()),
                'prev'  => $payment_types->previousPageUrl(),
                'next'  => $payment_types->nextPageUrl(),
            ]
        ]);  
    }

      public function StorePaymentType (PaymentTypeFormRequest $request, PaymentType $payment_type) {
        try { 

            $this->authorize('create', PaymentType::class);        
            $data = [
                    'name' => strip_tags($request->name),
                    'description' => strip_tags($request->description),
                    'change' => strip_tags($request->change),
                    'status' => strip_tags($request->status),
                    'user_id'  => auth()->user()->id,
                ];               
                        
            $payment_type = $this->queryService->CreatePaymentType($data); 
            return response()->json([
              'message' => 'Tipo de pagamento cadastrado com sucesso!',
               'data' => $payment_type
            ],200);
         
        } catch (\Throwable $th) {    
          return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }
}
