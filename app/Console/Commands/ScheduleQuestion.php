<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\PublishQuestion;
use App\Notifications\NewQuestionToFollowers;
use Pusher\Pusher;

class ScheduleQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:question';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule to post question';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scheduleQuestions = Question::with('user')->where('schedule_time', '<=', Carbon::now())
        ->where('status', 0)
        ->get();
        $scheduleQuestions->each->update([
            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        foreach ($scheduleQuestions as $scheduleQuestion) {
            // add to index ES
            $scheduleQuestion->addToIndex();
            //
            $user = User::find($scheduleQuestion->user_id);
            $data = [
                'question_id' => $scheduleQuestion->id,
                'question_title' => $scheduleQuestion->title,
                'user_id' => $scheduleQuestion->user_id,
                'user_avatar' => $user->avatar ?? asset('images/default_avatar.png'),
            ];
            $scheduleQuestion->user->notify(new PublishQuestion($data)); //tao du lieu trong db
            // tìm những người theo dõi chủ của câu hỏi này
            $followers = $user->follows;
            // tạo thông báo tới các followers
            foreach ($followers as $follower) {
                $followerData = [
                    'follower_id' => $follower->model_id,
                    'user_avatar' => $user->avatar ?? asset('images/default_avatar.png'),
                    'user_name' => $user->name,
                    'question_id' => $scheduleQuestion->id,
                ];
                User::find($follower->model_id)->notify(new NewQuestionToFollowers($followerData));
            }
        }
    }
}
