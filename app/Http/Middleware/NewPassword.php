<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class NewPassword
{
    public function handle($request, Closure $next)
    {
        $userId = $request->route()->parameter('userId');
        $hashedToken = sha1($request->route()->parameter('token'));
        $user = User::find($userId);
        if ($user->reset_password_token == $hashedToken) {
            return $next($request);
        } else {
            abort(404);
        } 
    }
}
