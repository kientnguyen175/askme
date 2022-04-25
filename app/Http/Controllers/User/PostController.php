<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Notifications\PublishQuestion;
use App\Notifications\NewQuestionToFollowers;

class PostController extends Controller
{
    public function create()
    {
        $avatar = Auth::user()->avatar;
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(9)->get(); 

        return view('ask_question', compact(['avatar', 'topTags']));
    }

    public function store(Request $request)
    {   
        $this->validate($request, 
            [
                'title' => ['required', 'string', 'max:255'],
                'tags' => ['required'],
                'photos.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
                'audios.*' => 'mimetypes:audio/mpeg,video/webm,audio/ogg|max:3072'
            ]
	    ); 
        if (!$request->content) {
            return response()->json(['response' => 0]); 
        } 
        if ($request->datetime) {
            if (Carbon::parse($request->datetime)->subHours(7)->lte(Carbon::now()->addMinutes(0))) {
                return response()->json(['response' => 2]); 
            }
        }
        // save question
        if ($request->datetime) {
            $question = Question::create([
                'user_id' => Auth::id(),
                'title' => trim($request->title),
                'schedule_time' => Carbon::parse($request->datetime)->subHours(7)->format('Y-m-d H:i:s'),
                'status' => 0
            ]);
        } else {
            $question = Question::create([
                'user_id' => Auth::id(),
                'title' => trim($request->title)
            ]);
        }
        DB::transaction(function () use ($request, $question) {
            // save tags
            $tags = explode(",", $request->tags);
            $formattedTags = [];
            foreach ($tags as $tag) {
                $element = [
                    'tag' => $tag
                ];
                array_push($formattedTags, $element);
            }
            DB::table('tags')->insertOrIgnore($formattedTags);
            $ids = Tag::whereIn('tag', $tags)->select('id')->get()->toArray();
            $ids = Arr::flatten($ids);
            $question->tags()->attach($ids);
            // save content
            $question->content()->create([
                'content' => $request->content,
                'updated' => 0
            ]);
            // save images
            if ($request->photos) {
                foreach ($request->photos as $image) {
                    if (in_array($image->getClientOriginalName(), array_filter(explode(",", $request->imgUrls)))) {
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        $whereToSaveImage = public_path('images/uploads');
                        $image->move($whereToSaveImage, $imageName);
                        $url = "http://localhost:8000/images/uploads/$imageName" ;
                        $question->images()->create([
                            'url' => $url
                        ]);
                    };
                }
            } 
            // save medias
            if ($request->audios) {
                foreach ($request->audios as $media) {
                    $mediaName = time() . '_' . $media->getClientOriginalName();
                    $whereToSaveMedia = public_path('medias');
                    $media->move($whereToSaveMedia, $mediaName);
                    $url = "http://localhost:8000/medias/$mediaName" ;
                    $question->medias()->create([
                        'url' => $url
                    ]);
                }
            } 
        });
        
        if ($request->datetime) {
            return response()->json(['response' => 1, 'schedule' => 1]);
        } else {
            // add to index ES
            $question->addToIndex();
            //notify
            // tìm những người theo dõi tac gia của câu hỏi này
            $followers = Auth::user()->follows;
            // tạo thông báo tới các followers
            foreach ($followers as $follower) {
                $followerData = [
                    'follower_id' => $follower->model_id,
                    'user_avatar' => Auth::user()->avatar ?? asset('images/default_avatar.png'),
                    'user_name' => Auth::user()->name,
                    'question_id' => $question->id,
                ];
                User::find($follower->model_id)->notify(new NewQuestionToFollowers($followerData));
            }

            return response()->json(['response' => 1, 'schedule' => 0, 'question_id' => $question->id]);
        }
    }

    public function newsfeed()
    {
        $user = Auth::user();
        $questions = Question::with(['content', 'tags', 'answers.content'])->where('user_id', $user->id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();
        $totalQuestions = $user->questions->where('status', 1)->count();

        return view('newsfeed', compact(['questions', 'user', 'topTags', 'totalQuestions']));
    }

    public function pending()
    {
        $user = Auth::user();
        $questions = Question::with(['content', 'tags', 'answers.content'])->where('user_id', $user->id)->where('status', 0)->orderBy('created_at', 'desc')->paginate(10);

        return view('pending_question', compact(['questions', 'user']));
    }
}
