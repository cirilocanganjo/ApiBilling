<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        $token = $request->user()?->currentAccessToken();  // Pegar o token atual do usuário autenticado       
        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido'
            ], 401);
        }
       
        $minutes = (int) config('api.token_expiration_minutes',120);  // Tempo de expiração do token em minutos, definido no .env      
        if ($token->created_at->addMinutes($minutes)->isPast()) {   // Verifica se o token expirou          
            $token->delete();   // Remove token expirado do banco
            return response()->json([
                'success' => false,
                'message' => 'Token expirado'
            ], 401);
        }
        
        return $next($request);
    }
}
