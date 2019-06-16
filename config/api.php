<?php

/**
 * Created by PhpStorm.
 * User: wy220
 * Date: 2018/9/6
 * Time: 17:57
 */

if(env('APP_ENV') != 'Production'){
    return [
        //图片上传相关
        'img_url'=>'https://img.hxjbcdn.com/',
        'accessKey'=>'J5nxOfb-zYKavzs2myV9NIywYboTPtQQJKaViMSE',
        'secretKey'=>'UfPCQqd5j2JekLgVUH0gbG680ur79_WIKqnHsu5Y',
        'bucketName'=>'hxjb-img'
    ];
}else{
    return [
        //图片上传相关
        'img_url'=>'https://img.hxjbcdn.com/',
        'accessKey'=>'J5nxOfb-zYKavzs2myV9NIywYboTPtQQJKaViMSE',
        'secretKey'=>'UfPCQqd5j2JekLgVUH0gbG680ur79_WIKqnHsu5Y',
        'bucketName'=>'hxjb-img'
    ];
}