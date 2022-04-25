<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Media;
use App\Models\Image;
use App\Models\Content;
use App\Models\Question;
use App\Models\Vote;
use App\Models\Comment;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;
    
    protected $fillable = [ 
        'user_id',
        'question_id',
        'vote_number'
    ];

    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function content()
    {
        return $this->morphOne(Content::class, 'contentable');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
