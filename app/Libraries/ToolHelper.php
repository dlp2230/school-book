<?php
/**
 * Created by PhpStorm.
 * User: deng
 * Date: 2018/8/26
 * Time: 22:11
 */
namespace App\Libraries;

class ToolHelper{
    static function errorReport($msg)
    {
        $data = [
            'status' => 'fail',
            'data' => [
                'error' => $msg,
            ]
        ];

        die(json_encode($data));
    }

    static function successReport($data)
    {
        $data = [
            'status' => 'success',
            'data' => $data,
        ];
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /*
     * api to data
     * **/
    static function returnApiData($result=[])
    {
        //error
        if($result['code'] == 400 && !empty($result)){
            return [];
            //写入日志
            //self::errorReport($result['code'],json_encode($result));
        }
        //success
        if($result['code'] == 200 && !empty($result)){
            return $result['result'];
        }

        return [];
    }

}