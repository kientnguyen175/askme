<?php

namespace App\Admin\Controllers;

use App\Models\Tag;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;
use App\Admin\Actions\Post\BatchRestore;
Use Encore\Admin\Admin;

Admin::style('.box-title {font-weight: bold; color: #3B8DBC} .main-footer strong {display: none} .main-footer .pull-right {display: none} th {vertical-align: top !important}');

class TagController extends AdminController
{
    protected $title = 'Tag';

    protected function grid()
    {
        $grid = new Grid(new Tag());

        $grid->column('id', __('Tag Id'))->display(function ($id) {
            return "<a style='color: white' href='http://localhost:8000/admin/manage/tags/{$id}'><span class='label label-success'>{$id}</span></a>";
        })->filter()->sortable();
        $grid->column('tag', __('Tag'))->filter('like')->sortable();
        // $grid->column('updated_at', __('Updated at'));
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
        $show = new Show(Tag::findOrFail($id));

        $show->field('id', __('Tag Id'));
        $show->field('tag', __('Tag'));
        $show->panel()->tools(function($tools){
            $tools->disableDelete();
            $tools->disableEdit();
        });
        $show->questions('Tag\'s Questions', function ($questions) {
            $questions->column('id', __('Question Id'))->display(function ($id) {
                return "<a style='color: white' href='http://localhost:8000/admin/manage/questions/{$id}'><span class='label label-success'>{$id}</span></a>";
            })->sortable();
            $questions->column('title', __('Title'))->sortable();;
            $questions->disableActions();
            $questions->disableFilter();
            // $images->disableRowSelector();
            $questions->disableColumnSelector();
            $questions->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $questions->disableCreateButton();
            $questions->disableExport();
        });

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Tag());

        $form->text('tag', __('Tag'));

        return $form;
    }
}
