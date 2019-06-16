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

 Route::group(['middleware' => ['web']], function () {
     //上传图片接口
     Route::post('upload','Api\UploadImg@uploadImg');
     Route::get('login', 'Auth\LoginController@login');
     Route::post('login', 'Auth\LoginController@login');
     Route::get('logout', 'Auth\LoginController@logout');
     Route::post('logout', 'Auth\LoginController@logout');
     //修改密码
     Route::get('upd_password','Auth\ResetPasswordController@updPassword');
     Route::post('upd_password','Auth\ResetPasswordController@updPassword');

     Route::group(['middleware' => ['check.login']], function () {

    //帐号管理
     Route::group(['namespace' => 'Account','prefix'=>'account','as'=>'account.'], function () {
         Route::get('list', ['as' => 'list', 'uses' => 'TUserController@index']);
         Route::get('add', ['as' => 'add', 'uses' => 'TUserController@add']);
         Route::post('doAdd', ['as' => 'doAdd', 'uses' => 'TUserController@doAdd']);
         Route::get('edit/{account_id}', ['as' => 'edit', 'uses' => 'TUserController@edit']);
         Route::post('doEdit', ['as' => 'doEdit', 'uses' => 'TUserController@doEdit']);
         Route::post('delete', ['as' => 'delete', 'uses' => 'TUserController@delete']);
     });

     //用户信息~
     Route::group(['namespace'=>'User','prefix'=>'user','as'=>'user.'],function(){
         Route::post('ajax_user_info',['as'=>'ajax_user_info','uses'=>'UserController@ajaxUserInfo']); //获取用户信息~
         //用户优惠券~
         Route::get('coupons',['as'=>'coupons','uses'=>'UserController@getUserCoupons']);
     });
    //运营管理
    Route::group(['namespace' => 'Operate'], function () {
        Route::group(['prefix' => 'comment', 'as' => 'comment.'], function () {
            Route::get('index', ['as' => 'index', 'uses' => 'CommentController@index']);
        });
    });

     //商品信息
    Route::group(['namespace' => 'Goods','prefix' => 'goods', 'as' => 'goods.'], function () {
        Route::get('list', ['as' => 'list', 'uses' => 'GoodsController@index']);
        Route::get('add',['as'=>'add','uses'=>'GoodsController@add']);
        Route::post('doAdd',['as'=>'add','uses'=>'GoodsController@doAdd']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'GoodsController@edit']);
        Route::post('doEdit', ['as' => 'doEdit', 'uses' => 'GoodsController@doEdit']);
        Route::get('delete/{goods_id}', ['as' => 'delete', 'uses' => 'GoodsController@delete']);
        //search_goods 搜索商品
        Route::get('search_goods/{page}',['as'=>'search_goods','uses'=>'GoodsController@searchGoods']);
     });

    //订单管理
     Route::group(['namespace' => 'Orders','prefix' => 'order', 'as' => 'order.'], function () {
         //统一收银
         Route::group(['prefix' => 'unified', 'as' => 'unified.'], function () {
             Route::get('list', ['as' => 'list', 'uses' => 'CollectController@index']);
             Route::get('create', ['as' => 'create', 'uses' => 'CollectController@create']); //创建订单~
             Route::get('export', ['as' => 'export', 'uses' => 'CollectController@export']); //导出excel
             Route::post('ajax_order_user_coupons',['as' =>'ajax_order_user_coupons', 'uses' => 'CollectController@ajaxOrderUserCoupons']); //用户订单相关的信息~
             //订单号查询
             Route::get('search_order_num',['as'=>'search_order_num','uses'=>'CollectController@searchOrderNum']);
             //订单确认页
             Route::match(['get','post'],'ajax_create_check',['as' => 'ajax_create_check', 'uses' => 'CollectController@ajaxCreateCheck']);
             //订单详情页
             Route::get('detail',['as'=>'detail','uses'=>'CollectController@detail']);

             //修改地址~
             Route::get('edit_address',['as'=>'edit_address','uses'=>'CollectController@editAddress']);
             //添加地址~
             Route::get('add_address',['as'=>'add_address','uses'=>'CollectController@addAddress']);
             //收货地址
             Route::get('address_list',['as'=>'address_list','uses'=>'CollectController@addressList']);

             //执行创建订单~
             Route::post('do_create_order',['as'=>'do_create_order','uses'=>'CollectController@doCreateOrder']);
         });
         //非统一收银~
         Route::group(['prefix' => 'non_unity', 'as' => 'non_unity.'], function () {
             Route::get('list', ['as' => 'list', 'uses' => 'UnityController@index']);
             Route::get('export', ['as' => 'export', 'uses' => 'UnityController@export']);
         });

     });

    //收银概览
    Route::group(['namespace' => 'Cash'], function () {
        Route::group(['prefix' => 'cash', 'as' => 'cash.'], function () {
            Route::get('list', ['as' => 'list', 'uses' => 'CashController@index']);
        });
    });

   //结算中心
    Route::group(['namespace'=>'SettlCenter','prefix'=>'settl_center','as'=>'settl_center'],function(){
        Route::group(['prefix' => 'settl', 'as' => 'settl.'], function () {
            Route::get('unified', ['as' => 'unified', 'uses' => 'SettlController@unified']); //统一结算
            Route::get('non_unity', ['as' => 'unified', 'uses' => 'SettlController@nonUnity']); //非统一结算
            Route::get('h_gold_detail', ['as' => 'h_gold_detail', 'uses' => 'SettlController@hGoldDetail']); //滞保金明细~
            Route::get('h_gold_refund', ['as' => 'h_gold_refund', 'uses' => 'SettlController@hGoldRefund']); //滞保金退款~

        });
    });
    //后台首页
    Route::get('/',['as'=>'home','uses'=>'Home\HomeController@index']);
     });
});
