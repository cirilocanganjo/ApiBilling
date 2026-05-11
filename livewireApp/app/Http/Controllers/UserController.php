<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use \App\Services\{ApiQueryService};
use \App\Models\{Supply, User};
use App\Http\Requests\{UserFormRequest, UserRenamePasswordFormRequest};

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ApiQueryService $queryService)
    {
        $this->queryService = $queryService;  
    }

    public function GetUsers(Request $request) {   
    $this->authorize('viewAny', User::class);   
    $users = $this->queryService->GetUserFromService($request); 
    $users->getCollection()->each(fn($user) => $user
        ->makeHidden([ // campos a serem ocultados
            'password',
            'remember_token',
            'email_verified_at',
            'deleted_at',
            'created_by',
            'updated_by',
        ])
    );

    return response()->json([
        'data' => $users->items(),
        'meta' => [
            'current_page' => $users->currentPage(),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'last_page' => $users->lastPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
        ],

        'links' => [
            'first' => $users->url(1),
            'last'  => $users->url($users->lastPage()),
            'prev'  => $users->previousPageUrl(),
            'next'  => $users->nextPageUrl(),
        ]
    ]);
}

    public function StoreUser (UserFormRequest $request, User $user) {
        try {   
          $this->authorize('create', User::class);            
          $userData = [
            'name'  => strip_tags($request->name), // strip_tags para evitar XSS, aplicando a sanitização.
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'password' => $request->password,
           // 'password_confirmation' => $request->password_confirmation,              
           'company_id' => auth()->user()->company_id,
            'type' => strip_tags($request->type),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ];

            $user = $this->queryService->CreateUser($userData); 
            return response()->json([
              'message' => 'Utilizador cadastrado com sucesso!',
               'data' => $user
            ],200);
           
        } catch (\Throwable $e) { 
             return response()->json([
            'message' => 'Erro interno no servidor',
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTrace(), // ativa se quiser ver tudo
        ], 500);
        }
    }

    public function EditUser ($uuid) {        
           $user = $this->queryService->GetUserFromServiceById($uuid);
           $this->authorize('view', $user);
             return response()->json([
            'data' => $user ?? []
            ], 200);       
    }

    public function CloneUser($id) {
        $user = $this->queryService->GetUserFromServiceByPrimaryKey($id);
        $this->authorize('view', $user);
        return response()->json([
            'data' => $user ?? []
        ], 200);
    }

    public function DeleteUser($uuid)
    {
    try {       
        $user = $this->queryService->GetUserFromServiceById($uuid);      
        $this->authorize('delete', $user);      
        $deleted = $this->queryService->DeleteUserFromService($uuid, auth()->user()->id);

        if ($deleted) {
            return response()->json([
                'message' => 'Utilizador eliminado com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Não foi possível eliminar o utilizador.'
        ], 400);

    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'Ocorreu um erro ao realizar a operação!'
        ], 500);
    }
}

public function UpdateUser(UserFormRequest $request, $uuid)
{
    try {
        // Buscar usuário apenas para autorização
        $user = User::where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $user);
       
        $userData = [
            'name' => strip_tags($request->name) ?? $user->name, 
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL) ,
            'company_id' => auth()->user()->company_id, 
            'type' => strip_tags($request->type) ?? $user->type,
            'updated_by' => auth()->user()->id
        ];

        $user = $this->queryService->UpdateUserFromService($uuid, $userData);
        return response()->json([
            'message' => 'Utilizador atualizado com sucesso!',
            'data' => $user
        ], 200);

    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}   

public function RenamePassword (UserRenamePasswordFormRequest $request, $uuid)  
{
$user = User::where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $user);
        $userData = [
            'password' => $request->password,
        ];

        $user = $this->queryService->RenamUserPasswordFromService($uuid, $userData);
        
        return response()->json([
            'message' => 'Palavra-passe do utilizador atualizada com sucesso!',
            'data' => $user
        ], 200);
}

public function GetUserReport(Request $request)
{
     $this->authorize('viewAny', Supply::class);
    $users_report = $this->queryService->GetUserReportFromService($request);
    $users_report->each(fn ($user) => $user->makeHidden([        
        'password',
        'remember_token',
        'email_verified_at',
        'deleted_at',
        'created_by',
        'updated_by',
    ]));

    return response()->json([
        'data' => $users_report,
    ]);
}


}
