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
Route::get('/', 'AdminController@index');
// Route::get('/', 'AgentController@index');
Route::get('/admin/index', 'AdminController@index');
Route::post('/admin/check', 'AdminController@check');
Route::get('/index/access','IndexController@access');


Route::get('/agent/index','AgentController@index');

Route::group(["middleware" =>['web','checkNode'] ], function() {

//首页
Route::get('/index/index', 'IndexController@index');
    //修改密码
    Route::any('/index/change_pwd', 'AdminController@changePwd');
//我的下级
Route::get('/index/subordinates', 'IndexController@mySubordinates');
    //成为代理
    Route::get('/index/add_agent', 'IndexController@addAgents');
    Route::post('/index/create_agents', 'IndexController@createAgents');
//申请提现
Route::get('/index/widthdraw', 'IndexController@widthdraw');
    //提现记录
    Route::get('/index/widthdrawdetail', 'IndexController@widthdrawDetail');
    //提现记录
    Route::post('/index/widthdrawUp', 'IndexController@widthdrawUpdate');

    //代理提现
    Route::post('/index/widthdraw_agent', 'IndexController@widthdrawAgent');
//收益明细
Route::any('/index/profitdetail', 'IndexController@profitDetail');
    //会员明细
    Route::any('/index/playerDetail', 'IndexController@playerDetail');
    //代理明细
    Route::any('/index/agentsDetail', 'IndexController@agentsDetail');
//个人信息-修改个人资料
Route::post('/index/profile', 'IndexController@updateProfile');
//退出登录
Route::get('/admin/out', 'AdminController@out');

});








