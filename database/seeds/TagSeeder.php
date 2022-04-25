<?php

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    
    public function run()
    {
        factory(Tag::class, 100)->create();
    }
}
