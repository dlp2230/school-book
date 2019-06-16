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
class NonUnityService extends BaseService
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

    /*
     * 非统一收银订单列表~
     * **/
    public function getOrderNonUnityList()
    {
        $param = request()->all();
        $orderModel = new OrderModel();
        $page = request('page', $this->page); /*获取开始*/
        $pageSize = request('pageSize', $this->pageSize); ///*获取条数*/
        $orderSn = request("order_sn","");
        $paymentType = request("payment_type",1);

        $orderModel = $orderModel->where('deleted',0)->where('merchant_id', $this->merchantId);
        $orderModel = $orderModel->where('payment_type',$paymentType);

        if (!empty($orderSn)) {
            $orderModel = $orderModel->where('order_sn',$orderSn);
        }

        $count = $orderModel->count();
        $orderList = $orderModel->offset(($page-1)*$pageSize)->limit($pageSize)->get();
        parent::initPagination($orderList, $count, $pageSize, $page, array('path' => url('/order/non_unity/list'), 'query' => $param));

        $merchant = $this->merchantModel->getMerchantById($this->merchantId);

        return [
            'list'=>$orderList,
            'merchant'=>$merchant,
        ];
    }

}