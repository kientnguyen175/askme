<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\User;
use App\Models\Tag;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $newestQuestions = Question::with(['content', 'user', 'answers', 'tags'])->where('status', 1)->orderByDesc('id')->take(15)->get();
        $unansweredQuestions = Question::with(['content', 'user', 'answers', 'tags'])->where('status', 1)->where('best_answer_id', null)->orderByDesc('id')->take(15)->get();
        $votesQuestions = Question::with(['content', 'user', 'answers', 'tags'])->where('status', 1)->orderByDesc('vote_number')->take(15)->get();
        $topUsers = User::orderByDesc('points')->take(10)->get();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();

        return view('home', compact(['newestQuestions', 'unansweredQuestions', 'votesQuestions', 'topUsers', 'topTags']));
    }

    // public function search(Request $request)
    // {
    //     if($request->textSearch){
    //         $items = Question::search($request->input('textSearch'))->toArray();
    //     }
    //     dd($items);
    // }
}
