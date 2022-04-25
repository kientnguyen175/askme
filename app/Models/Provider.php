<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Provider extends Model
{
    protected $fillable = [ 
        'provider' 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
