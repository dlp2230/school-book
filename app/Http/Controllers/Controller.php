<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\LengthAwarePaginator as Pagination;
use Cache;
use Cookie;
use Auth;
use App\Models\MerchantModel;
use App\Models\ActivityModel;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 用户id
     *
     * @var int
     */
    protected $userId;

    /**
     * 用户信息
     *
     * @var array
     */
    protected $user = [];

    /**
     * 请求对象
     *
     * @var object
     */
    protected $request;

    protected $adminInfoKey = "adminUser";
    //用户信息
    protected $adminInfo;
    //用户信息
    protected $accountId = 0;
    //商户ID
    protected $merchantId = 0;

    //merchantModel
    protected $merchantModel;

    //商户信息
    protected $merchant;
    //活动信息
    protected $activity;
    //活动ID
    protected $activityId;

    //serverDate
    protected $serverDate;

    public function __construct()
    {
        $this->setHeaderCache(7200);
        $this->serverDate = date("Y-m-d H:i:s");
        // 请求对象
        $this->request = request();
        if(Auth::id() > 0){
            $this->user = Auth::user()->where('account_id',Auth::id())->first()->toArray();
            $this->merchantId =  $this->user['merchant_id'];
            $this->accountId  =  $this->user['account_id'];
        }

        $this->merchantModel = new MerchantModel();
        $this->initActivity();
        view()->share('title', "华夏家博商户中心后台");
    }

    /*
     * header cache
     * **/
    protected function setHeaderCache($offset = 3600)
    {
        header("Cache-Control: public");
        header("Pragma: cache");

        $ExpStr = "Expires: ".gmdate("D, d M Y H:i:s", time() + $offset)." GMT";
        header($ExpStr);
    }

    /*
     * 初始化展届信息
     * **/
    protected function initActivity()
    {
        $merchant = $this->merchantModel->getMerchantById($this->merchantId);
        $this->merchant = $merchant;
        $activityModel = new ActivityModel();
        if($merchant){
            $this->activity = $activityModel->getActivityCurrent($merchant['city_id'],$this->serverDate);
            $this->activityId = isset($this->activity['activity_id']) ? $this->activity['activity_id'] : 0;
        }
    }

    /**
     * 获取请求参数
     *
     * @param string $key
     * @param string $default 默认值
     * @return string
     */
    protected function requestInput($key = null, $default = null)
    {
        if ($key === null) {
            $requestInput = $this->request->all();
        } else {
            $requestInput = $this->request->input($key);
        }

        return empty($requestInput) ? (is_array($requestInput) ? [] : $default) : $requestInput;
    }

    //catch
    protected function exceptionHandle(\Exception $e){

        if(request()->ajax()){
            if($e->getCode()==-8000){
                $msg = $e->getMessage();
            }else{
                $msg = '系统异常请稍后再试~';
            }
            return response()->json(['status'=>500,'msg'=>$msg]);
        }else{
            if($e->getCode()==-8000){
                $msg = $e->getMessage();
            }else{
                $msg = '系统异常请稍后再试~';
            }

            return view('index.error',['msg'=>$msg]);
        }
    }

    //success
    protected function successJson($msg = 'ok!',$data = []){
        return response()->json([
            'status'    => 200,
            'msg'       => $msg ? $msg : 'success',
            'data'      => $data,
        ]);
    }

    //fail
    protected function failJson($msg = '系统异常~',$error_code = 0){

        return response()->json([
            'status'        => 400,
            'msg'           => $msg,
            'error_code'   => $error_code
        ]);
    }

    /*
     * success
     * **/
    protected function success($data = [])
    {
        return response()->json([
            'status'    => 1,
            'msg'       => 'success',
            'data'      => $data
        ]);
    }

    /*
     * fail
     * **/
    protected function fail($msg = '系统异常~')
    {
        return response()->json([
            'status'    => 0,
            'msg'       => $msg
        ]);
    }

    /**
     * page
     *
     * @param  [type] $list        [description]
     * @param  [type] $totalRows   [description]
     * @param  [type] $perPage     [description]
     * @param  [type] $currentPage [description]
     * @param  array  $options     [description]
     * @return [type]              [description]
     */
    protected function initPagination($list, $totalRows, $perPage, $currentPage = null, $options = [])
    {
        $page = new Pagination($list, $totalRows, $perPage, $currentPage, $options);

        view()->share('page', $page);
    }

}
