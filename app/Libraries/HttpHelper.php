<?php

namespace App\Libraries;

class HttpHelper {

    //post
    public function post($url,$data, $decode = true) {
        return $this->request($url, $this->createData($data), 'post', $decode);
    }

    //get
    public function get($url, $data, $decode = true) {
        return $this->request($url, $this->createData($data), 'get', $decode);
    }

    /**
     * 创建URL参数
     */
    private function createData($data) {
        $data = http_build_query($data);
        return $data;
    }

    /**
     * 执行一个 HTTP 请求
     *
     * @param string 	$Url 	执行请求的Url
     * @param mixed	$Params 表单参数
     * @param string	$Method 请求方法 post / get
     * @param boolen $decode 是否json_decode
     * @return array 结果数组
     */
    private function request($Url, $Params, $Method, $decode) {
//    echo "$Url?$Params";exit();
        $Curl = curl_init(); //初始化curl
        if ('get' == $Method) {//以GET方式发送请求
            curl_setopt($Curl, CURLOPT_URL, "$Url?$Params");
        } else {//以POST方式发送请求
            curl_setopt($Curl, CURLOPT_URL, $Url);
            curl_setopt($Curl, CURLOPT_POST, 1); //post提交方式
            curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params); //设置传送的参数
        }

        curl_setopt($Curl, CURLOPT_HEADER, false); //设置header
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true); //要求结果为字符串且输出到屏幕上
        curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, 3); //设置等待时间
        $Res = curl_exec($Curl); //运行curl

        //TODO 服务器请求URL和返回结果
        //echo "$Url?$Params";exit();
        curl_close($Curl);
        if ($decode) {
            return json_decode($Res, true);
        } else {
            return $Res;
        }
    }

    /*
     * 获取header头部信息
     * **/
    public static function getHeader($key, $def = NULL)
    {
        $http_key = 'HTTP_'.strtoupper($key);
        return array_key_exists($http_key, $_SERVER) && isset($_SERVER[$http_key]) ? $_SERVER[$http_key] : $def;
    }

}
