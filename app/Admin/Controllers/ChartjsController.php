<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Callout;
use App\Models\User;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Vote;
use App\Models\Comment;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Encore\Admin\Admin;
use Encore\Admin\Widgets\InfoBox;

Admin::style('.main-footer strong {display: none} .main-footer .pull-right {display: none}');

class ChartjsController extends Controller
{
    public function index(Content $content)
    {   
        // tags chart
        $tags = Tag::withCount('questions')->get()->toArray();
        $tagTotal = count($tags);
        array_multisort(array_column($tags, 'questions_count'), SORT_DESC, $tags);
        // tag labels
        $tagNames = array_column($tags, 'tag');
        $tagLabels = array_slice($tagNames, 0, 7);
        array_push($tagLabels, 'Other Tags');
        // tag data
        $tagCount = array_column($tags, 'questions_count');
        $tagData = array_slice($tagCount, 0, 7);
        array_push($tagData, $tagTotal - array_sum($tagData));

        // user chart 
        $erUsers = User::all();
        $erTotalUsers = [];
        $erTotalUsers[0] = $erUsers->where('created_at', '<=', Carbon::today()->subDays(6)->toDateString())->count();
        $erTotalUsers[1] = $erUsers->where('created_at', '<=', Carbon::today()->subDays(5)->toDateString())->count();
        $erTotalUsers[2] = $erUsers->where('created_at', '<=', Carbon::today()->subDays(4)->toDateString())->count();
        $erTotalUsers[3] = $erUsers->where('created_at', '<=', Carbon::today()->subDays(3)->toDateString())->count();
        $erTotalUsers[4] = $erUsers->where('created_at', '<=', Carbon::today()->subDays(2)->toDateString())->count();
        $erTotalUsers[5] = $erUsers->where('created_at', '<=', Carbon::today()->subDays(1)->toDateString())->count();
        $erTotalUsers[6] = $erUsers->count();

        $userLabels = [
            Carbon::today()->subDays(6)->format('d/m'),
            Carbon::today()->subDays(5)->format('d/m'),
            Carbon::today()->subDays(4)->format('d/m'),
            Carbon::today()->subDays(3)->format('d/m'),
            Carbon::today()->subDays(2)->format('d/m'),
            Carbon::today()->subDays(1)->format('d/m'),
            Carbon::today()->format('d/m'),
        ];

        // Question Engagement Chart
        // ER Users
        // ER Question Votes
        $erVotes = Vote::where('voteable_type', 'App\Models\Question')->get();
        $erTotalVotes = [];
        $erTotalVotes[0] = $erVotes->where('created_at', '<=', Carbon::today()->subDays(6)->toDateString())->count();
        $erTotalVotes[1] = $erVotes->where('created_at', '<=', Carbon::today()->subDays(5)->toDateString())->count();
        $erTotalVotes[2] = $erVotes->where('created_at', '<=', Carbon::today()->subDays(4)->toDateString())->count();
        $erTotalVotes[3] = $erVotes->where('created_at', '<=', Carbon::today()->subDays(3)->toDateString())->count();
        $erTotalVotes[4] = $erVotes->where('created_at', '<=', Carbon::today()->subDays(2)->toDateString())->count();
        $erTotalVotes[5] = $erVotes->where('created_at', '<=', Carbon::today()->subDays(1)->toDateString())->count();
        $erTotalVotes[6] = $erVotes->count();
        // ER Comments
        $erComments = Comment::all();
        $erTotalComments = [];
        $erTotalComments[0] = $erComments->where('created_at', '<=', Carbon::today()->subDays(6)->toDateString())->count();
        $erTotalComments[1] = $erComments->where('created_at', '<=', Carbon::today()->subDays(5)->toDateString())->count();
        $erTotalComments[2] = $erComments->where('created_at', '<=', Carbon::today()->subDays(4)->toDateString())->count();
        $erTotalComments[3] = $erComments->where('created_at', '<=', Carbon::today()->subDays(3)->toDateString())->count();
        $erTotalComments[4] = $erComments->where('created_at', '<=', Carbon::today()->subDays(2)->toDateString())->count();
        $erTotalComments[5] = $erComments->where('created_at', '<=', Carbon::today()->subDays(1)->toDateString())->count();
        $erTotalComments[6] = $erComments->count();
        // ER Answers
        $erAnswers = Answer::all();
        $erTotalAnswers = [];
        $erTotalAnswers[0] = $erAnswers->where('created_at', '<=', Carbon::today()->subDays(6)->toDateString())->count();
        $erTotalAnswers[1] = $erAnswers->where('created_at', '<=', Carbon::today()->subDays(5)->toDateString())->count();
        $erTotalAnswers[2] = $erAnswers->where('created_at', '<=', Carbon::today()->subDays(4)->toDateString())->count();
        $erTotalAnswers[3] = $erAnswers->where('created_at', '<=', Carbon::today()->subDays(3)->toDateString())->count();
        $erTotalAnswers[4] = $erAnswers->where('created_at', '<=', Carbon::today()->subDays(2)->toDateString())->count();
        $erTotalAnswers[5] = $erAnswers->where('created_at', '<=', Carbon::today()->subDays(1)->toDateString())->count();
        $erTotalAnswers[6] = $erAnswers->count();
        // ER Answers
        $erQuestions = Question::all();
        $erTotalQuestions = [];
        $erTotalQuestions[0] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(6)->toDateString())->count();
        $erTotalQuestions[1] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(5)->toDateString())->count();
        $erTotalQuestions[2] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(4)->toDateString())->count();
        $erTotalQuestions[3] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(3)->toDateString())->count();
        $erTotalQuestions[4] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(2)->toDateString())->count();
        $erTotalQuestions[5] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(1)->toDateString())->count();
        $erTotalQuestions[6] = $erQuestions->count();
        // ER
        $erData = [];
        for ($i = 0; $i < 7; $i++) {
            $erData[$i] = ($erTotalVotes[$i] + $erTotalComments[$i] + $erTotalAnswers[$i]) / ($erTotalQuestions[$i] * $erTotalUsers[$i]) * 100;
            $erData[$i] = number_format($erData[$i], 2, '.', '');
        };

        // questions chart
        $tongTonDong = [];
        $tongCauHoiGiaiQuyetTrongNgay = [];
        $tongTonDong[0] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(6)->toDateString())->where('best_answer_id', '==', null)->count();
        $tongCauHoiGiaiQuyetTrongNgay[0] = $erQuestions->whereBetween('solved_at', [Carbon::today()->subDays(6)->toDateString(), Carbon::today()->subDays(5)->toDateString()])->count();
        $tongTonDong[1] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(5)->toDateString())->where('best_answer_id', '==', null)->count();
        $tongCauHoiGiaiQuyetTrongNgay[1] = $erQuestions->whereBetween('solved_at', [Carbon::today()->subDays(5)->toDateString(), Carbon::today()->subDays(4)->toDateString()])->count();
        $tongTonDong[2] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(4)->toDateString())->where('best_answer_id', '==', null)->count();
        $tongCauHoiGiaiQuyetTrongNgay[2] = $erQuestions->whereBetween('solved_at', [Carbon::today()->subDays(4)->toDateString(), Carbon::today()->subDays(3)->toDateString()])->count();
        $tongTonDong[3] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(3)->toDateString())->where('best_answer_id', '==', null)->count();
        $tongCauHoiGiaiQuyetTrongNgay[3] = $erQuestions->whereBetween('solved_at', [Carbon::today()->subDays(3)->toDateString(), Carbon::today()->subDays(2)->toDateString()])->count();
        $tongTonDong[4] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(2)->toDateString())->where('best_answer_id', '==', null)->count();
        $tongCauHoiGiaiQuyetTrongNgay[4] = $erQuestions->whereBetween('solved_at', [Carbon::today()->subDays(2)->toDateString(), Carbon::today()->subDays(1)->toDateString()])->count();
        $tongTonDong[5] = $erQuestions->where('created_at', '<=', Carbon::today()->subDays(1)->toDateString())->where('best_answer_id', '==', null)->count();
        $tongCauHoiGiaiQuyetTrongNgay[5] = $erQuestions->whereBetween('solved_at', [Carbon::today()->subDays(1)->toDateString(), Carbon::today()->toDateString()])->count();
        $tongTonDong[6] = $erQuestions->where('best_answer_id', '==', null)->count();
        $tongCauHoiGiaiQuyetTrongNgay[6] = $erQuestions->whereBetween('solved_at', [Carbon::today()->toDateString(), Carbon::today()->addDay(1)->toDateString()])->count();
        
        // infoBox
        // $userBox = new InfoBox('Total Users', 'users', 'aqua', '/admin/manage/users', User::all()->count());
        $userBox = new InfoBox('Users', 'users', 'aqua', '/admin/manage/users', 220);
        // $questionBox = new InfoBox('Total Questions', 'question-circle', 'green', '/admin/manage/questions', Question::all()->count());
        $questionBox = new InfoBox('Questions', 'question-circle', 'green', '/admin/manage/questions', 175);
        $tagBox = new InfoBox('Tags', 'tags', 'yellow', '/admin/manage/tags', Tag::all()->count());
        // $answerBox = new InfoBox('Total Answers', 'reply-all', 'red', '/admin/manage/answers', Answer::all()->count());
        $answerBox = new InfoBox('Answers', 'reply-all', 'red', '/admin/manage/answers', 67);
        // $commentBox = new InfoBox('Total Comments', 'comments', 'pink', '/admin/manage/comments', Comment::all()->count());
        $commentBox = new InfoBox('Comments', 'comments', 'teal', '/admin/manage/comments', 127);

        return $content
            ->header('Dashboard')
            ->body(
                new Box('', view('admin.chartjs', [
                'userLabels' => $userLabels, 
                'userData' => $erTotalUsers,
                'tagLabels' => $tagLabels,
                'tagData' => $tagData,
                'erData' => $erData,
                'tongTonDong' => $tongTonDong,
                'tongCauHoiGiaiQuyetTrongNgay' => $tongCauHoiGiaiQuyetTrongNgay,
                'userBox' => $userBox,
                'questionBox' => $questionBox,
                'tagBox' => $tagBox,
                'answerBox' => $answerBox,
                'commentBox' => $commentBox,
            ])));
    }
}