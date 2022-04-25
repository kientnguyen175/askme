<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\NewCommentNoti;

class CommentController extends Controller
{
    public function store(Request $request, $answerId)
    {
        $newComment = Comment::create([
            'answer_id' => $answerId,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'updated' => 0
        ]);
        // notify to answer author
        $answerAuthor = Answer::find($answerId)->user;
        // neu comment author != answer author
        if (Auth::id() != $answerAuthor->id) {
            $page = (int) ceil(Question::find($request->questionId)->answers->where('id', '<=', $answerId)->count() / 5);
            $answerAuthor->notify(new NewCommentNoti([
                'question_id' => $request->questionId,
                'question_title' => Question::find($request->questionId)->title,
                'answer_id' => $answerId,
                'answer_author_id' => $answerAuthor->id,
                'comment_user_name' => $request->commentUserName,
                'comment_user_avatar' => $request->commentUserAvatar,
                'comment_id' => $newComment->id,
                'page' => $page
            ]));
        }

        return response()->json([
            'response' => 1, 
            'time' => 'Just now',
            'comment_id' => $newComment->id,
        ]);
    }

    public function destroy($commentId)
    {
        Comment::find($commentId)->delete();
    }

    public function update(Request $request, $commentId)
    {
        Comment::find($commentId)->update([
            'comment' => $request->comment,
            'updated' => 1
        ]);
        
        return response()->json(['commentId' => $commentId, 'comment' => $request->comment]);
    }
}
