<?php

use Illuminate\Support\Facades\Route;

Route::get('/create_index', function() {
    \App\Models\Question::addAllToIndex(); 
    \App\Models\Content::addAllToIndex();

    return dd("indexed");
});

Route::get('/', 'HomeController@index')->name('home');
Auth::routes();
Route::get('auth/redirect/{provider}', 'SocialLoginController@redirect')->name('login.social.redirect');
Route::get('callback/{provider}', 'SocialLoginController@callback')->name('login.social.callback');
Route::group(['namespace' => 'User'], function () {
    Route::get('{username}', 'UserController@showBy')->name('user.showBy');
    Route::group(['prefix' => 'user'], function () {
        Route::get('{userId}/profile', 'UserController@show')->name('user.show');
        Route::get('{userId}/followers', 'UserController@followers')->name('user.followers');
        Route::middleware('auth')->group(function () {
            Route::patch('changePassword', 'UserController@changePassword')->name('user.changePassword');
            Route::get('edit', 'UserController@edit')->name('user.edit');
            Route::patch('update', 'UserController@update')->name('user.update');
            Route::get('/questionForm', 'PostController@create')->name('user.showAskForm');
            Route::post('/postQuestion', 'PostController@store')->name('user.postQuestion');
            Route::get('/newsfeed', 'PostController@newsfeed')->name('user.newsfeed');
            Route::get('/pendingQuestions', 'PostController@pending')->name('user.pending');
            Route::post('/saveQuestion/{questionId}', 'UserController@saveQuestion')->name('users.saveQuestion');
            Route::post('/unsaveQuestion/{questionId}', 'UserController@unsaveQuestion')->name('users.unsaveQuestion');
            Route::post('/saveToCollection/{questionId}', 'UserController@saveToCollection')->name('users.saveToCollection');
            Route::get('/savedQuestions', 'UserController@savedQuestions')->name('users.savedQuestions');
            Route::get('/collection/{collectionId}', 'CollectionController@show')->name('collections.show');
            Route::post('/collection/{collectionId}/update', 'CollectionController@update')->name('collections.update');
            Route::post('/collection/{collectionId}/destroy', 'CollectionController@destroy')->name('collections.destroy');
            Route::post('/followUser/{userId}', 'UserController@followUser')->name('user.followUser');
            Route::post('/unfollowUser/{userId}', 'UserController@unfollowUser')->name('user.unfollowUser');
            Route::post('/followQuestion/{questionId}', 'UserController@followQuestion')->name('user.followQuestion');
            Route::post('/unfollowQuestion/{questionId}', 'UserController@unfollowQuestion')->name('user.unfollowQuestion');
            Route::get('/readNotiPublishQuestion/{notiId}/{questionId}', 'UserController@readNotiPublishQuestion')->name('user.readNotiPublishQuestion');
            Route::get('/readNotiNewAnswer/{notiId}/{questionId}/{newAnswerId}', 'UserController@readNotiNewAnswer')->name('user.readNotiNewAnswer');
            Route::get('/readNewCommentNoti/{notiId}/{questionId}/{commentId}/{page}', 'UserController@readNewCommentNoti')->name('user.readNewCommentNoti');
            Route::get('/markAllAsRead', 'UserController@markAllAsRead')->name('user.markAllAsRead');
        });
        Route::post('resetPasswordLink', 'UserController@sendResetPasswordLink')->name('resetPasswordLink');
        Route::get('newPassword/{userId}/{token}', 'UserController@newPassword')->name('newPassword')->middleware('password.new');
        Route::post('resetPassword/{userId}', 'UserController@resetPassword')->name('resetPassword');
    });
    Route::get('{userId}/newsfeed', 'UserController@newsfeed')->name('user.newsfeedBy');
    Route::get('{userId}/answers', 'UserController@answers')->name('user.answers');
    Route::group(['prefix' => 'questions'], function () {
        Route::get('/view', 'QuestionController@view')->name('questions.view');
        Route::get('/view/{searchText}/{tab}', 'QuestionController@viewByTab')->name('questions.viewByTab');
        Route::get('/{questionId}', 'QuestionController@show')->name('questions.show')->middleware('question.pending');
        Route::get('/{questionId}/sortBy/{sortBy}', 'QuestionController@showBy')->name('questions.showBy')->middleware('question.pending');
        Route::get('/{questionId}/goToBestAnswer', 'QuestionController@goToBestAns')->name('questions.goToBestAns');
        Route::middleware('auth')->group(function () {
            Route::post('{questionId}/vote', 'QuestionController@vote')->name('questions.vote');
            Route::post('{questionId}/createAnswer', 'AnswerController@store')->name('answers.store');
            Route::post('voteAnswer/{answerId}', 'AnswerController@vote')->name('answers.vote');
            Route::post('answer/{answerId}/updateConversation', 'AnswerController@updateConversation')->name('answers.updateConversation');
            Route::post('answer/{answerId}/deleteConversationThread', 'AnswerController@deleteConversationThread')->name('answers.deleteConversationThread');
            Route::patch('{questionId}/bestAnswer', 'QuestionController@bestAnswer')->name('questions.best');
            Route::post('answer/{answerId}/addComment', 'CommentController@store')->name('comments.store');
            Route::get('{questionId}/delete', 'QuestionController@destroy')->name('questions.destroy');
            Route::get('{questionId}/edit', 'QuestionController@edit')->name('questions.edit')->middleware('question.edit');
            Route::post('{questionId}/update', 'QuestionController@update')->name('questions.update');
        });
    });
    Route::group(['prefix' => 'answers'], function () {
        Route::get('{answerId}/show', 'AnswerController@show')->name('answers.show');
        Route::middleware('auth')->group(function () {
            Route::get('{answerId}/edit', 'AnswerController@edit')->name('answers.edit')->middleware('answer.edit');
            Route::post('{answerId}/update', 'AnswerController@update')->name('answers.update');
            Route::post('{answerId}/destroy', 'AnswerController@destroy')->name('answers.destroy');
            Route::get('readUpdateAnswerNoti/{notiId}/{questionId}/{answerId}', 'AnswerController@readUpdateAnswerNoti')->name('answers.readUpdateAnswerNoti');
        });
    });
    Route::group(['prefix' => 'comments'], function () {
        Route::middleware('auth')->group(function () {
            Route::post('{commentId}/destroy', 'CommentController@destroy')->name('comments.destroy');
            Route::post('{commentId}/update', 'CommentController@update')->name('comments.update');
        });
    });
    Route::group(['prefix' => 'tags'], function () {
        Route::get('/view/{tab}', 'TagController@view')->name('tags.view');
        Route::get('/search/{searchText}/{tab}', 'TagController@search')->name('tags.search');
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get('/view/{tab}', 'UserController@view')->name('users.view');
        Route::get('/search/{searchText}/{tab}', 'UserController@search')->name('users.search');
    });
});
