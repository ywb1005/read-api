<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
Route::get('/', function () {
    return view('welcome');
});

//api路由组不需要鉴权的
Route::group(['prefix' => '/admin','middleware' => ['api', 'cors']], function ($route) {
    $route->post('/login', 'UserController@adminLogin');
});
//api鉴权路由组
Route::group(['prefix' => '/admin', 'middleware' => ['api', 'cors', 'jwt.api.auth']], function ($route) {
    $route->get('/user/list', 'UserController@userList');
    $route->post('/user/add', 'UserController@createMember');
    $route->get('/adminUser/list', 'UserController@getAdminUser');
    $route->post('/adminUser/add', 'UserController@addAdminUser');
    $route->post('/adminUser/delete/{id}', 'UserController@deleteAdminUser');
    $route->post('/adminUser/update/{id}', 'UserController@updateAdminUser');
    $route->get('/comment/list', 'CommentController@getAdminCommentList');
    $route->post('/comment/search', 'CommentController@search');
    $route->post('/comment/delete/{id}', 'CommentController@deleteComment');
    $route->post('/user/search', 'UserController@search');
    $route->post('/file', 'FileController@file');
    $route->post('/files', 'FileController@files');
    $route->post('/book/create', 'BooksController@createBook');
    $route->post('/book/section/create', 'BooksController@createSection');
    $route->get('/book/list', 'BooksController@getBookList');
    $route->get('/book/info/{id}', 'BooksController@getBookInfoById');
    $route->get('/book/{id}/section/list', 'BooksController@getSectionListById');
    $route->post('/book/edit/{id}', 'BooksController@updateBook');
    $route->post('/book/{bookId}/section/edit/{id}', 'BooksController@updateSection');
    $route->get('/book/section/info/{id}', 'BooksController@getSectionById');
    $route->get('/option/download/list', 'OptionController@getDownloadList');
    $route->get('/option/upload/list', 'OptionController@getUploadList');
});