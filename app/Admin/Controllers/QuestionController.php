<?php

namespace App\Admin\Controllers;

use App\Models\Question;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;
use App\Admin\Actions\Post\BatchRestore;
Use Encore\Admin\Admin;

Admin::style('.box-title {font-weight: bold; color: #3B8DBC} .main-footer strong {display: none} .main-footer .pull-right {display: none} .mejs__audio {float: left} th {vertical-align: top !important}');

class QuestionController extends AdminController
{
    protected $title = 'Question';

    protected function grid()
    {
        $grid = new Grid(new Question());
       
        $grid->column('id', __('Question Id'))->display(function ($id) {
            return "<a style='color: white' href='http://localhost:8000/admin/manage/questions/{$id}'><span class='label label-success'>{$id}</span></a>";
        })->filter()->sortable();
        $grid->column('title', __('Title'))->filter('like')->sortable();
        $grid->column('view_number', __('Views'))->filter()->sortable();
        // $grid->column('best_answer_id', __('Best answer id'));
        $grid->column('vote_number', __('Votes'))->filter()->sortable();
        $grid->column('created_at', __('Created At'))->display(function ($created_at) {
            return Carbon::parse($created_at)->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        })->filter('date')->sortable();
        // $grid->column('updated_at', __('Updated at'));
        // $grid->column('updated', __('Updated'));
        // $grid->column('schedule_time', __('Schedule time'));
        $grid->column('solved_at', __('Solved At'))->display(function ($solved_at) {
            if ($solved_at) {
                return Carbon::parse($solved_at)->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
            } else {
                return null;
            }
        })->filter('date')->sortable();
        $grid->column('status', __('Status'))->display(function ($status) {
            if ($status == 0) return 'Pending';
            else return 'Published';
        })->filter('like')->sortable();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            if (\request('_scope_') == 'trashed') {
                $actions->disableDelete();
            }
        });
        $grid->filter(function($filter) {
            $filter->disableIdFilter();
        });
        $grid->disableCreateButton();
        $grid->filter(function($filter) {
            $filter->scope('trashed', 'Recycle Bin')->onlyTrashed();
            $filter->disableIdFilter();
        });
        if (\request('_scope_') == 'trashed') {
            $grid->disableActions();
        }
        $grid->batchActions (function($batch) {
            if (\request('_scope_') == 'trashed') {
                $batch->add(new BatchRestore());
                
            }
        });
        $grid->export(function ($export) {

            $export->column('id', function ($value, $original) {
                return html_entity_decode(strip_tags($original));
            });
        });
        // $grid->column('id')->totalRow();

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Question::findOrFail($id));
        $show->field('id', __('Question Id'));
        $show->field('user_id', __('Author Id'));
        // $show->field('type_id', __('Type id'));
        $show->field('title', __('Title'));
        $show->field('content.content', __('Content'))->unescape();
        $show->tags('Question\'s Tags', function ($tags) {
            $tags->column('id', __('Tag Id'))->display(function ($id) {
                return "<a style='color: white' href='http://localhost:8000/admin/manage/tags/{$id}'><span class='label label-success'>{$id}</span></a>";
            })->sortable();
            $tags->column('tag', __('Tag'));
            $tags->disableActions();
            $tags->disableFilter();
            // $images->disableRowSelector();
            $tags->disableColumnSelector();
            $tags->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $tags->disableCreateButton();
            $tags->disableExport();
        });
        $show->images('Question\'s Images', function ($images) {
            $images->column('id', __('Image Id'))->sortable();
            $images->column('url', __('Image'))->image();
            $images->filter(function($filter) {
                $filter->disableIdFilter();
            });
            $images->disableActions();
            $images->disableFilter();
            // $images->disableRowSelector();
            $images->disableColumnSelector();
            $images->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $images->disableCreateButton();
            $images->disableExport();
        });
        $show->medias('Question\'s Audios', function ($medias) {
            $medias->column('id', __('Audio Id'))->sortable();
            $medias->column('url', __('Audio'))->audio();
            $medias->disableCreateButton();
            $medias->disableExport();
            $medias->disableActions();
            $medias->disableFilter();
            // $medias->disableRowSelector();
            $medias->disableColumnSelector();
            $medias->filter(function($filter) {
                $filter->disableIdFilter();
            });
            $medias->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
        });
        $show->answers('Question\'s Answers', function ($answers) {
            $answers->column('id', __('Answer Id'))->display(function ($id) {
                return "<a style='color: white' href='http://localhost:8000/admin/manage/answers/{$id}'><span class='label label-success'>{$id}</span></a>";
            })->sortable();
            $answers->column('content.content', __('Content'))->display(function ($content) {
                $content = preg_replace('/<comment-start[^>]*>/', '', $content);
                $content = preg_replace('/<\/?comment-start[^>]*>/', '', $content);
                $content = preg_replace('/<comment-end[^>]*>/', '', $content);
                $content = preg_replace('/<\/?comment-end[^>]*>/', '', $content);

                return $content;
            });
            $answers->disableActions();
            $answers->disableFilter();
            // $images->disableRowSelector();
            $answers->disableColumnSelector();
            $answers->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $answers->disableCreateButton();
            $answers->disableExport();
        });
        $show->field('view_number', __('Views'));
        // $show->field('best_answer_id', __('Best answer id'));
        $show->field('vote_number', __('Votes'));
        $show->field('created_at', __('Created At'))->as(function ($created_at) {
            return Carbon::parse($created_at)->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        });;
        $show->field('schedule_time', __('Schedule Time'))->as(function ($schedule_time) {
            if ($schedule_time == null) return 'NULL';
            else return $schedule_time;
        });;
        $show->field('status', __('Status'))->as(function ($status) {
            if ($status == 0) return 'Pending';
            else return 'Published';
        });
        $show->field('solved_at', __('Solved At'))->as(function ($solved_at) {
            if ($solved_at == null) return 'NULL';
            else return Carbon::parse($solved_at)->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        });
        $show->field('best_answer_id', __('Best Answer Id'))->as(function ($best_answer_id) {
            if ($best_answer_id == null) return 'NULL';
            else return $best_answer_id;
        });
        $show->panel()->tools(function($tools){
            $tools->disableDelete();
            $tools->disableEdit();
        });

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Question());

        $form->number('user_id', __('User id'));
        $form->number('type_id', __('Type id'))->default(1);
        $form->text('title', __('Title'));
        $form->number('view_number', __('View number'));
        $form->number('best_answer_id', __('Best answer id'));
        $form->number('vote_number', __('Vote number'));
        $form->switch('updated', __('Updated'));
        $form->datetime('schedule_time', __('Schedule time'))->default(date('Y-m-d H:i:s'));
        $form->switch('status', __('Status'))->default(1);
        $form->datetime('solved_at', __('Solved at'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
