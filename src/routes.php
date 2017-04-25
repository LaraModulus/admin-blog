<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin/blog',
    'middleware' => ['web', 'auth'],
    'namespace' => 'LaraMod\Admin\Blog\Controllers',
], function(){
    Route::group([
        'prefix' => 'posts'
    ], function(){
        Route::get('/', ['as' => 'admin.blog.posts', 'uses' => 'PostsController@index']);
        Route::get('/form', ['as' => 'admin.blog.posts.form', 'uses' => 'PostsController@getForm']);
        Route::post('/form', ['as' => 'admin.blog.posts.form', 'uses' => 'PostsController@postForm']);

        Route::get('/delete', ['as' => 'admin.blog.posts.delete', 'uses' => 'PostsController@delete']);
    });

    Route::group([
        'prefix' => 'categories'
    ], function(){
        Route::get('/', ['as' => 'admin.blog.categories', 'uses' => 'CategoriesController@index']);
        Route::get('/form', ['as' => 'admin.blog.categories.form', 'uses' => 'CategoriesController@getForm']);
        Route::post('/form', ['as' => 'admin.blog.categories.form', 'uses' => 'CategoriesController@postForm']);

        Route::get('/delete', ['as' => 'admin.blog.categories.delete', 'uses' => 'CategoriesController@delete']);
    });

    Route::group([
       'prefix' => 'comments'
    ], function(){
        Route::get('/', ['as' => 'admin.blog.comments', 'uses' => 'CommentsController@index']);
        Route::get('/form', ['as' => 'admin.blog.comments.form', 'uses' => 'CommentsController@getForm']);
        Route::post('/form', ['as' => 'admin.blog.comments.form', 'uses' => 'CommentsController@postForm']);

        Route::get('/delete', ['as' => 'admin.blog.comments.delete', 'uses' => 'CommentsController@delete']);
    });


});
