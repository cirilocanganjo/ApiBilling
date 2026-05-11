<?php

namespace App\Http\Controllers;
use \App\Models\{User};
use App\Http\Requests\AuthFormRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{ 
   public $superAdmin;
    
public function __construct() {  
  $this->verifyIfAlreadyHasSuperAdminUserProfileStored();
}

public function verifyIfAlreadyHasSuperAdminUserProfileStored () {
try {      
   DB::beginTransaction(); 
    $this->superAdmin = User::where('type', 'SUPER_ADMIN')->get();
   if ($this->superAdmin->isNotEmpty() && $this->superAdmin->count() > 1) {    
       User::query()->where('email', 's_admin@email.com')->forceDelete();
   }else{
      User::create([
        'name' => 'Super Admin Teste',
        'email' => 's_admin@email.com',
        'type' => 'SUPER_ADMIN',        
        'password' => Hash::make('12345678'),
        'company_id' => 0,
        'status' => 'active',
        'is_deleted' => false
      ]);
    }
    DB::commit();
} catch (\Throwable $th) {
  DB::rollback();
  return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
}
}

public function authenticateApi(AuthFormRequest $request) {
    
        try {           
            
            $credentials = $request->only('email', 'password');
            
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Email ou senha incorretos.'
                ], 401);
            }
        
            $user = Auth::user();        
            if ($user->status !== 'active') {
                Auth::logout(); 
                return response()->json(['message' => 'Sua conta está inativa. Contate o administrador.' ], 403);                   
            }               
        
            $token = $user->createToken('auth_token')->plainTextToken;
        
            return response()->json([
                'message'      => 'Login realizado com sucesso!',
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user' => [
                    'id'   => $user->id,
                    'name' => $user->name,
                    'email'=> $user->email,
                    'type' => $user->type,
                ]
            ], 200);   
        
        } catch (\Throwable $th) {
         return response()->json(['error' => 'Ocorreu um erro ao realizar a operação!']);
        }
    }
    
 

}