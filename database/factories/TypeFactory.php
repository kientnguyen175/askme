<?php

use App\Models\Type;
use Faker\Generator as Faker;

$factory->define(Type::class, function (Faker $faker) {
    return [
        'id' => 1,
        'name' => 'normal'
    ];
});
