<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;

class EditAnswer
{
    public function handle($request, Closure $next)
    {
        $answerId = $request->route()->parameter('answerId');
        $authorId = Answer::with('user')->where('id', $answerId)->first()->user->id;
        if ($authorId == Auth::id()) {
            return $next($request);
        } else {
            abort(404);
        } 
    }
}
