<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//api路由组不需要鉴权的
Route::group(['middleware' => ['api', 'cors']], function ($route) {
    $route->get('/index', 'BooksController@index');
    $route->post('/register', 'UserController@register');
    $route->post('/sms', 'UserController@getSms');
    $route->post('/getPwd', 'UserController@resetPwd');
    $route->post('/login', 'UserController@login');
    $route->get('/book/comment/{bookId}', 'CommentController@commentList');
    $route->get('/book/{bookId}/section', 'BooksController@getAllSection');
    $route->get('/book/{bookId}', 'BooksController@getBookInfo');
    $route->get('/search', 'BooksController@search');
    $route->get('/book/{bookId}/section/{id}', 'BooksController@getSection');
});
//api鉴权路由组
Route::group(['middleware' => ['api', 'cors', 'jwt.api.auth']], function ($route) {
    $route->post('/book/comment/{bookId}', 'CommentController@setComment');
    $route->post('/user/info', 'UserController@createUserInfo');
    $route->get('/user/info', 'UserController@getUserInfo');
    $route->post('/user/collect', 'UserController@addCollect');
    $route->post('/user/collect/check', 'UserController@isCollect');
    $route->post('/user/collect/cancel', 'UserController@cancelCollect');
    $route->post('/user/editPwd', 'UserController@updateUserPwd');
    $route->post('/files', 'FileController@files');
    $route->post('/user/upload', 'UserController@uploadBook');
    $route->post('/user/download', 'UserController@downloadLog');
});
