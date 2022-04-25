<?php

namespace App\Models;
use Elasticquent\ElasticquentTrait;


use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use ElasticquentTrait;

    protected $fillable = [
        'content',
        'updated'
    ];

    protected $mappingProperties = [
        'title' => [
            'type' => 'text',
            "analyzer" => "classic",
        ]
    ];

    function getIndexName() {
        return 'forum1';
    }

    function getTypeName() {
        return 'content';
    }

    public function contentable()
    {
        return $this->morphTo();
    }
}
