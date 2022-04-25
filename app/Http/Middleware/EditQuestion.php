<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class EditQuestion
{
    public function handle($request, Closure $next)
    {
        $questionId = $request->route()->parameter('questionId');
        $userId = Question::with('user')->where('id', $questionId)->first()->user->id;
        if ($userId == Auth::id()) {
            return $next($request);
        } else {
            abort(404);
        } 
    }
}
