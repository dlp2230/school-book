<?php
/**
 * Created by PhpStorm.
 * User: denglixing
 * Date: 2018/9/26
 * Time: 13:49
 */
namespace App\Http\Service\Orders;

use App\Models\OrderModel;
use App\Models\MerchantModel;

use App\Http\Service\BaseService;
class OrderService extends BaseService
{
    //查找类型~
    protected $serachType = [1 => '订单编号', 3 => '营销人员', 4 => '买家电话',];

    //支付订单~
    protected $paymentTypes = [0 => '所有订单', -1 => '未付款', 2 => '已下定金', 1 => '已付全款',];

    //优惠券~
    protected $couponTypes = [1 => '商户优惠券', 2 => '平台优惠券',];

    //订单model
    protected $orderModel;

    //商户model
    protected $merchantModel;
    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new OrderModel();
        $this->merchantModel = new MerchantModel();
    }

    //订单列表
    public function getOrderList()
    {
        $param = request()->all();
        $orderModel = new OrderModel();
        $page = request('page', $this->page); /*获取开始*/
        $pageSize = request('pageSize', $this->pageSize); ///*获取条数*/
        $orderSn = request("order_sn","");
        $account_id = request("account_id",0);
        $start_date = request('start_date');
        $end_date = request('end_date');
        $orderModel = $orderModel->where('deleted',0)->where('merchant_id', $this->merchantId);

        if(!empty($start_date)){
            $orderModel = $orderModel->where('pay_time','>=',$start_date);
        }

        if(!empty($end_date)){
            $orderModel = $orderModel->where('pay_time','<=',$end_date);
        }
        //帐号存在时~
        if($account_id > 0){
            $orderModel = $orderModel->where('account_id',$account_id);
        }
        if (!empty($orderSn)) {
            $orderModel = $orderModel->where('order_sn',$orderSn);
        }


        $count = $orderModel->count();
        $orderList = $orderModel->offset(($page-1)*$pageSize)->limit($pageSize)->get();

        parent::initPagination($orderList, $count, $pageSize, $page, array('path' => url('/cash/list'), 'query' => $param));

        return $orderList;
    }


}