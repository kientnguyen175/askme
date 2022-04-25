<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Answer;

class Conversation extends Model
{
    protected $fillable = [ 
        'conversation',
        'answer_id'
    ];

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
