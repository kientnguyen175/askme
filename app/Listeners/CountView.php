<?php

namespace App\Listeners;

use App\Events\ShowQuestion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Session\Store;

class CountView
{
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    private function isQuestionViewed($question)
	{
	    $viewed = $this->session->get('viewed_questions', []);

	    return array_key_exists($question->id, $viewed);
	}

	private function storeQuestion($question)
	{
	    $key = 'viewed_questions.' . $question->id;

	    $this->session->put($key, time());
	}
    
    public function handle(ShowQuestion $event)
    {
        if (!$this->isQuestionViewed($event->question))
	    {
	        $event->question->increment('view_number');
	        $this->storeQuestion($event->question);
	    }
        
    }
}
