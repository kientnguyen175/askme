<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Content;
use App\Models\Media;
use App\Models\Tag;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Events\ShowQuestion; // count view

class QuestionController extends Controller
{
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

    public function show($id)
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
        if ($ids != []) {
            $relatedQuestions = Question::with(['user', 'content'])->whereIn('id', $ids)->orderByRaw(DB::raw(sprintf('FIELD(id, %s)', implode(',', $ids))))->skip(1)->take(5)->get();
        } else {
            $relatedQuestions = collect(new Question);
        }
        if (Auth::check()) {
            $saveQuestion = DB::table('saves')->where('user_id', Auth::id())->where('question_id', $question->id)->count();
            $saveQuestion = $saveQuestion || DB::table('collections')
                ->join('collection_question', 'collections.id', '=', 'collection_question.collection_id')
                ->where('user_id', Auth::id())
                ->where('question_id', $question->id)
                ->count();
            $collections = Auth::user()->collections;
            if ($question->status == 1) event(new ShowQuestion($question));
            // check follow author
            $checkFollowAuthor = DB::table('follows')->where('followable_id', $question->user->id)->where('followable_type', 'App\Models\User')->where('model_id', Auth::id())->count();
            $checkFollowQuestion = DB::table('follows')->where('followable_id', $question->id)->where('followable_type', 'App\Models\Question')->where('model_id', Auth::id())->count();
            
            return view('question_details', compact(['checkFollowQuestion', 'checkFollowAuthor', 'collections', 'saveQuestion', 'relatedQuestions', 'topTags', 'question', 'answers', 'votedCheck', 'answerUserIds', 'answerUserNames', 'answerUserAvatars', 'answerContents', 'answerConversations', 'answerVotedCheck', 'answerIds', 'sortBy']));
        } else {
            $saveQuestion = 0;
            if ($question->status == 1) event(new ShowQuestion($question));

            return view('question_details', compact(['relatedQuestions', 'topTags', 'question', 'answers', 'votedCheck', 'answerUserIds', 'answerUserNames', 'answerUserAvatars', 'answerContents', 'answerConversations', 'answerVotedCheck', 'answerIds', 'sortBy']));
        }
    }

    public function showBy($id, $sortBy)
    {
        $question = Question::with(['images', 'medias', 'votes', 'content', 'user', 'tags', 'answers.comments.user', 'answers.user', 'answers.votes', 'answers.content', 'answers.conversation', 'answers.comments.user'])->where('id', $id)->first();
        $allAnswers = Answer::with(['images', 'medias', 'user', 'content', 'conversation', 'votes', 'comments.user'])->where('question_id', $id)->orderBy($sortBy, 'desc')->orderBy('id', 'asc')->get();
        $answers = Answer::with(['images', 'medias', 'user'])->where('question_id', $id)->orderBy($sortBy, 'desc')->orderBy('id', 'asc')->paginate(5);
        $votedCheck = $question->votes->where('user_id', Auth::id())->first();
        $answerUserIds = [];
        $answerUserNames = [];
        $answerUserAvatars = [];
        $answerContents = [];
        $answerConversations = [];
        $answerVotedCheck = [];
        $answerIds = [];
        foreach ($allAnswers as $answer) {
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
        if ($ids != []) {
            $relatedQuestions = Question::with(['user', 'content'])->whereIn('id', $ids)->orderByRaw(DB::raw(sprintf('FIELD(id, %s)', implode(',', $ids))))->skip(1)->take(5)->get();
        } else {
            $relatedQuestions = collect(new Question);
        }
        if (Auth::check()) {
            $saveQuestion = DB::table('saves')->where('user_id', Auth::id())->where('question_id', $question->id)->count();
            $saveQuestion = $saveQuestion || DB::table('collections')
                ->join('collection_question', 'collections.id', '=', 'collection_question.collection_id')
                ->where('user_id', Auth::id())
                ->where('question_id', $question->id)
                ->count();
            $collections = Auth::user()->collections;
            if ($question->status == 1) event(new ShowQuestion($question));
            // check follow author
            $checkFollowAuthor = DB::table('follows')->where('followable_id', $question->user->id)->where('followable_type', 'App\Models\User')->where('model_id', Auth::id())->count();
            $checkFollowQuestion = DB::table('follows')->where('followable_id', $question->id)->where('followable_type', 'App\Models\Question')->where('model_id', Auth::id())->count();
            
            return view('question_details', compact(['checkFollowQuestion', 'checkFollowAuthor', 'collections', 'saveQuestion', 'relatedQuestions', 'topTags', 'question', 'answers', 'votedCheck', 'answerUserIds', 'answerUserNames', 'answerUserAvatars', 'answerContents', 'answerConversations', 'answerVotedCheck', 'answerIds', 'sortBy']));
        } else {
            $saveQuestion = 0;
            if ($question->status == 1) event(new ShowQuestion($question));
            
            return view('question_details', compact(['relatedQuestions', 'topTags', 'question', 'answers', 'votedCheck', 'answerUserIds', 'answerUserNames', 'answerUserAvatars', 'answerContents', 'answerConversations', 'answerVotedCheck', 'answerIds', 'sortBy']));
        }
    }

    public function vote($id)
    {
        $question = Question::with('votes')->where('id', $id)->first();
        $userId = Auth::id();
        $votedCheck = $question->votes->where('user_id', $userId)->first();
        if (!$votedCheck) {
            DB::transaction(function () use ($question, $userId) {
                $question->update([
                    'vote_number' => ++$question->vote_number
                ]);
                $question->votes()->create([
                    'user_id' => $userId
                ]);
                $question->user()->update([
                    'points' => ++$question->user->points
                ]);
            });

            return response()->json(['response' => 1]);
        } else {
            DB::transaction(function () use ($question, $userId) {
                $question->update([
                    'vote_number' => --$question->vote_number
                ]);
                $question->votes()->where('user_id', $userId)->delete();
                $question->user()->update([
                    'points' => --$question->user->points
                ]);
            });
            
            return response()->json(['response' => 0]);
        }
    }

    public function bestAnswer(Request $request, $questionId)
    {
        $question = Question::where('id', $questionId)->first();
        $question->update([
            'best_answer_id' => $request->answerId,
        ]);
        $answerAuthor = Answer::find($request->answerId)->user;
        $answerAuthor->update([
            'points' => $answerAuthor->points + 10
        ]);
        if ($question->solved_at == null) {
            $question->update([
                'solved_at' => Carbon::now()
            ]);
        }

        return response()->json(['response' => 1, 'answerId' => $request->answerId]);
    }

    public function destroy($questionId)
    {
        // lay cau hoi
        $question = Question::with(['tags', 'medias', 'images', 'votes', 'answers.medias', 'answers.images', 'answers.votes', 'answers.content', 'answers.conversation', 'answers.comments'])->where('id', $questionId)->first();

        DB::transaction(function () use ($question, $questionId) {
            // lay cac tagIds can xoa
            $tagIds = array_column($question->tags->toArray(), 'id');
            $deleteTags = [];
            $questionCountForEachTag = DB::table('question_tag')
                ->select(DB::raw('count(*) as question_count, tag_id'))
                ->whereIn('tag_id', $tagIds)
                ->groupBy('tag_id')
                ->get();
            foreach ($questionCountForEachTag as $key => $questionCount) {
                if ($questionCount->question_count == 1) 
                    array_push($deleteTags, $questionCount->tag_id);
            }
            // xoa cac tags
            DB::table('question_tag')->where('question_id', $questionId)->delete();
            Tag::whereIn('id', $deleteTags)->delete();
            
            // xoa medias cua question
            $mediaPaths = [];
            foreach ($question->medias as $key => $media) {
                $mediaPath = str_replace('http://localhost:8000/medias/', '', $media->url);
                $mediaPath = public_path('medias') . '/' . $mediaPath;   
                array_push($mediaPaths, $mediaPath);
            }
            File::delete($mediaPaths);
            $question->medias()->delete();

            // xoa images cua question
            $imagePaths = [];
            foreach ($question->images as $key => $image) {
                $imagePath = str_replace('http://localhost:8000/images/uploads', '', $image->url);
                $imagePath = public_path('images/uploads') . '/' . $imagePath;   
                array_push($imagePaths, $imagePath);
            }
            File::delete($imagePaths);
            $question->images()->delete();

            // xoa votes
            $question->votes()->delete();

            // xoa answers
            $answerMediaPaths = [];
            $answerImagePaths = [];
            foreach ($question->answers as $key1 => $answer) {
                // xoa medias cua answers
                foreach ($answer->medias as $key2 => $media) {
                    $answerMediaPath = str_replace('http://localhost:8000/medias/', '', $media->url);
                    $answerMediaPath = public_path('medias') . '/' . $answerMediaPath;   
                    array_push($answerMediaPaths, $answerMediaPath);
                }
                $answer->medias()->delete();

                // xoa images cua answers
                $imagePaths = [];
                foreach ($answer->images as $key3 => $image) {
                    $answerImagePath = str_replace('http://localhost:8000/images/uploads', '', $image->url);
                    $answerImagePath = public_path('images/uploads') . '/' . $answerImagePath;   
                    array_push($answerImagePaths, $answerImagePath);
                }
                $answer->images()->delete();

                // xoa votes cua answers
                $answer->votes()->delete();

                // xoa contents cua answers
                $answer->content()->delete();

                // xoa comments cua answers
                $answer->comments()->delete();

                // xoa conversations cua answers
                $answer->conversation()->delete();
            }
            File::delete($answerMediaPaths);
            File::delete($answerImagePaths);
            
            // xoa content cua question
            $question->content()->delete();

            // xoa question
            $question->forceDelete();

            // reindex ES
            Question::reindex();
        });
        DB::table('notifications')->where('data->question_id', $questionId)->delete();

        return response()->json(['response' => 1]);
    }

    public function edit($questionId)
    {
        $question = Question::with(['tags', 'content', 'medias', 'images'])->where('id', $questionId)->first();
        $tags = implode(",", $question->tags->pluck('tag')->toArray());
        $images = [];
        foreach ($question->images as $image) {
            array_push($images, ['id' => $image->id, 'src' => $image->url]);
        }
        $medias = $question->medias;
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();

        return view('edit_question', compact(['question', 'tags', 'images', 'medias', 'topTags']));
    }
    public function update(Request $request, $questionId) 
    {   
        // dd('hello');
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
        $question = Question::with(['tags', 'content', 'medias', 'images'])->where('id', $questionId)->first();
        DB::transaction(function () use ($request, $question) {
            // save question: title, updated
            $question->update([
                'title' => $request->title,
                'updated' => 1
            ]);
            
            // $question->updateIndex();
            Question::reindex();
            //$question->addToIndex();

            // save tags
            // them cac tags moi vao bang tags
            $tags = explode(",", $request->tags);
            $formattedTags = [];
            foreach ($tags as $tag) {
                $element = [
                    'tag' => $tag
                ];
                array_push($formattedTags, $element);
            }
            DB::table('tags')->insertOrIgnore($formattedTags);
            // lay ids cua tags cua question
            $tagIds = Arr::flatten(Tag::select('id')->whereIn('tag', $tags)->get()->toArray());
            // sync voi bang trung gian
            $question->tags()->sync($tagIds);
            // xoa tags ko co question

            // save content
            $question->content()->update([
                'content' => $request->content,
            ]);

            // save images
            // tat ca old images cua question
            $allImageIds = $question->images()->pluck('id')->toArray();

            // (neu nhu question ko co anh, hoac xoa het anh cu) va ko them anh moi
            if (!$request->oldImageIds && $question->images->count() > 0) {
                $imagePaths = [];
                foreach ($question->images as $key => $image) {
                    $imagePath = str_replace('http://localhost:8000/images/uploads', '', $image->url);
                    $imagePath = public_path('images/uploads') . '/' . $imagePath;   
                    array_push($imagePaths, $imagePath);
                }
                File::delete($imagePaths);
                $question->images()->delete();
            }

            // neu nhu ton tai cac anh cu, va co anh cu nao do bi xoa
            if ($request->oldImageIds && $request->oldImageIds != $allImageIds) {
                // lay images cần xoá
                $oldImageIds = [];
                foreach ($request->oldImageIds as $key => $oldImageId) {
                    if ($key - 1 >= 0 && $request->oldImageIds[$key] < $request->oldImageIds[$key-1]) {
                        break;
                    } else {
                        array_push($oldImageIds, $oldImageId);
                    }
                }
                $deletedImageIds = array_diff($allImageIds, $oldImageIds);

                // xoa images
                $deletedImagePaths = [];
                $deletedImages = Image::whereIn('id', $deletedImageIds)->get();
                foreach ($deletedImages as $deletedImage) {
                    $deletedImagePath = str_replace('http://localhost:8000/images/uploads', '', $deletedImage->url);
                    $deletedImagePath = public_path('images/uploads') . '/' . $deletedImagePath;   
                    array_push($deletedImagePaths, $deletedImagePath);
                }
                File::delete($deletedImagePaths);
                Image::destroy($deletedImageIds);
            }

            // add new images
            // dd($request->photos, $request->imgUrls);
            // dd($request->photos);
            if ($request->photos) {
                // dd($request->photos);
                foreach ($request->photos as $image) {
                    if (in_array($image->getClientOriginalName(), array_filter(explode(",", $request->imgUrls)))) {
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        $whereToSaveImage = public_path('images/uploads');
                        $image->move($whereToSaveImage, $imageName);
                        $url = "http://localhost:8000/images/uploads/$imageName" ;
                        $question->images()->create([
                            'url' => $url
                        ]);
                    }
                }
            } 

            $oldAudioIds = $question->medias()->pluck('id')->toArray();
            if ($oldAudioIds) {
                // update old audios
                // lay ids can xoa
                if (!$request->oldAudioIds) $deletedAudioIds = $oldAudioIds;
                else $deletedAudioIds = array_diff($oldAudioIds, $request->oldAudioIds);

                // xoa audios
                $deletedAudioPaths = [];
                $deletedAudios = Media::whereIn('id', $deletedAudioIds)->get();
                foreach ($deletedAudios as $deletedAudio) {
                    $deletedAudioPath = str_replace('http://localhost:8000/images/uploads', '', $deletedAudio->url);
                    $deletedAudioPath = public_path('images/uploads') . '/' . $deletedAudioPath;   
                    array_push($deletedAudioPaths, $deletedAudioPath);
                }
                File::delete($deletedAudioPaths);
                Media::destroy($deletedAudioIds);
            }

            // add new audios
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

        return response()->json(['response' => 1, 'questionId' => $questionId]);
    }

    public function view()
    {
        $questions = Question::with(['content', 'user', 'answers', 'tags'])->where('status', 1)->orderByDesc('id')->paginate(15);
        $totalQuestions = Question::all()->where('status', 1)->count();
        $totalAnswers = Answer::all()->count();
        $topUsers = User::orderByDesc('points')->take(10)->get();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();

        return view('questions', compact(['questions', 'totalQuestions', 'totalAnswers', 'topUsers', 'topTags']));
    }

    public function searchByTitleAndContent($searchText)
    {
        $titleSearch = Question::complexSearch($this->elasticsearchDSL($searchText, ['title']))->getHits()['hits'];
        $contentSearch = Content::complexSearch($this->elasticsearchDSL($searchText, ['content']))->getHits()['hits'];
        $idsTitle = array_map(function($item){
            return (int) $item['_id'];
        }, $titleSearch);
        $idsContent = array_map(function($item){
            if ($item['_source']['contentable_type'] == "App\Models\Question") return (int) $item['_source']['contentable_id'];
        }, $contentSearch);
        $ids = array_unique(
            array_merge($idsTitle, $idsContent)
        );
        $ids = array_filter($ids);

        return $ids;
    }

    public function viewByTab($searchText, $tab)
    {
        $topUsers = User::orderByDesc('points')->take(10)->get();
        $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();
        if ($searchText == 'noSearching') {
            if ($tab == 'newest') {
                $questions = Question::with(['content', 'user', 'answers'])->where('status', 1)->orderByDesc('id')->paginate(15);
                $totalQuestions = Question::all()->where('status', 1)->count();
            }
            if ($tab == 'unsolved') {
                $questions = Question::with(['content', 'user', 'answers'])->where('status', 1)->where('best_answer_id', null)->orderByDesc('id')->paginate(15);
                $totalQuestions = Question::all()->where('status', 1)->where('best_answer_id', null)->count();
            } 
            if ($tab == 'votes') {
                $questions = Question::with(['content', 'user', 'answers'])->where('status', 1)->orderByDesc('vote_number')->paginate(15);
                $totalQuestions = Question::all()->where('status', 1)->count();
            }
            $totalAnswers = Answer::all()->count();
            // $topUsers = User::orderByDesc('points')->take(10)->get();
            // $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();

            return view('questions', compact(['questions', 'tab', 'totalQuestions', 'totalAnswers', 'topUsers', 'topTags']));
        } else {
            if ($searchText[0] == '[' && $searchText[-1] == ']') {
                if ($tab == 'newest') {
                    $searchTag = substr($searchText, 0, -1);
                    $searchTag = substr($searchTag, 1);
                    $tags = explode(",", $searchTag);
                    $questions = DB::table('questions')
                        ->join('question_tag', 'questions.id', '=', 'question_tag.question_id')
                        ->join('tags', 'tags.id', '=', 'question_tag.tag_id')
                        ->select(DB::raw('questions.id, count(tag_id) as tag_count'))
                        ->where('tag', 'like', '%' . $tags[0] . '%')
                        ->where('status', 1);
                    if (isset($tags[1])) {
                        for ($i=1; $i<count($tags); $i++) {
                            $questions = $questions->orWhere('tag', 'like', '%' . $tags[$i] . '%');
                        }
                        $questions = $questions->groupBy('questions.id')->having('tag_count', '>', 1)->get();
                    } else {
                        $questions = $questions->groupBy('questions.id')->get();
                    }
                    $ids = Arr::flatten($questions->pluck('id'));
                    $questions = Question::whereIn('id', $ids)->orderByDesc('id')->paginate(15);
                    $totalQuestions = Question::whereIn('id', $ids)->count();
                }
                if ($tab == 'unsolved') {
                    $searchTag = substr($searchText, 0, -1);
                    $searchTag = substr($searchTag, 1);
                    $tags = explode(",", $searchTag);
                    $questions = DB::table('questions')
                        ->join('question_tag', 'questions.id', '=', 'question_tag.question_id')
                        ->join('tags', 'tags.id', '=', 'question_tag.tag_id')
                        ->select(DB::raw('questions.id, count(tag_id) as tag_count'))
                        ->where('tag', 'like', '%' . $tags[0] . '%')
                        ->where('status', 1);
                    if (isset($tags[1])) {
                        for ($i=1; $i<count($tags); $i++) {
                            $questions = $questions->orWhere('tag', 'like', '%' . $tags[$i] . '%');
                        }
                        $questions = $questions->groupBy('questions.id')->having('tag_count', '>', 1)->get();
                    } else {
                        $questions = $questions->groupBy('questions.id')->get();
                    };
                    $ids = Arr::flatten($questions->pluck('id'));
                    $questions = Question::whereIn('id', $ids)->where('best_answer_id', null)->orderByDesc('id')->paginate(15);
                    $totalQuestions = Question::whereIn('id', $ids)->where('best_answer_id', null)->count();
                } 
                if ($tab == 'votes') {
                    $searchTag = substr($searchText, 0, -1);
                    $searchTag = substr($searchTag, 1);
                    $tags = explode(",", $searchTag);
                    $questions = DB::table('questions')
                        ->join('question_tag', 'questions.id', '=', 'question_tag.question_id')
                        ->join('tags', 'tags.id', '=', 'question_tag.tag_id')
                        ->select(DB::raw('questions.id, count(tag_id) as tag_count'))
                        ->where('tag', 'like', '%' . $tags[0] . '%')
                        ->where('status', 1);
                    if (isset($tags[1])) {
                        for ($i=1; $i<count($tags); $i++) {
                            $questions = $questions->orWhere('tag', 'like', '%' . $tags[$i] . '%');
                        }
                        $questions = $questions->groupBy('questions.id')->having('tag_count', '>', 1)->get();
                    } else {
                        $questions = $questions->groupBy('questions.id')->get();
                    };
                    $ids = Arr::flatten($questions->pluck('id'));
                    $questions = Question::whereIn('id', $ids)->orderByDesc('vote_number')->paginate(15);
                    $totalQuestions = Question::whereIn('id', $ids)->count();
                }
            } else {
                if ($tab == 'relevance') {
                    $ids = $this->searchByTitleAndContent($searchText);
                    $questions = Question::whereIn('id', $ids)->orderByRaw(DB::raw(sprintf('FIELD(id, %s)', implode(',', $ids))))->paginate(15);
                    $totalQuestions = Question::whereIn('id', $ids)->count();
                }
                if ($tab == 'newest') {
                    $ids = $this->searchByTitleAndContent($searchText);
                    $questions = Question::whereIn('id', $ids)->orderByDesc('id')->paginate(15);
                    $totalQuestions = Question::whereIn('id', $ids)->count();
                }
                if ($tab == 'unsolved') {
                    $ids = $this->searchByTitleAndContent($searchText);
                    $questions = Question::whereIn('id', $ids)->where('best_answer_id', null)->orderByDesc('id')->paginate(15);
                    $totalQuestions = Question::whereIn('id', $ids)->where('best_answer_id', null)->count();
                } 
                if ($tab == 'votes') {
                    $ids = $this->searchByTitleAndContent($searchText);
                    $questions = Question::whereIn('id', $ids)->orderByDesc('vote_number')->paginate(15);
                    $totalQuestions = Question::whereIn('id', $ids)->count();
                }
            }
            $totalAnswers = Answer::whereIn('question_id', $ids)->count();
            // $topUsers = User::orderByDesc('points')->take(10)->get();
            // $topTags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();

            return view('questions', compact(['questions', 'tab', 'searchText', 'totalQuestions', 'totalAnswers', 'topUsers', 'topTags']));
        }
    }

    public function goToBestAns($questionId) {
        $question = Question::find($questionId);
        $bestAnsId = $question->best_answer_id;
        $page = (int) ceil($question->answers->where('id', '<=', $bestAnsId)->count() / 5);

        return response()->json(['bestAnsId' => $bestAnsId, 'page' => $page]);
    }
}
