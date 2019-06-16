<?php
/**
 * Created by PhpStorm.
 * User: denglixing
 * Date: 2018/9/26
 * Time: 13:46
 */
namespace App\Http\Service;

use Illuminate\Pagination\LengthAwarePaginator as Pagination;
use Illuminate\Support\Facades\Validator;

class BaseService
{
    //
    protected $page  = 1;
    protected $pageSize = 20;
    //系统时间
    protected $serverDate;
    //开始时间
    protected $startDate;
    //结束时间
    protected $endDate;

    protected $adminInfoKey = "adminUser";

    //用户信息
    protected $adminInfo;
    //用户信息
    protected $accountId;
    //商户信息
    protected $merchant;
    //商户ID
    protected $merchantId;

    public function __construct()
    {
        $this->serverDate = date("Y-m-d H:i:s");
        $this->startDate = date("Y-m-d H:i:s",strtotime(date("Y-m-d")) - 3600);//时间减1小时
        $this->endDate = date("Y-m-d",strtotime("+1 days"));

        $this->adminInfo = request()->session()->get($this->adminInfoKey);
        $this->merchant = $this->adminInfo;
        $this->merchantId = $this->adminInfo['merchant_id'];
        $this->accountId = $this->adminInfo['account_id'];
    }

    /**
     * 异常处理
     * @param null $errorMessage
     * @throws \Exception
     */
    protected function exceptionHandle($errorMessage = null){

        throw new \Exception($errorMessage ?: '系统异常,请稍后再试~~',-8000);

    }

    /**
     * 成功返回
     * @param string $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function successJson($msg = 'success',$data = [])
    {
        $res['status'] = 200;
        $res['msg'] = $msg;
        $res['data'] = $data;

        return response()->json($res);
    }

    /**
     * 错误返回
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function failJson($msg = 'error')
    {
        $res['status'] = 401;
        $res['msg'] = $msg;

        return response()->json($res);
    }

    /**
     * 分页
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


    /**
     * 通用插入更新处理
     * @param $rule
     * @param $message
     * @param $tableModel
     * @param $data
     * @param $type
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function baseInsertOrUpdate($rule , $message , $tableModel , $data , $type  , $id = null)
    {

        $validator = Validator::make($data,$rule,$message);

        if ($validator->fails()){
            return $this->failJson($validator->errors()->first());
        }

        switch ($type){
            case 'insert':
                $result = $tableModel->doAdd($data);
                break;
            case 'update':
                if (!$id){
                    return $this->failJson('参数缺失');
                }
                $result = $tableModel->doEdit($id , $data);
                break;
            default:
                $result['status'] = 500;
                break;
        }

        if ($result['status'] == 200){
            return $this->successJson($result['msg']);
        }

        return $this->failJson($result['msg']);
    }

}