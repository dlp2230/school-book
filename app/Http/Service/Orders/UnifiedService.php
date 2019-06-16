<?php
/**
 * Created by PhpStorm.
 * User: denglixing
 * Date: 2018/9/26
 * Time: 13:49
 */
namespace App\Http\Service\Orders;

use App\Models\GoodsModel;
use App\Models\OrderGoodsModel;
use App\Models\OrderModel;
use App\Models\MerchantModel;
use App\Models\ActivityModel;
use App\Models\UserModel;

use App\Http\Service\BaseService;
//统一收银
class UnifiedService extends BaseService
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
     * 统一收银订单列表~
     * **/
     public function getUnifiedList()
     {
         $param = request()->all();
         $orderModel = new OrderModel();
         $page = request('page', $this->page); /*获取开始*/
         $pageSize = request('pageSize', $this->pageSize); ///*获取条数*/
         $serachType = request('serach_type');
         $keyword = request('keyword','');
         $couponType = request('coupon_type');
         $activityId = request('activity_id');
         $paymentType = request("payment_type",0);
         $goods_id = request('goods_id',0);

         $orderModel = $orderModel->where('deleted',0)->where('merchant_id', $this->merchantId);
         //查找类型
         $userModel = new UserModel();
         switch($serachType)
         {
             case 1: //订单编号
                 if(!empty($keyword)){
                     $orderModel = $orderModel->where('order_sn',$keyword);
                 }
                 break;
             case 3: //营销人员
                 break;
             case 4: //买家电话
                 if(!empty($keyword)){
                     $user = $userModel->getUserMobileByInfo($keyword);
                     if($user){
                         $orderModel = $orderModel->where('uid',$user['uid']);
                     }
                 }

                 break;
             default:
         }

         $orderGoodsModel = new OrderGoodsModel();
         if($goods_id > 0){
            $serachGoods = $orderGoodsModel->getOrderGoodsByGoodsId($goods_id);
            if(!empty($serachGoods)){
                $serachOrderIds = array_filter(array_unique(array_column($serachGoods,'order_id'))); //排除0
                $orderModel = $orderModel->whereIn('order_id',$serachOrderIds);
            }
         }

        //订单支付类型~
        switch($paymentType){
            case 0: //所有订单
                break;
            case -1: //未付款
                $orderModel = $orderModel->where('status',0);
                break;
            case 1: //已付全款
                $orderModel = $orderModel->where('status',1)->where('payment_type',1);
                break;
            case 2: //已下定金
                $orderModel = $orderModel->where('status',1)->where('payment_type',2);
                break;
            default:
        }

        //优惠券类型~
         switch($couponType){
             case 1: //商户优惠券
                 break;
             case 2: //平台优惠券~
                 break;
             default:
         }

         //活动ID
         if($activityId > 0){
             $orderModel = $orderModel->where('activity_id',$activityId);
         }

         $count = $orderModel->count();
         $orderList = $orderModel->offset(($page-1)*$pageSize)->limit($pageSize)->get();

         //商品信息~
         $goodsModel = new GoodsModel();
         $goodsList = $goodsModel->getGoodsMerchant($this->merchantId);
         //处理数据~
         $userModel = new UserModel();
         if(!empty($orderList)){
            $orderList = $orderList->toArray();
            $orderIds = array_column($orderList,'order_id');
            $uids = array_unique(array_column($orderList,'uid'));
            $userList = $userModel->getUserByIds($uids); //用户列表信息~
            $orderGoods = $orderGoodsModel->getOrderGoodsByIds($orderIds); //订单商品信息~
            foreach($orderList as &$item){
                $item['goods_info'] = isset($orderGoods[$item['order_id']]) ? $orderGoods[$item['order_id']] : [];
                $item['user_info'] = isset($userList[$item['uid']]) ? $userList[$item['uid']] : [];
            }
         }

         parent::initPagination($orderList, $count, $pageSize, $page, array('path' => url('/order/unified/list'), 'query' => $param));

         $merchant = $this->merchantModel->getMerchantById($this->merchantId);

         //活动展届~
         $activityModel = new ActivityModel();
         $activitys = [];
         if($merchant){
             $activitys = $activityModel->getActivityByCityList($merchant['city_id']);
         }

         //处理数据~
         return [
             'list'           =>$orderList,
             'merchant'      => $merchant,
             'serach_type'   => $this->serachType,
             'payment_types' => $this->paymentTypes,
             'coupon_types'  => $this->couponTypes,
             'activity_list' => $activitys,
             'goods_list'     => $goodsList,
         ];
     }

    /*
     * 得到用户的优惠信息
     * **/
    public function getOrderUserCoupons($mobile)
    {
        $userModel = new UserModel();
        $result =  $userModel->getUserMobileByInfo($mobile);
        //优惠券数量
        $result['coupon_count'] = $userModel->getUserCouponCount($result['uid']);

        return $result;
    }

}