<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\NewAnswerToFollowers;
use App\Notifications\UpdateAnswerNoti;
use App\Notifications\NewPrivateCommentNoti;
use Illuminate\Support\Facades\File; 
use App\Models\Image;
use App\Models\Media;
use Illuminate\Support\Facades\Redirect;

class AnswerController extends Controller
{
    public function show($answerId) {
        $answer = Answer::find($answerId);
        $question = $answer->question;
        $page = (int) ceil($question->answers->where('id', '<=', $answerId)->count() / 5);

        return Redirect::to('http://localhost:8000/questions/' . $question->id . '?page=' . $page . '#answer-' . $answerId);
    }

    public function store(Request $request, $questionId)
    {
        if (!Auth::check()) {
            return response()->json(['response' => -1]); 
        }
        $this->validate($request, 
            [
                'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
                'medias.*' => 'mimetypes:audio/mpeg,video/webm,audio/ogg|max:3072'
            ]
	    ); 
        if (!$request->content) {
            return response()->json(['response' => 0]); 
        } 
        $user = Auth::user();
        $answer = null;
        $imageURLs = [];
        $mediaURLs = [];
        DB::transaction(function () use ($request, $questionId, &$answer, $user, &$imageURLs, &$mediaURLs) {
            // save answer
            $answer = Answer::create([
                'user_id' => $user->id,
                'question_id' => $questionId
            ]);
            // save content
            $answer->content()->create([
                'content' => $request->content,
                'updated' => 0
            ]);
            // save conversation
            $answer->conversation()->create([
                'conversation' => $request->conversation
            ]);
            // save images
            if ($request->images) {
                foreach ($request->images as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $whereToSaveImage = public_path('images/uploads');
                    $image->move($whereToSaveImage, $imageName);
                    $url = "http://localhost:8000/images/uploads/$imageName" ;
                    $answer->images()->create([
                        'url' => $url
                    ]);
                    array_push($imageURLs, $url);
                }
            } 
            // save medias
            if ($request->medias) {
                foreach ($request->medias as $media) {
                    $mediaName = time() . '_' . $media->getClientOriginalName();
                    $whereToSaveMedia = public_path('medias');
                    $media->move($whereToSaveMedia, $mediaName);
                    $url = "http://localhost:8000/medias/$mediaName" ;
                    $answer->medias()->create([
                        'url' => $url
                    ]);
                    array_push($mediaURLs, $url);
                }
            } 
        });
        $responseData = [
            'response' => 1, 
            'newAnswerId' => $answer->id,
            'questionId' => $questionId,
            'answerUserAvatar' => $user->avatar,
            'answerUserName' => $user->name,
            'answerUserId' => $user->id,
            'answerConversation' => $answer->conversation->conversation,
            'answerContent' => $answer->content->content,
            'time' => $answer->created_at->diffForHumans(Carbon::now()),
            'keyCkeditor' => $answer->id,
            'imageURLs' => $imageURLs,
            'mediaURLs' => $mediaURLs,
            'newAnswerPage' => (int) ceil(Question::find($questionId)->answers()->count() / 5)
        ];
        // notify to followers cua cau hoi
        $question = Question::find($questionId);
        $followers = $question->follows;
        foreach ($followers as $follower) {
            if ($follower->model_id != Auth::id()) {
                User::find($follower->model_id)->notify(new NewAnswerToFollowers([
                    'answer_id' => $answer->id,
                    'question_id' => $questionId,
                    'answer_user_avatar' => $user->avatar ?? asset('images/default_avatar.png'),
                    'question_title' => $question->title,
                    'answer_user_name' => $user->name,
                    'newAnswerPage' => (int) ceil(Question::find($questionId)->answers()->count() / 5),
                    'follower_id' => $follower->model_id
                ]));
            }
        }
        // notify to author 
        $author = $question->user;
        if (Auth::id() != $author->id) {
            $author->notify(new NewAnswerToFollowers([
                'answer_id' => $answer->id,
                'question_id' => $questionId,
                'answer_user_avatar' => $user->avatar ?? asset('images/default_avatar.png'),
                'question_title' => $question->title,
                'answer_user_name' => $user->name,
                'newAnswerPage' => (int) ceil(Question::find($questionId)->answers()->count() / 5),
                'author_id' => $author->id
            ]));
        }
        
        return response()->json($responseData);
    }

    public function vote($id)
    {
        $answer = Answer::with('votes')->where('id', $id)->first();
        $userId = Auth::id();
        $votedCheck = $answer->votes->where('user_id', $userId)->first();
        if (!$votedCheck) {
            DB::transaction(function () use ($answer, $userId) {
                $answer->update([
                    'vote_number' => ++$answer->vote_number
                ]);
                $answer->votes()->create([
                    'user_id' => $userId
                ]);
                $answer->user()->update([
                    'points' => ++$answer->user->points
                ]);
            });

            return response()->json(['response' => 1]);
        } else {
            DB::transaction(function () use ($answer, $userId) {
                $answer->update([
                    'vote_number' => --$answer->vote_number
                ]);
                $answer->votes()->where('user_id', $userId)->delete();
                $answer->user()->update([
                    'points' => --$answer->user->points
                ]);
            });
            
            return response()->json(['response' => 0]);
        }    
    }

    public function updateConversation(Request $request, $answerId)
    {
        $conversation = Conversation::where('answer_id', $answerId)->first();
        if ($conversation->conversation == $request->oldConversation) {
            $conversation->update([
                'conversation' => $request->conversation
            ]);
            if (isset($request->addComment)) {
                $answer = Answer::with(['question.user', 'user'])->where('id', $answerId)->first();
                $answerUserId = $answer->user->id;
                $questionUserId = $answer->question->user->id;
                // nguoi add la chu cau hoi, gui thong bao toi chu cau tra loi
                if ($answerUserId != $questionUserId && Auth::id() == $questionUserId) {
                    User::find($answerUserId)->notify(new NewPrivateCommentNoti([
                        'question_user_avatar' => $answer->question->user->avatar ?? asset('images/default_avatar.png'),
                        'question_user_name' => $answer->question->user->name,
                        'question_title' => $answer->question->title,
                        'answer_user_id' => $answerUserId,
                        'answer_id' => $answerId,
                        'question_id' => $answer->question->id,
                        'page' => (int) ceil(Question::find($answer->question->id)->answers->where('id', '<=', $answerId)->count() / 5)
                    ]));
                } else {
                    // nguoi add la chu cau tra loi, gui thong bao toi chu cau hoi
                    User::find($questionUserId)->notify(new NewPrivateCommentNoti([
                        'answer_user_avatar' => $answer->user->avatar ?? asset('images/default_avatar.png'),
                        'answer_user_name' => $answer->user->name,
                        'question_title' => $answer->question->title,
                        'question_user_id' => $questionUserId,
                        'answer_id' => $answerId,
                        'question_id' => $answer->question->id,
                        'page' => (int) ceil(Question::find($answer->question->id)->answers->where('id', '<=', $answerId)->count() / 5)
                    ]));
                }
            }
            return response()->json(['response' => 1]);
        } else {
            response()->json(['response' => 0]);
        }
        
    }

    public function deleteConversationThread(Request $request, $answerId)
    {
        $answer = Answer::find($answerId);
        if ($request->conversation != '[]') {
            DB::transaction(function () use ($answer, $request) {
                $answer->content->update([
                    'content' => $request->answerContent
                ]);
                $answer->conversation->update([
                    'conversation' => $request->conversation
                ]);
            });

            return response()->json(['response' => 1]);
        } else {
            DB::transaction(function () use ($answer, $request) {
                $answer->content->update([
                    'content' => $request->answerContent
                ]);
                $answer->conversation->delete();
            });

            return response()->json(['response' => 0]);
        }
    }

    public function edit($answerId)
    {
        $answer = Answer::with(['content', 'medias', 'images', 'conversation', 'question'])->where('id', $answerId)->first();
        $author = $answer->question->user;
        $images = [];
        foreach ($answer->images as $image) {
            array_push($images, ['id' => $image->id, 'src' => $image->url]);
        }
        $medias = $answer->medias;
        $conversation = $answer->conversation->conversation ?? '';
        $questionId = $answer->question->id;

        return view('edit_answer', compact(['answer', 'images', 'medias', 'author', 'conversation', 'questionId']));
    }

    public function update($answerId, Request $request) {
        $this->validate($request, 
            [
                'photos.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
                'audios.*' => 'mimetypes:audio/mpeg,video/webm,audio/ogg|max:3072'
            ]
        ); 
        if (!$request->content) {
            return response()->json(['response' => 0]); 
        } 
        $answer = Answer::with(['content', 'medias', 'images', 'user'])->where('id', $answerId)->first();
        DB::transaction(function () use ($request, $answer) {
            // save question: updated
            $answer->update([
                'updated' => 1
            ]);

            // save content
            $answer->content()->update([
                'content' => $request->content,
            ]);

            // save conversation
            if ($request->conversation == '[]') {
                $answer->conversation()->delete();
            } else {
                $answer->conversation()->update([
                    'conversation' => $request->conversation
                ]);
            }
            // save images
            // tat ca old images cua question
            $allImageIds = $answer->images()->pluck('id')->toArray();

            // (neu nhu question ko co anh, hoac xoa het anh cu) va ko them anh moi
            if (!$request->oldImageIds && $answer->images->count() > 0) {
                $imagePaths = [];
                foreach ($answer->images as $key => $image) {
                    $imagePath = str_replace('http://localhost:8000/images/uploads', '', $image->url);
                    $imagePath = public_path('images/uploads') . '/' . $imagePath;   
                    array_push($imagePaths, $imagePath);
                }
                File::delete($imagePaths);
                $answer->images()->delete();
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
                        $answer->images()->create([
                            'url' => $url
                        ]);
                    }
                }
            } 

            $oldAudioIds = $answer->medias()->pluck('id')->toArray();
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
                    $answer->medias()->create([
                        'url' => $url
                    ]);
                }
            } 
        });
        $page = (int) ceil(Question::find($request->questionId)->answers->where('id', '<=', $answerId)->count() / 5);

        // notify to followers cua cau hoi
        $question = Question::find($request->questionId);
        $followers = $question->follows;
        foreach ($followers as $follower) {
            if ($follower->model_id != Auth::id()) {
                User::find($follower->model_id)->notify(new UpdateAnswerNoti([
                    'answer_id' => $answer->id,
                    'question_id' => $request->questionId,
                    'answer_user_avatar' => $answer->user->avatar ?? asset('images/default_avatar.png'),
                    'question_title' => $question->title,
                    'answer_user_name' => $answer->user->name,
                    'newAnswerPage' => $page,
                    'follower_id' => $follower->model_id
                ]));
            }
        }
        // notify to author 
        $author = $question->user;
        // neu nguoi cap nhat answer ko phai author cua question
        if (Auth::id() != $author->id) {
            $author->notify(new UpdateAnswerNoti([
                'answer_id' => $answer->id,
                'question_id' => $request->questionId,
                'answer_user_avatar' => $answer->user->avatar ?? asset('images/default_avatar.png'),
                'question_title' => $question->title,
                'answer_user_name' => $answer->user->name,
                'newAnswerPage' => $page,
                'author_id' => $author->id
            ]));
        }

        return response()->json(['response' => 1, 'answerId' => $answerId, 'page' => $page]);
    }

    public function destroy($answerId) 
    {
        $answer = Answer::find($answerId);
        $answer->content()->delete();
        $answer->delete();
        $answer->comments()->delete();
        if ($answer->question->best_answer_id == $answerId) {
            $answer->question()->update([
                'best_answer_id' => null
            ]);

            return response()->json(['best_answer_id' => 0]);
        }

        return response()->json(['best_answer_id' => 1]);
    }

    public function readUpdateAnswerNoti($notiId, $questionId, $answerId)
    {
        auth()->user()->notifications()->find($notiId)->markAsRead();
        $page = (int) ceil(Question::find($questionId)->answers->where('id', '<=', $answerId)->count() / 5);

        return Redirect::to('http://localhost:8000/questions/' . $questionId . '?page=' . $page . '#answer-' . $answerId);
    }
}
