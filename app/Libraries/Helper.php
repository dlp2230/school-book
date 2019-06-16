<?php

namespace App\Libraries;

class Helper {

    /**
     * post提交方法
     */
    const METHOD_POST = 1;

    /**
     * get提交方法
     *
     */
    const METHOD_GET = 0;

    /**
     * 成功状态
     */
    const STATUS_OK = 1;

    /**
     * 失败状态
     */
    const STATUS_ERR = 0;

    /**
     * 其他状态
     */
    const STATUS_FAIL = 2;

    /**
     * 家博会加密用秘钥
     *
     * @var string
     */
    public static $web_key = '1n2W!dEr@8f4P3"gSql:<`~XC5b)*?T(vBHY>|6"u7jz&JNs$Bi^KMoe#B6YF0kJ9a,;\\%/+.\'][-=';

    /**
     * 家博会MD5加密方法
     *
     * @param string $str 要加密的字符串
     * @param string | null $key 字符串
     *
     * @return string
     */
    public static function jbMd5($str, $key = null)
    {
        if ($key !== null) {
            return '' === $str ? '' : md5(sha1($str) . $key);
        } else {
            return '' === $str ? '' : md5(sha1($str) . self::$web_key);
        }
    }

    /**
     * 验证密码是否符合要求
     *
     * @param string $pwd
     * @return boolean
     */
    public static function checkPwd($pwd)
    {
        if (strlen($pwd) < 6) {
            return false;
        }

        return true;
    }

    /**
     * 输出ajax信息
     *
     * @param int $status
     * @param string $msg
     * @param array $data
     */
    public static function ajaxMsg($status, $msg = null, $data = array())
    {
        return response()->json(array('status' => $status, 'msg' => $msg, 'data' => $data));
    }

    /**
     * 获取客户端ip地址
     *
     * @return string 客户端ip地址
     */
    public static function getClientIp()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "unknown";
        }

        return $ip;
    }

    /**
     * 验证是否为手机号码
     *
     * @param string $mobile
     * @return boolean
     */
    public static function isMobile($mobile)
    {
        if (preg_match('/1\d{10}$/', $mobile)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 隐藏手机号码中间4位
     *
     * @param string $mobile
     * @return string $mobile
     */
    public static function hideMobile($mobile)
    {
        return substr_replace($mobile, '****', 3, 4);
    }

    /**
     * 短信模板接口
     *
     * @param $mobile 手机号
     * @param $templateId 模板ID
     * @return void
     */
    public static function sendTemplateMsg($mobile, $templateId, $params = array(), $source_id = 1, $ext = 0)
    {
        header('Content-type:text/html;charset=utf-8');

        $uri = config('hxjb.sms_template_link');

        $uri .= '?mobile=' . $mobile . '&template_id=' . $templateId . '&source_id=' . $source_id . '&ext=' . $ext;

        if (! empty($params)) {
            foreach ($params as $name => $value) {
                $uri .= '&' . $name . '=' . $value;
            }
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);

        curl_close($ch);

        return true;
    }

    /**
     * 获取字符串字节长度
     *
     * @param string $str 字符串
     * @return number $count 字节长度
     */
    function jbStrlen($str)
    {
        $count = 0;
        $strlen = strlen($str);

        for ($i = 0; $i < $strlen; $i ++) {
            if (ord($str{$i}) >= 128) {
                $i = $i + 2;
                $count = $count + 2;
            } else {
                $count = $count + 1;
            }
        }

        return $count;
    }

    /**
     * 验证图片验证码正确性
     *
     * @param string $code 输入的验证码
     * @param string $srcId 验证码来源
     */
    public static function verifyImageCaptcha($code, $srcId)
    {
        // 获取验证码session
        $sessionCaptcha = $_SESSION['captcha' . $srcId];

        if (strtolower($sessionCaptcha) == strtolower($code)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 用户输入的Email跳转到相应的电子邮箱首页
     * @param string $email 电子邮件
     * @return boolean
     */
    public static function emailUrl($email)
    {
        $domain = explode('@', $email);

        $domain=strtolower($domain[1]);

        if ($domain == '163.com') {
            return 'mail.163.com';
        } else if ($domain == 'vip.163.com') {
            return 'vip.163.com';
        } else if ($domain == '126.com') {
            return 'mail.126.com';
        } else if ($domain == 'qq.com' || $domain == 'vip.qq.com' || $domain == 'foxmail.com') {
            return 'mail.qq.com';
        } else if ($domain == 'gmail.com') {
            return 'mail.google.com';
        } else if ($domain == 'sohu.com') {
            return 'mail.sohu.com';
        } else if ($domain == 'tom.com') {
            return 'mail.tom.com';
        } else if ($domain == 'vip.sina.com') {
            return 'vip.sina.com';
        } else if ($domain == 'sina.com.cn' || $domain == 'sina.com') {
            return 'mail.sina.com.cn';
        } else if ($domain == 'tom.com') {
            return 'mail.tom.com';
        } else if ($domain == 'yahoo.com.cn' || $domain == 'yahoo.cn') {
            return 'mail.cn.yahoo.com';
        } else if ($domain == 'tom.com') {
            return 'mail.tom.com';
        } else if ($domain == 'yeah.net') {
            return 'www.yeah.net';
        } else if ($domain == '21cn.com') {
            return 'mail.21cn.com';
        } else if ($domain == 'hotmail.com') {
            return 'www.hotmail.com';
        } else if ($domain == 'sogou.com') {
            return 'mail.sogou.com';
        } else if ($domain == '188.com') {
            return 'www.188.com';
        } else if ($domain == '139.com') {
            return 'mail.10086.cn';
        } else if ($domain == '189.cn') {
            return 'webmail15.189.cn/webmail';
        } else if ($domain == 'wo.com.cn') {
            return 'mail.wo.com.cn/smsmail';
        } else if ($domain == '139.com') {
            return 'mail.10086.cn';
        } else {
            return '';
        }
    }

    /**
     * 格式化价格
     *
     * @param number $price 价格
     * @return number 格式化后的价格
     */
    public static function price($price) {
        return sprintf("%0.2f", $price);
    }

    /**
     * 生成UUID
     *
     * @return string $uuid 唯一标识
     */
    public static function createUuid($prefix = 'HXJB')
    {
        $charid = strtoupper(md5(uniqid(rand(), true)));

        $hyphen = chr(45); // "-"

        $uuid = chr(123) // "{"
            .$prefix.$hyphen
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125); // "}"

        return $uuid;
    }

    /**
     * 根据IP获取城市
     *
     * @param  string $ip ip地址
     * @return string $city 城市名
     */
    public static function getIpCity($ip)
    {
        $ch = curl_init();

        $url = 'http://apis.baidu.com/apistore/iplookupservice/iplookup?ip='.$ip;

        $header = array('apikey: 3b00d4fd26705c4d3dcdf46bf625d818'); // 夏先洋的账号

        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);

        $res = curl_exec($ch);

        $res = json_decode($res, true);

        if ($res['errNum'] == 0) {
            return $res['retData']['city'];
        } else {
            return null;
        }
    }

    /**
     * 获取优酷视频VID
     *
     * @param string $url 视频地址
     * @return string $vid 视频VID
     */
    public static function getYoukuVid($url)
    {
        $urlArray = explode('/', $url);

        $vidPos = count($urlArray) - 2;

        $vid = isset($urlArray[$vidPos]) ? $urlArray[$vidPos] : NULL;

        return $vid;
    }

    /**
     * 隐藏手机号中间四位
     * @param string $url 隐藏前的手机号
     * @return string $vid 隐藏后的手机号
     */
    public static function hide_mobile($mobile)
    {
        if(empty($mobile)) return $mobile;
        if(strlen($mobile) < 11) return $mobile;
        return substr($mobile,0,3).'****'.substr($mobile,7,11);
    }

    /**
     * unicode转utf8
     *
     * @param string $str 转码前的字符串
     * @return string $str 转码后的字符串
     */
    public static function unicode2utf8($str)
    {
        $arr = explode('\\u', $str);

        $string = '';

        foreach ($arr as $k => $v) {
            if(empty($v)) continue;
            $v = str_pad($v,4,'0',STR_PAD_LEFT);
            $string .= '\u'.$v;
        }

        $str = str_replace("\n", "<br />", json_decode('"'.$string.'"'));

        return $str;
    }

    /**
     * unicode转utf8 -换行
     *
     * @param string $str 转码前的字符串
     * @return string $str 转码后的字符串
     */
    public static function unicode2utf8no($str)
    {
        $arr = explode('\\u', $str);

        $string = '';

        foreach ($arr as $k => $v) {
            if(empty($v)) continue;
            $v = str_pad($v,4,'0',STR_PAD_LEFT);
            $string .= '\u'.$v;
        }

        $str = json_decode('"'.$string.'"');

        return $str;
    }

    /**
     * 数组根据给定key序列重排
     *
     * @param array $arr 原数组
     * @param array $keyArr key序列数组
     * @param string $key 匹配条件
     * @return array 新数组
     */
    public static function reTree($arr, $keyArr, $key)
    {
        if (empty($arr) || empty($keyArr) || empty($key)) {
            return $arr;
        } else {
            // 定义新数组
            $newArr = [];

            foreach ($keyArr as $v) {
                if (!isset($newArr[$v])) { $newArr[$v] = []; }

                foreach ($arr as $value) {
                    if ($value->$key == $v) {
                        array_push($newArr[$v], $value);
                    }
                }
            }

            return $newArr;
        }
    }

    /**
     * 数组转为链式参数
     *
     * @param array $arr 原数组
     * @return string $link 链式参数
     */
    public static function arrayToLink($arr)
    {
        $link = ''; // 初始化参数

        if (empty($arr)) {
            return $link;
        } else {
            $i = 0;

            foreach ($arr as $name => $value) {
                $link .= ($i ++ != 0 ? '&' : '').$name.'='.$value;
            }

            return $link;
        }
    }

    /**
     * utf8字符转换成Unicode字符
     * @param  [type] $utf8_str Utf-8字符
     * @return [type]           Unicode字符
     */
    public static function utf8_str_to_unicode($utf8_str) {
        $unicode = 0;
        $unicode = (ord($utf8_str[0]) & 0x1F) << 12;
        $unicode |= (ord($utf8_str[1]) & 0x3F) << 6;
        $unicode |= (ord($utf8_str[2]) & 0x3F);
        return dechex($unicode);
    }

    /**
     * Unicode字符转换成utf8字符
     * @param  [type] $unicode_str Unicode字符
     * @return [type]              Utf-8字符
     */
    public static function unicode_to_utf8($unicode_str) {
        $utf8_str = '';
        $code = intval(hexdec($unicode_str));
        //这里注意转换出来的code一定得是整形，这样才会正确的按位操作
        $ord_1 = decbin(0xe0 | ($code >> 12));
        $ord_2 = decbin(0x80 | (($code >> 6) & 0x3f));
        $ord_3 = decbin(0x80 | ($code & 0x3f));
        $utf8_str = chr(bindec($ord_1)) . chr(bindec($ord_2)) . chr(bindec($ord_3));
        return $utf8_str;
    }

    /*
     * 截图字符串
     * ***/
    public static function cutStr($str='',$len=30){
        //检查参数
        if(!is_string($str) || !is_int($len)){
            return '';
        }
        $length = strlen($str);
        if($length <= 0 ){
            return '';
        }
        if($len>=$length){
            return $str;
        }
        //初始化，统计字符串的个数，
        $count = 0;
        for($i=0;$i<$length;$i++){
            //达到个数跳出循环，$i即为要截取的长度
            if($count == $len){
                break;
            }
            $count++;
            //ord函数是获取字符串的ASCII编码，大于等于十六进制0x80的字符串即为中文字符串
            if(ord($str{$i}) >= 0x80){
                $i +=2;//中文编码的字符串的长度再加2
            }
        } //如果要截取的个数超过了字符串的总个数，那么我们返回全部字符串，不带省略号
        if($len > $count){
            return $str;
        }else{
            return substr($str,0,$i).'...';
        }
    }

    /*
     * java 时间戳
     * **/
    public static function javaTime($time){
        if($time){
            return $time.'000';
        }
        return time().'000';

    }

    /*
     * java 转PHP时间
     * ***/
    public static function javaToPHPTime($time){
        if($time){
            return substr($time,0,10);
        }
        return time();
    }

    public static function xj_strlen($str)
    {

        $count = 0;
        $strlen = strlen($str);

        for ($i = 0; $i < $strlen; $i++) {
            if (ord($str{$i}) >= 128) {
                $i = $i + 2;
                $count = $count + 2;
            } else {
                $count = $count + 1;
            }
        }
        return $count;

    }

    /* 毫秒时间戳转换成日期 */
    public static function msecdate($tag, $time)
    {
        $a = substr($time,0,10);
        $b = substr($time,10);
        $date = date($tag,$a);
        return $date;
    }
    /*
     * 计算二个时间之前的天数
     * **/
    public static function diffBetweenTwoDays ($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }

    /*
     * 计算两者之间的时间
     * **/
    public static function diffBetweenTwoTime ($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        $diff_time = $second1 - $second2;

        if ($diff_time <= 60 * 60) { // 1小时以内
            return floor($diff_time/60) . '分钟后';
        } else if ($diff_time <= 24 * 60 * 60) { // 24小时以内
            return floor(($diff_time)/3600).'小时后';
        } else {
            return floor(($diff_time)/86400).'天后';
        }

        $string .= '\u'.$v;

        $str = json_decode('"'.$string.'"');

        return $str;

    }

    /**
     * 格式化发布时间
     *
     * @param  string $date Y-m-d H:i:s 发布时间
     * @return string 格式化后的时间
     */
    public static function formate_pubish_time($date)
    {
        if (empty($date)) return $date;

        $now_time = time();
        $date_time = strtotime($date);

        $diff_time = $now_time - $date_time;

        if ($diff_time <= 60 * 60) { // 1小时以内
            return ceil($diff_time/60).'分钟前';
            //return date('i', $diff_time) . '分钟前';
        } else if ($diff_time <= 24 * 60 * 60) { // 24小时以内
            return floor(($diff_time)/3600).'小时前';
        } else {
            return ceil(($diff_time)/86400).'天前';
        }

        $string .= '\u'.$v;

        $str = json_decode('"'.$string.'"');

        return $str;

    }
    /* 检测是否爬虫机器人 */
    public static function checkrobot($useragent = '')
    {
        static $kw_spiders = ['bot', 'crawl', 'spider', 'slurp', 'sohu-search', 'lycos', 'robozilla'];
        static $kw_browsers = ['msie', 'netscape', 'opera', 'konqueror', 'mozilla'];
        $useragent = strtolower(empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent);
        if(strpos($useragent, 'http://') === false && Helper::dstrpos($useragent, $kw_browsers)) return false;
        if(Helper::dstrpos($useragent, $kw_spiders)) return true;
        return false;
    }
    public static function dstrpos($string, $arr, $returnvalue = false) {
        if(empty($string)) return false;
        foreach((array)$arr as $v) {
            if(strpos($string, $v) !== false) {
                $return = $returnvalue ? $v : true;
                return $return;
            }
        }
        return false;
    }

    /*
     * 判断是否手机访问
     *
     * **/
    public static function isMobileDevice()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return TRUE;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;// 找不到为flase,否则为TRUE
        }
        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'mobile',
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return TRUE;
            }
        }
        if (isset ($_SERVER['HTTP_ACCEPT'])) { // 协议法，因为有可能不准确，放到最后判断
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    //随机生成小数的函数
    public static function randomFloat($min, $max)
    {
        return self::price($min + mt_rand() / mt_getrandmax() * ($max - $min));
    }

    /*
     * is_utf8
     * **/
    public static function isUtf8($str)
    {
        $len = strlen($str);
        for($i = 0; $i < $len; $i++){ $c = ord($str[$i]); if($c > 128){
            if(($c > 247)){
                return false;
            }elseif($c > 239){
                $bytes = 4;
            }elseif($c > 223){
                $bytes = 3;
            }elseif ($c > 191){
                $bytes = 2;
            }else{
                return false;
            }
            if(($i + $bytes) > $len){
                return false;
            }
            while($bytes > 1){
                $i++;
                $b = ord($str[$i]);
                if($b < 128 || $b > 191){
                    return false;
                }
                $bytes--;
            }
        }
        }
        return true;
    }

    /*
     * base_64位判断
     * **/
    public static function isBase64($str)
    {
        if(@preg_match('/^[0-9]*$/',$str) || @preg_match('/^[a-zA-Z]*$/',$str)){
            return false;
        }elseif(self::isUtf8(base64_decode($str)) && base64_decode($str) != ''){
            return true;
        }
        return false;
    }


}
