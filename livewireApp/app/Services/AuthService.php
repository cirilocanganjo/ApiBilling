<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
     public static function updateLastLoginOfAuthenticatedUser(): void
    {
        $user = auth()->user();
        if ($user) {
            User::query()->where('id', $user->id)               
                ->update([
                   'last_login_at' => date('Y-m-d H:i:s'),
                ]);
            }
    }

}