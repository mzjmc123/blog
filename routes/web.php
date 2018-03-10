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


Route::any('admin/index','Admin\UserController@index');
Route::any('admin/pwd','Admin\UserController@pwd');


Route::group(['middleware'  => ['admin'],'prefix'=>'admin','namespace'=>'Admin'],function(){
    //管理员 master
    Route::any('/user','IndexController@index');
    Route::any('/master','UserController@master');
    Route::any('/master/add','UserController@add');
    Route::any('/master/update_password/{id}','UserController@update_password');
    Route::post('/master/store_password','UserController@store_password');
    Route::post('/master/member_stop/{id}','UserController@member_stop');
    Route::post('/master/member_del/{id}','UserController@member_del');
    Route::any('/master_del','UserController@master_del');
    Route::any('/master/master_recover/{id}','UserController@master_recover');
    Route::get('/logout','IndexController@logout');
    //导航 navs
    Route::get('/navs/index','NavsController@index');
    Route::any('/navs/add','NavsController@add');
    Route::post('/navs/navs_stop/{id}','NavsController@navs_stop');
    Route::post('/navs/navs_recover/{id}','NavsController@navs_recover');
    Route::get('/navs/navs_edit/{id}','NavsController@navs_edit');
    Route::post('/navs/storg','NavsController@storg');
    //分类 category
    Route::get('cate/index','CategoryController@index');
    Route::any('cate/add','CategoryController@add');
    Route::post('/cate/cate_stop/{id}','CategoryController@cate_stop');
    Route::post('/cate/cate_recover/{id}','CategoryController@cate_recover');
    Route::get('/cate/cate_edit/{id}','CategoryController@cate_edit');
    Route::post('/cate/update','CategoryController@update');
    //文章article
    Route::get('article/index','ArticleController@index');
    Route::any('article/add','ArticleController@add');
    Route::post('article/upload','CommonController@upload');
    Route::post('article/article_stop','ArticleController@article_stop');
    Route::post('article/article_recover','ArticleController@article_recover');
    Route::get('article/article_edit/{id}','ArticleController@article_edit');
    Route::post('article/update','ArticleController@update');
    //网站配置 config
    Route::get('config/index','ConfigController@index');
    Route::any('config/add','ConfigController@add');
    Route::get('config/config_edit/{id}','ConfigController@edit');
    Route::post('config/update','ConfigController@update');
    Route::post('config/config_stop','ConfigController@config_stop');
    Route::post('config/config_recover','ConfigController@config_recover');
    Route::post('config/content','ConfigController@content');
    Route::get('config/putfile','ConfigController@putfile');

});

// 前台
Route::get('/','Home\IndexController@index');