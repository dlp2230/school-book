<?php
/**
 * Created by PhpStorm.
 * User: denglixing
 * Date: 2018/9/26
 * Time: 13:49
 */
namespace App\Http\Service\SettlCenter;

use App\Models\OrderModel;
use App\Models\MerchantModel;
use App\Models\OrderGoodsModel;
use App\Models\OrderCouponModel;
use App\Models\GoodsModel;
use App\Models\UserModel;
use App\Models\ActivityModel;

use App\Http\Service\BaseService;
class SettlService extends BaseService
{
    //商户model
    protected $merchantModel;

    protected $orderModel;
    public function __construct()
    {
        parent::__construct();
        $this->merchantModel = new MerchantModel();
        $this->orderModel = new OrderModel();
    }


    /*
     * 统一收银结算 列表~
     * **/
    public function getUnifiedList()
    {
        $param = request()->all();
        $page = request('page', $this->page); /*获取开始*/
        $pageSize = request('pageSize', $this->pageSize); ///*获取条数*/
        $activityId = request("activity_id",0);

        $orderModel = new OrderModel();
        $orderModel = $orderModel->where('deleted',0)->where('order_type',1)->where('merchant_id', $this->merchantId);
        //帐号存在时~
        if($activityId > 0){
            $orderModel = $orderModel->where('activity_id',$activityId);
        }

        $count = $orderModel->count();
        $orderList = $orderModel->offset(($page-1)*$pageSize)->limit($pageSize)->get();
        //商品信息~
        $orderGoodsModel = new OrderGoodsModel();
        $orderCouponModel = new OrderCouponModel();
        //处理数据~
        if(!empty($orderList)){
            $orderList = $orderList->toArray();
            $orderIds = array_column($orderList,'order_id');
            $orderCounpons = $orderCouponModel->getOrderCouponByIds($orderIds);
            $orderGoods = $orderGoodsModel->getOrderGoodsByIds($orderIds); //订单商品信息~
            foreach($orderList as &$item){
                //商品信息~
                $item['goods_info'] = isset($orderGoods[$item['order_id']]) ? $orderGoods[$item['order_id']] : [];
                //商户优惠券~
                $item['supplier_coupon_val'] = isset($orderCounpons[$item['order_id']]) ? $orderCounpons[$item['order_id']]['coupon_val'] : 0;
                //现场退款~
            }
        }

        parent::initPagination($orderList, $count, $pageSize, $page, array('path' => url('/settl_center/settl/unified'), 'query' => $param));

        $orderData = $this->orderModel->getMerchantActivityOrderNums($this->merchantId,$activityId);
        $merchant = $this->merchantModel->getMerchantById($this->merchantId);

        //活动展届~
        $activityModel = new ActivityModel();
        $activitys = [];
        if($merchant){
            $activitys = $activityModel->getActivityByCityList($merchant['city_id']);
        }
        return [
            'list' => $orderList,
            'merchant' => $merchant,
            'order_data' =>$orderData,
            'activity_list'=>$activitys,
            'payment_type_map'=>orderModel::$paymentTypeMap,
        ];
    }



}