<?php

/**
 * Created by PhpStorm.
 * User: wy220
 * Date: 2018/9/6
 * Time: 14:53
 */
namespace App\Http\Controllers\Api;

//use lluminate\Support\Facades\Input;
use Illuminate\Support\Facades\Input;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

class UploadImg
{
    protected $accessKey;
    protected $secretKey;
    protected $bucketName;
    protected $img_url;

    public function __construct()
    {
        $this->accessKey  = config('api.accessKey');
        $this->secretKey  = config('api.secretKey');;
        $this->bucketName = config('api.bucketName');;
        $this->img_url    = config('api.img_url');;
    }

    public function uploadImg(){
        $upload_name = array_keys(Input::file())[0];
        $file = Input::file($upload_name);
        try{
            if ($file->isValid()){
                $auth = new Auth($this->accessKey, $this->secretKey);
                $token = $auth->uploadToken($this->bucketName);

                $filePath = $file->getRealPath();

                // 文件扩展名
                $entension = $file -> getClientOriginalExtension();

                //上传后保存的文件名
                $key = date('YmdHis').mt_rand(100,999).'.'.$entension;

                $uploadMgr = new UploadManager();

                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

                if ($err !== null) {
                    return ['code'=>500,'msg'=>$err];
                } else {
                    return ['code'=>200,'msg'=>'success','src'=>$this->img_url . $ret['key']];
                }
            }
            return ['code' => 500 , 'msg'=>'网络错误'];
        } catch (\Exception $e){
            return ['code'=>500,'msg'=>'上传异常'];
        }
    }
}