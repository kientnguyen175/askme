<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = [ 
        'model_id',
    ];

    public function followable()
    {
        return $this->morphTo();
    }
}
