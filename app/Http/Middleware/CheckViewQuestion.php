<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Session;

class CheckViewQuestion
{
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    private function getViewedQuestions()
    {
        return $this->session->get('viewed_questions', null);
    }

    private function cleanExpiredViews($questions)
    {
        $time = time();

        // Let the views expire after one hour.
        $throttleTime = 3600;

        return array_filter($questions, function ($timestamp) use ($time, $throttleTime)
        {
            return ($timestamp + $throttleTime) > $time;
        });
    }

    private function storeQuestions($questions)
    {
        $this->session->put('viewed_questions', $questions);
    }

    public function handle($request, Closure $next)
    {
        $questions = $this->getViewedQuestions();

        if (!is_null($questions))
        {
            $questions = $this->cleanExpiredViews($questions);
            $this->storeQuestions($questions);
        }

        return $next($request);
    }
}
