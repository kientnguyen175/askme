<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;
use App\Admin\Actions\Post\BatchRestore;
Use Encore\Admin\Admin;

Admin::style('.box-title {font-weight: bold; color: #3B8DBC} .main-footer strong {display: none} .main-footer .pull-right {display: none} th {vertical-align: top !important}');

class UserController extends AdminController
{
    protected $title = 'User';

    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('User Id'))->display(function ($id) {
            return "<a style='color: white' href='http://localhost:8000/admin/manage/users/{$id}'><span class='label label-success'>{$id}</span></a>";
        })->filter()->sortable();
        $grid->column('name', __('Name'))->filter('like')->sortable();
        $grid->column('username', __('Username'))->filter('like')->sortable();
        $grid->column('email', __('Email'))->filter('like')->sortable(); 
        // $grid->column('username', __('Username'))->filter('like');
        $grid->column('points', __('Points'))->filter()->sortable(); 
        $grid->column('follower_number', __('Followers'))->filter()->sortable(); 
        // $grid->column('avatar', __('Avatar'))->image(80,80);
        $grid->column('created_at', __('Registered At'))->display(function ($created_at) {
            return Carbon::parse($created_at)->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        })->filter('date')->sortable();
       
        // $grid->column('email_verified_at', __('Email verified at'));
        // $grid->column('password', __('Password'));
        // $grid->column('remember_token', __('Remember token'));
        // $grid->column('updated_at', __('Updated at'));
        // $grid->column('website_link', __('Website link'));
        // $grid->column('bio', __('Bio'));
        // $grid->column('reset_password_token', __('Reset password token'));
        // $grid->column('role_id', __('Role id'));

        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
        });
        if (\request('_scope_') == 'trashed') {
            $grid->disableActions();
        }
        $grid->filter(function($filter) {
            $filter->scope('trashed', 'Recycle Bin')->onlyTrashed();
            $filter->disableIdFilter();
        });
        $grid->batchActions (function($batch) {
            if (\request('_scope_') == 'trashed') {
                $batch->add(new BatchRestore());
            }
        });
        $grid->export(function ($export) {

            $export->column('id', function ($value, $original) {
                return $original;
            });
        });
        
        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('User Id'));
        $show->field('name', __('Name'));
        $show->field('username', __('Username'))->as(function ($username) {
            if ($username == null) return 'NULL';
            else return $username;
        });
        $show->field('email', __('Email'));
        
        $show->avatar()->as(function ($avatar) {
            if ($avatar == null) return 'http://localhost:8000/images/default_avatar.png';
            else return $avatar;
        })->image(80,80);
        // $show->field('email_verified_at', __('Email verified at'));
        // $show->field('password', __('Password'));
        // $show->field('remember_token', __('Remember token'));
        $show->field('website_link', __('Website link'))->as(function ($website_link) {
            if ($website_link == null) return 'NULL';
            else return $website_link;
        });
        $show->field('bio', __('Bio'))->as(function ($bio) {
            if ($bio == null) return 'NULL';
            else return $bio;
        });
        $show->field('points', __('Points'));
        $show->field('follower_number', __('Followers'));
        $show->field('created_at', __('Registered At'))->as(function ($created_at) {
            return Carbon::parse($created_at)->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        });
        // $show->field('reset_password_token', __('Reset password token'));
        // $show->field('role_id', __('Role id'));
        $show->panel()->tools(function($tools){
            $tools->disableDelete();
            $tools->disableEdit();
        });

        $show->questions('User\'s Questions', function ($questions) {
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

        $show->answers('User\'s Answers', function ($answers) {
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

        $show->comments('User\'s Comments', function ($comments) {
            $comments->column('id', __('Comment Id'))->display(function ($id) {
                return "<a style='color: white' href='http://localhost:8000/admin/manage/comments/{$id}'><span class='label label-success'>{$id}</span></a>";
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
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
        $form->text('remember_token', __('Remember token'));
        $form->textarea('avatar', __('Avatar'));
        $form->text('website_link', __('Website link'));
        $form->text('bio', __('Bio'));
        $form->number('points', __('Points'));
        $form->text('reset_password_token', __('Reset password token'));
        $form->number('role_id', __('Role id'))->default(1);
        $form->text('username', __('Username'));

        return $form;
    }
}
