<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;

class BatchRestore extends BatchAction
{
    public $name = 'Restore';

    public function handle (Collection $collection)
    {
        $collection->each->restore();

        return $this->response()->success('Recovered')->refresh();
    }

    public function dialog ()
    {
        $this->confirm('Are you sure you want to resume?');
    }
}
