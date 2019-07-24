<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['middleware'=>'weixin'],function ($app) {

    $app->get('login', function () {
        return view('Mobile.User.login');
    });

    $app->get('weixin', function () {
        return view('Mobile.weixin');
    });

    $app->post('login', 'User\UserController@login');

    $app->get('reg', function () {
        return view('Mobile.User.reg');
    });

    $app->post('reg', 'User\UserController@reg');

    $app->get('user/code/{phone}', 'User\UserController@sendRegSms');
});

$router->group(['middleware'=>'login'],function ($app){


    $app->get('/', 'Index\IndexController@index');

    $app->get('user/my','User\UserController@my');

    $app->post('user/update','User\UserController@update');

    $app->get('user/search','Friend\FriendController@getRequestManage');

    $app->get('user/search/friend/{phone}','User\UserController@searchFriend');

    $app->get('user/add/friend/{friend_id}','User\UserController@addFriend');

    $app->get('user/add/group/{gid}','User\UserController@addGroup');

    $app->get('user/search/group/{id}','User\UserController@searchGroup');



    $app->get('friend/request/agree/{friend_id}','Friend\FriendController@Agree');

    $app->get('friend/request/refuse/{friend_id}','Friend\FriendController@Refuse');


    $app->get('group/manage','Group\GroupController@lists');

    $app->get('group/message/{id}','Group\GroupController@message');

    $app->post('group/create','Group\GroupController@create');

    $app->get('group/del/{gid}','Group\GroupController@del');



    $app->post('upload/file','Help\QiNiu\QiNiuController@upload');


    $app->get('chat/log/friend/{id}','Chat\ChatController@friendLog');

    $app->get('chat/log/group/{id}','Chat\ChatController@groupLog');


});


