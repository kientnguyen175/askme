<?php

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    public function run()
    {
        factory(Type::class, 1)->create();
    }
}
