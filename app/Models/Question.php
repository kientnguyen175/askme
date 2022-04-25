<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tag;
use App\Models\Type;
use App\Models\Media;
use App\Models\Image;
use App\Models\Content;
use App\Models\Vote;
use App\Models\Answer;
use App\Models\Collection;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Follow;

class Question extends Model
{
    use ElasticquentTrait;
    use SoftDeletes;

    protected $fillable = [ 
        'user_id',
        'title',
        'view_number',
        'best_answer_id',
        'vote_number',
        'updated',
        'schedule_time',
        'status',
        'solved_at',
        'created_at'
    ];

    protected $mappingProperties = [
        'title' => [
            'type' => 'text',
            "analyzer" => "classic",
        ],
    ];

    function getIndexName() {
        return 'forum';
    } 

    function getTypeName() {
        return 'question';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

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

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }

    public function follows()
    {
        return $this->morphMany(Follow::class, 'followable');
    }
}
