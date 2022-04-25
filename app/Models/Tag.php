<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = [ 
        'tag' 
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }
}
