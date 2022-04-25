<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class PendingQuestion
{
    public function handle($request, Closure $next)
    {
        $questionId = $request->route()->parameter('questionId');
        $question = Question::with('user')->where('id', $questionId)->first();
        $authorId = $question->user->id;
        if ($authorId != Auth::id() && $question->status == 0) {
            abort(404);
        } else {
            return $next($request);
        } 
    }
}
