<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Content;
use App\Models\Tag;
use App\Models\Follow;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Jobs\NewPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Events\ShowQuestion;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function followers($userId) {
        $followers = Follow::where('followable_id', $userId)->where('followable_type', 'App\Models\User')->paginate(66);
        $totalFollowers = Follow::where('followable_id', $userId)->where('followable_type', 'App\Models\User')->count();

        return view('followers', compact(['followers', 'totalFollowers']));
    }

    public function answers($userId) {
        $user = User::find($userId);
        $answers = Answer::with(['content', 'comments'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();
        $totalAnswers = $user->answers->count();
        $totalBestAnswers = Question::whereIn('best_answer_id', $user->answers->pluck('id')->toArray())->count();

        return view('user_answers', compact(['answers', 'user', 'topTags', 'totalAnswers', 'totalBestAnswers']));
    }

    public function show($userId)
    {
        $user = User::with(['questions', 'answers'])->where('id', $userId)->first();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();
        $checkFollowUser = DB::table('follows')->where('followable_id', $userId)->where('followable_type', 'App\Models\User')->where('model_id', Auth::id())->count();
        $totalFollowers = $user->follows->count();

        return view('user_profile', compact(['checkFollowUser', 'user', 'topTags', 'totalFollowers']));
    }

    public function showBy($username)
    {
        $user = User::with(['questions', 'answers'])->where('username', $username)->first();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();
        $checkFollowUser = DB::table('follows')->where('followable_id', $userId)->where('followable_type', 'App\Models\User')->where('model_id',  Auth::id())->count();
        $totalFollowers = $user->follows->count();

        return view('user_profile', compact(['checkFollowUser', 'user', 'topTags', 'totalFollowers']));
    }

    public function edit()
    {
        $user = Auth::user();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();

        return view('edit_profile', compact(['user', 'topTags']));
    }

    public function update(Request $request)
    {
        $this->validate($request, 
            [
                'name' => ['required', 'string', 'max:255']
            ],			
            [
                'username' => ['string', 'max:255']
            ],			
            [
                'name.required' => 'You have to fill out this field!',
            ]
	    );
        $avatarFile = $request->file('avatar');
        if ($avatarFile) {
            $this->validate($request, 
                [
                    'avatar' => 'image|mimes:jpg,jpeg,png,gif|max:2048', // 2048 = 2M
                ],
            );
            $avatar = time() . '_' . $avatarFile->getClientOriginalName();
            $destinationPath = public_path('images/avatars');
            $avatarFile->move($destinationPath, $avatar);
            $avatar = "http://localhost:8000/images/avatars/$avatar" ;
        }
        if ($request->website_link) {
            $request->website_link = str_ireplace('https://', '', $request->website_link);
        }
        $user = Auth::user();
        $user->update([
            'avatar' => isset($avatar) ? $avatar : $user->avatar,
            'bio' => $request->bio,
            'name' => $request->name,
            'website_link' => $request->website_link
        ]);
        if (!$user->username) {
            $user->update([
                'username' => $request->username
            ]);

            return response()->json(['website_link' => $request->website_link, 'name' => $request->name, 'username' => $request->username]);
        }

        return response()->json(['website_link' => $request->website_link, 'name' => $request->name]);
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, 
            [
                'old_password' => ['required', 'string', 'min:8'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
        );
        $user = Auth::user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json(['response' => 1]);
        }

        return response()->json(['response' => 0]);
    }

    public function sendResetPasswordLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('email-error', 'The e-mail does not exist!');
        }
        $token = Str::random(8);
        $hashedToken = sha1($token); 
        $user->update([
            'reset_password_token' => $hashedToken
        ]);
        $data = [
            'token' => $token,
            'userId' => $user->id
        ];
        dispatch(new NewPassword($data, $request->email));
           
        return redirect()->route('login')->with('mail-successfully', true);
    }

    public function newPassword(Request $request)
    {
        $userId = $request->route('userId');

        return view('new_password', compact('userId'));
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, 
            [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
        );
        $user = User::find($request->route('userId'));
        $user->update([
            'password' => Hash::make($request->password),
            'reset_password_token' => null
        ]);

        return redirect()->route('login')->with('new-password', true);
    }

    public function newsfeed($id)
    {
        $user = User::find($id);
        $questions = Question::with(['content', 'tags', 'answers.content'])->where('user_id', $user->id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();
        $totalQuestions = $user->questions->where('status', 1)->count();

        return view('newsfeed', compact(['questions', 'user', 'topTags', 'totalQuestions']));
    }

    public function view($tab)
    {
        if ($tab == 'points') {
            $users = User::orderBy('points', 'desc')->paginate(66);
        }
        if ($tab == 'name') {
            $users = User::orderBy('name', 'asc')->paginate(66);
        }
        if ($tab == 'newest') {
            $users = User::orderBy('id', 'desc')->paginate(66);
        }
        $topUsers = User::orderByDesc('points')->take(10)->get();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();
        $totalUsers = User::all()->count();

        return view('users', compact(['users', 'tab', 'topUsers', 'topTags', 'totalUsers']));
    }

    public function search($searchText, $tab)
    {
        if ($tab == 'points') {
            $users = User::where('name', 'like', '%' . $searchText . '%')->orWhere('username', 'like', '%' . $searchText . '%')->orderBy('points', 'desc')->paginate(66);
        }
        if ($tab == 'name') {
            $users = User::where('name', 'like', '%' . $searchText . '%')->orWhere('username', 'like', '%' . $searchText . '%')->orderBy('name', 'asc')->paginate(66);
        }
        if ($tab == 'newest') {
            $users = User::where('name', 'like', '%' . $searchText . '%')->orWhere('username', 'like', '%' . $searchText . '%')->orderBy('id', 'desc')->paginate(66);
        }
        $totalUsers = $users->count();
        $topUsers = User::orderByDesc('points')->take(10)->get();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();

        return view('users', compact(['users', 'tab', 'searchText', 'totalUsers', 'topUsers', 'topTags']));
    }

    public function saveQuestion($questionId)
    {
        DB::table('saves')->insert([
            'user_id' => Auth::id(),
            'question_id' => $questionId
        ]);
    }

    public function unsaveQuestion($questionId)
    {
        DB::table('saves')->where('user_id', Auth::id())->where('question_id', $questionId)->delete();
        $collections = Collection::where('user_id', Auth::id())->get();
        foreach ($collections as $collection) {
            $collection->questions()->detach($questionId);
            if ($collection->questions->count() == 0) $collection->delete();
        }
    }

    public function saveToCollection(Request $request, $questionId)
    {
        if ($request->saveOptions != 'old' && $request->saveOptions != 'new') {
            return response()->json(['response' => 1]);
        }
        if ($request->saveOptions == 'old' && $request->chooseCollection == null) {
            if (Auth::user()->collections->count() == 0) {
                return response()->json(['response' => -1]);
            } else {
                return response()->json(['response' => 0]);
            }
        }
        if ($request->saveOptions == 'old' && $request->chooseCollection != null) {
            DB::table('saves')->where('user_id', Auth::id())->where('question_id',$questionId)->delete();
            $question = Question::find($questionId);
            $question->collections()->attach($request->chooseCollection);

            return response()->json(['response' => 4]);
        }
        if ($request->saveOptions == null) {
            return response()->json(['response' => 1]);
        }
        if ($request->saveOptions == 'new' && $request->title == null) {
            return response()->json(['response' => 2]);
        }
        if ($request->saveOptions == 'new' && $request->title != null) {
            DB::table('saves')->where('user_id', Auth::id())->where('question_id',$questionId)->delete();
            $question = Question::find($questionId);
            if (isset($request->image)) {
                $collectionImageName = time() . '_' . $request->image->getClientOriginalName();
                $whereToSaveCollectionImage = public_path('images/uploads');
                $request->image->move($whereToSaveCollectionImage, $collectionImageName);
                $url = "http://localhost:8000/images/uploads/$collectionImageName" ;
            }
            $newCollection = $question->collections()->create([
                'user_id' => Auth::id(),
                'name' => $request->title,
                'image' => $url ?? asset('images/default_collection.jpg')
            ]);

            return response()->json(['response' => 3, 'newCollectionId' => $newCollection->id, 'newCollectionName' => $newCollection->name]);
        }
    }

    public function savedQuestions()
    {
        $collections = Collection::where('user_id', Auth::id())->get();
        $singleQuestionIds = DB::table('saves')
            ->join('questions', 'saves.question_id', '=', 'questions.id')
            ->select('saves.question_id')
            ->where('saves.user_id', Auth::id())
            ->get()
            ->pluck('question_id')->toArray();
        if (count($singleQuestionIds) > 0) {
            $singleQuestions = Question::with(['user', 'content', 'tags'])->whereIn('id', $singleQuestionIds)->orderByRaw(DB::raw(sprintf('FIELD(id, %s)', implode(',', $singleQuestionIds))))->get();

            return view('saved_questions', compact(['collections', 'singleQuestions']));
        }
        
        return view('saved_questions', compact('collections'));
    }

    public function followUser($userId)
    {
        User::find($userId)->follows()->create([
            'model_id' => Auth::id()
        ]);
        // Auth::user()->follows()->create([
        //     'model_id' => $userId
        // ]);
    }

    public function unfollowUser($userId)
    {
        User::find($userId)->follows()->where('model_id', Auth::id())->delete();
    }

    public function elasticsearchDSL($searchText, $fields) {
        return [
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $searchText,
                        'fields' => $fields,
                        'fuzziness' => 'AUTO'
                    ]
                ]
            ]
        ];
    }

    public function readNotiPublishQuestion($notiId, $id)
    {
        $question = Question::with(['images', 'medias', 'votes', 'content', 'user', 'tags', 'answers.comments.user', 'answers.user', 'answers.votes', 'answers.content', 'answers.conversation', 'answers.comments.user'])->where('id', $id)->first();
        $answers = Answer::with(['images', 'medias', 'user', 'comments.user'])->where('question_id', $id)->paginate(5);
        $votedCheck = $question->votes->where('user_id', Auth::id())->first();
        $answerUserIds = [];
        $answerUserNames = [];
        $answerUserAvatars = [];
        $answerContents = [];
        $answerConversations = [];
        $answerVotedCheck = [];
        $answerIds = [];
        foreach ($question->answers as $key => $answer) {
            array_push($answerUserIds, $answer->user->id);
            array_push($answerUserNames, $answer->user->name);
            array_push($answerUserAvatars, $answer->user->avatar);
            array_push($answerContents, $answer->content->content);
            array_push($answerConversations, $answer->conversation->conversation ?? '[]');
            array_push($answerIds, $answer->id);
            if (!$answer->votes->where('user_id', Auth::id())->first()) {
                array_push($answerVotedCheck, 0);
            } else {
                array_push($answerVotedCheck, 1);
            }
        }
        $sortBy = 'oldest';
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();
        $relatedTitles = Question::complexSearch($this->elasticsearchDSL($question->title, ['title']))->getHits()['hits'];
        $relatedContents = Content::complexSearch($this->elasticsearchDSL($question->content->content, ['content']))->getHits()['hits'];
        $idsTitle = array_map(function($item){
            return (int) $item['_id'];
        }, $relatedTitles);
        $idsContent = array_map(function($item){
            if ($item['_source']['contentable_type'] == "App\Models\Question") return (int) $item['_source']['contentable_id'];
        }, $relatedContents);
        $ids = array_unique(
            array_merge($idsTitle, $idsContent)
        );
        $ids = array_filter($ids);
        $relatedQuestions = Question::with(['user', 'content'])->whereIn('id', $ids)->orderByRaw(DB::raw(sprintf('FIELD(id, %s)', implode(',', $ids))))->skip(1)->take(5)->get();
        if (Auth::check()) {
            $saveQuestion = DB::table('saves')->where('user_id', Auth::id())->where('question_id', $question->id)->count();
            $saveQuestion = $saveQuestion || DB::table('collections')
                ->join('collection_question', 'collections.id', '=', 'collection_question.collection_id')
                ->where('user_id', Auth::id())
                ->where('question_id', $question->id)
                ->count();
            $collections = Auth::user()->collections;
            event(new ShowQuestion($question));
            // check follow author
            $checkFollowAuthor = DB::table('follows')->where('followable_id', Auth::id())->where('followable_type', 'App\Models\User')->where('model_id', $question->user->id)->count();
            $checkFollowQuestion = DB::table('follows')->where('followable_id', $question->id)->where('followable_type', 'App\Models\Question')->where('model_id', Auth::id())->count();
            // read noti publish question
            auth()->user()->notifications()->find($notiId)->markAsRead();

            return view('question_details', compact(['checkFollowQuestion', 'checkFollowAuthor', 'collections', 'saveQuestion', 'relatedQuestions', 'topTags', 'question', 'answers', 'votedCheck', 'answerUserIds', 'answerUserNames', 'answerUserAvatars', 'answerContents', 'answerConversations', 'answerVotedCheck', 'answerIds', 'sortBy']));
        } else {
            $saveQuestion = 0;
            event(new ShowQuestion($question));
            // read noti publish question
            auth()->user()->notifications()->find($notiId)->markAsRead();

            return view('question_details', compact(['relatedQuestions', 'topTags', 'question', 'answers', 'votedCheck', 'answerUserIds', 'answerUserNames', 'answerUserAvatars', 'answerContents', 'answerConversations', 'answerVotedCheck', 'answerIds', 'sortBy']));
        }
    }

    public function followQuestion($questionId)
    {
        Question::find($questionId)->follows()->create([
            'model_id' => Auth::id()
        ]);
    }

    public function unfollowQuestion($questionId)
    {
        Question::find($questionId)->follows()->where('model_id', Auth::id())->delete();
    }

    public function readNotiNewAnswer($notiId, $questionId, $newAnswerId)
    {
        $noti = auth()->user()->notifications()->find($notiId);
        $noti->markAsRead();
        $responseData = [
            'response' => 1, 
            'newAnswerId' => $newAnswerId,
            'questionId' => $questionId,
            'newAnswerPage' => $noti->data['newAnswerPage']
        ];

        return response()->json($responseData);
    }

    public function readNewCommentNoti($notiId, $questionId, $commentId, $page) {
        $noti = auth()->user()->notifications()->find($notiId);
        $noti->markAsRead();

        return Redirect::to('http://localhost:8000/questions/' . $questionId . '?page=' . $page . '#comment-' . $commentId);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }
}
