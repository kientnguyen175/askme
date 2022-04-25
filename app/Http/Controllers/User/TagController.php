<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Question;
use App\Models\User;

class TagController extends Controller
{
    public function view($tab)
    {
        if ($tab == 'popular') {
            $tags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->paginate(60);
        }
        if ($tab == 'name') {
            $tags = Tag::withCount('questions')->orderBy('tag', 'asc')->paginate(60);
        }
        if ($tab == 'newest') {
            $tags = Tag::withCount('questions')->orderBy('id', 'desc')->paginate(60);
        }
        $totalTags = Tag::all()->count();
        $totalQuestions = Question::all()->count();
        $topUsers = User::orderByDesc('points')->take(10)->get();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();

        return view('tags', compact(['tags', 'tab', 'totalTags', 'totalQuestions', 'topUsers', 'topTags']));
    }

    public function search($searchText, $tab)
    {
        if ($tab == 'popular') {
            $tags = Tag::withCount('questions')->where('tag', 'like', '%' . $searchText . '%')->orderBy('questions_count', 'desc')->paginate(60);
        }
        if ($tab == 'name') {
            $tags = Tag::withCount('questions')->where('tag', 'like', '%' . $searchText . '%')->orderBy('tag', 'asc')->paginate(60);
        }
        if ($tab == 'newest') {
            $tags = Tag::withCount('questions')->where('tag', 'like', '%' . $searchText . '%')->orderBy('id', 'desc')->paginate(60);
        }
        $totalTags = Tag::where('tag', 'like', '%' . $searchText . '%')->count();
        $topUsers = User::orderByDesc('points')->take(10)->get();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();

        return view('tags', compact(['tags', 'tab', 'searchText', 'totalTags', 'topUsers', 'topTags']));
    }
}
