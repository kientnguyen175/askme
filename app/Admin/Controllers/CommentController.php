<?php

namespace App\Admin\Controllers;

use App\Models\Comment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Post\BatchRestore;
Use Encore\Admin\Admin;
use Carbon\Carbon;

Admin::style('.box-title {font-weight: bold; color: #3B8DBC} .main-footer strong {display: none} .main-footer .pull-right {display: none} th {vertical-align: top !important}');

class CommentController extends AdminController
{
    protected $title = 'Comment';

    protected function grid()
    {
        $grid = new Grid(new Comment());

        $grid->column('id', __('Id'))->display(function ($id) {
            return "<a style='color: white' href='http://localhost:8000/admin/manage/comments/{$id}'><span class='label label-success'>{$id}</span></a>";
        })->filter()->sortable();
        $grid->column('comment', __('Content'))->filter('like')->sortable();
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
                return $original;
            });
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Comment::findOrFail($id));
        $show->panel()->tools(function($tools){
            $tools->disableDelete();
            $tools->disableEdit();
        });
        $show->field('id', __('Comment Id'));
        $show->field('answer_id', __('Answer Id'));
        $show->field('user_id', __('User Id'));
        $show->field('comment', __('Content'));
        $show->field('created_at', __('Created At'))->as(function ($created_at) {
            return Carbon::parse($created_at)->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        });

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Comment());

        $form->number('answer_id', __('Answer id'));
        $form->number('user_id', __('User id'));
        $form->textarea('comment', __('Comment'));
        $form->switch('updated', __('Updated'));

        return $form;
    }
}
