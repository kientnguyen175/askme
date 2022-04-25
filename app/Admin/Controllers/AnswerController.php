<?php

namespace App\Admin\Controllers;

use App\Models\Answer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
Use Encore\Admin\Admin;
use Carbon\Carbon;
use App\Admin\Actions\Post\BatchRestore;

Admin::style('.box-title {font-weight: bold; color: #3B8DBC} .main-footer strong {display: none} .main-footer .pull-right {display: none} .mejs__audio {float: left} th {vertical-align: top !important}');

class AnswerController extends AdminController
{
    protected $title = 'Answer';

    protected function grid()
    {
        $grid = new Grid(new Answer());

        $grid->column('id', __('Answer Id'))->display(function ($id) {
            return "<a style='color: white' href='http://localhost:8000/admin/manage/answers/{$id}'><span class='label label-success'>{$id}</span></a>";
        })->filter()->sortable();
        $grid->column('content.content', __('Content'))->display(function ($content) {
            $content = preg_replace('/<comment-start[^>]*>/', '', $content);
            $content = preg_replace('/<\/?comment-start[^>]*>/', '', $content);
            $content = preg_replace('/<comment-end[^>]*>/', '', $content);
            $content = preg_replace('/<\/?comment-end[^>]*>/', '', $content);

            return $content;
        });
        $grid->column('vote_number', __('Votes'))->filter()->sortable();
        $grid->column('created_at', __('Created At'))->display(function ($created_at) {
            return Carbon::parse($created_at)->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        })->filter('date')->sortable();
        $grid->filter(function($filter) {
            $filter->scope('trashed', 'Recycle Bin')->onlyTrashed();
            $filter->disableIdFilter();
        });
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            if (\request('_scope_') == 'trashed') {
                $actions->disableDelete();
            }
        });
        $grid->batchActions (function($batch) {
            if (\request('_scope_') == 'trashed') {
                $batch->add(new BatchRestore());
            }
        });
        if (\request('_scope_') == 'trashed') {
            $grid->disableActions();
        }
        $grid->disableCreateButton();
        $grid->export(function ($export) {

            $export->column('id', function ($value, $original) {
                return html_entity_decode(strip_tags($original));
            });
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Answer::findOrFail($id));

        $show->field('id', __('Answer Id'));
        $show->field('user_id', __('User Id'));
        $show->field('question_id', __('Question Id'));
        $show->field('content.content', __('Content'))->as(function ($content) {
            $content = preg_replace('/<comment-start[^>]*>/', '', $content);
            $content = preg_replace('/<\/?comment-start[^>]*>/', '', $content);
            $content = preg_replace('/<comment-end[^>]*>/', '', $content);
            $content = preg_replace('/<\/?comment-end[^>]*>/', '', $content);

            return $content;
        })->unescape();
        $show->field('vote_number', __('Votes'));
        $show->field('created_at', __('Created At'))->as(function ($created_at) {
            return Carbon::parse($created_at)->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        });
        $show->panel()->tools(function($tools){
            $tools->disableDelete();
            $tools->disableEdit();
        });
        $show->images('Answer\'s Images', function ($images) {
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
        $show->medias('Answer\'s Audios', function ($medias) {
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
        $show->comments('Answer\'s Comments', function ($comments) {
            $comments->column('id', __('Comment Id'))->display(function ($id) {
                return "<a style='color: white' href='http://localhost:8000/admin/manage/answers/{$id}'><span class='label label-success'>{$id}</span></a>";
            })->sortable();
            $comments->column('comment', __('Content'));
            $comments->disableActions();
            $comments->disableFilter();
            // $images->disableRowSelector();
            $comments->disableColumnSelector();
            $comments->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $comments->disableCreateButton();
            $comments->disableExport();
        });

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Answer());

        $form->number('user_id', __('User id'));
        $form->number('question_id', __('Question id'));
        $form->number('vote_number', __('Vote number'));

        return $form;
    }
}
