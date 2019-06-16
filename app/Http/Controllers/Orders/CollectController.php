<?php

namespace App\Http\Controllers\Orders;

use App\Http\Service\Orders\UnifiedService;
use App\Models\OrderModel;
use App\Models\GoodsModel;
use App\Models\ContractModel;
use App\Http\Service\User\UserService;
use App\Http\Service\User\CouponService;
use App\Http\Controllers\Controller;
use App\Models\UserModel;

class CollectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UnifiedService $unifiedService)
    {
        $param = parent::requestInput();

        $viewData = $unifiedService->getUnifiedList();
        $viewData['where'] = $param;

        return view('order.collect.list',$viewData);
    }

    /*
     * 创建订单
     * goods
     * **/
    public function create()
    {
        return view('order.collect.add_new',[
            'title' => '创建订单',
            'type' => 1,
        ]);
    }

    /*
     * 订单用户信息
     * @param[mobile] $mobile 手机号码
     * **/
    public function ajaxOrderUserCoupons(UnifiedService $unifiedService)
    {
        $mobile = parent::requestInput('mobile');
        if($mobile == "" || strlen($mobile) != 11){
            return $this->fail("请输入正确的手机号码~");
        }

        $result =  $unifiedService->getOrderUserCoupons($mobile);

        return $this->success($result);
    }

    /*
     * 查询订单号是否存在~
     * @param[order_num] $order_num 订单号后缀
     * **/
    public function searchOrderNum()
    {
        $orderNum = parent::requestInput('order_num');
        if(empty($orderNum)){
            return $this->fail("订单号不能为空~");
        }

        // 创建一个查询构造器
        $builder = OrderModel::query()
            ->where('merchant_id',$this->merchantId)
            ->where('activity_id',$this->activityId)
            ->where('deleted',0)
            ->where('status', 0);

        $builder->where(function ($query) use ($orderNum) {
            $query->where('order_sn','like', '%'.$orderNum);
        });

        $orders = $builder->get();

        return $this->success(!empty($orders) ? $orders->toArray() : []);

    }

    /*
     * 验证订单
     * **/
    public function ajaxCreateCheck()
    {
        if(!$this->request->isMethod("post")){
            return $this->fail("请求方式有误~");
        }

        $type = parent::requestInput('type');
        $mobile = parent::requestInput('mobile');
        $orderNum = parent::requestInput('order_num');
        $goodsIds = parent::requestInput('goods_ids');
        //验证用户
        $userService = new UserService();
        $user = $userService->getUserInfo($mobile);
        if(empty($user)){
            return $this->fail("未找到用户信息，请更换手机号码并检索~");
        }
        //验证纸质订单号是否可使用~
        $orderModel =  new OrderModel();
        $orderSn = $orderModel->getOrderSnByMerchantInfo($orderNum,$this->merchantId,$this->activityId);
        if(empty($orderSn)){
            return $this->fail("所选纸质订单不可用~");
        }
        //验证展品信息是否可使用~
        $goodsModel = new GoodsModel();
        $goodsIds = json_decode($goodsIds, true);
        $goods = $goodsModel->getGoodsByIds($goodsIds,$this->merchantId);
        if(empty($goods)){
            return $this->fail("您选择的商品可能已被删除，请重新选择商品~");
        }

        return $this->success([]);

    }

    /*
     * 订单详情页
     * **/
    public function detail()
    {
        $param = parent::requestInput();
        $type = parent::requestInput('t');
        $mobile =parent::requestInput('mobile');
        $userId = parent::requestInput('user_id');
        $orderNum = parent::requestInput('order_num');
        $goodsIds = parent::requestInput('goods_ids');

        $goodsIds = json_decode($goodsIds, true);

        $userModel = new UserModel();
        $user = $userModel->getUserMobileByInfo($mobile);
        if (empty($goodsIds) || empty($userId)) {
            return $this->fail("参数错误~");
        }
        if(empty($user)){
          return $this->fail("没有找到用户信息~");
        }

        //验证展品信息是否可使用~
        $goodsModel = new GoodsModel();
        $goodsList = $goodsModel->getGoodsByIds($goodsIds,$this->merchantId);
        if(empty($goodsList)){
            return $this->fail("您选择的商品可能已被删除，请重新选择商品~");
        }

        //判断是否可以使用商户优惠券~
        $couponService = new CouponService();
        // 获取用户优惠券信息
        $coupon_list = $couponService->getUserCanCoupons($userId,$this->merchantId,$this->activityId,$this->merchant['brand_id']);

        $contractModel = new ContractModel();
        $contract =  $contractModel->getContractInfo($this->merchantId,$this->activityId); //商户合同~
        if($contract['payment_type'] == 2){ //统一收银~
            $discount_rate = 1 - ($contract['assume_rate'] + $contract['jb_assume_rate']) * 0.01;
        }else{
            $discount_rate = 1;
            $contract['assume_rate'] = 0.00;
        }

        //商户合同信息！

        $viewData['supplier_contract'] =$contract;
        $viewData['discount_rate'] =$discount_rate;
        $viewData['coupon_data'] = $coupon_list;
        $viewData['cash_order_total_money'] = 3000;
        $viewData['cash_order_paid_money'] = 200;
        $viewData['user'] = $user;
        $viewData['goods_list'] = $goodsList;
        $viewData['goods_ids'] = $goodsIds;
        $viewData['type'] = $type;
        $viewData['order_num'] = $orderNum;
        $viewData['pageTitle'] = $type == 1 ? '创建家居优品会订单' : '创建家博会订单';
        $viewData['active'] = 'create_order';

        return view('order.collect.detail',$viewData);

    }

    /*
     * 添加地址
     * **/
    public function addAddress()
    {
        $uid = parent::requestInput('uid');
        if(empty($uid)){
            return $this->fail("用户信息有误~");
        }
    }

    /*
     * 修改地址
     * **/
    public function editAddress()
    {
        $uid = parent::requestInput('uid');
        if(empty($uid)){
            return $this->fail("用户信息有误~");
        }
    }

    /*
     * 收货地址~
     * @param[uid] $uid 用户ID
     * @date 2018-11-14
     * @author denglixing
     * **/
    public function addressList()
    {
        $uid = parent::requestInput('uid');
        if(empty($uid)){
            return $this->fail("用户信息有误~");
        }

        $userService = new UserService();
        $result = $userService->getUserAddressList($uid);

        return view('user.address_list',[
            'address_list'=>$result
        ]);

    }

    /*
     * 执行创建订单~
     *
     * **/
    public function doCreateOrder()
    {
        $param = parent::requestInput();
        $goodsIds = parent::requestInput('goods_id');
        $goodsNum = parent::requestInput('goods_num');
        $originPrice = parent::requestInput('origin_price'); //商品原价
        $agreementPrice = parent::requestInput('agreement_price'); //协商价格
        $payType = parent::requestInput('pay_type'); //支付类型  订单支付类型：1=全款支付订单，2=预付定金订单
        $earnestPrice = parent::requestInput('earnest_price'); //定金支付金额~
        $score = parent::requestInput('score'); //积分~
        $supplierCouponId = parent::requestInput('supplier_coupon_id'); //商户优惠券ID
        $commonCouponId = parent::requestInput('common_coupon_id'); //平台优惠券~
        $addressId  = parent::requestInput('address_id'); //地址ID
        $userId  = parent::requestInput('user_id');  //用户UID
        $orderNum = parent::requestInput('order_num');  //订单号

        // 获取用户信息
        $userService =  new UserService();
        $user = $userService->getUserById($userId);
        if(empty($user)){
            $this->fail('未找到用户信息~');
        }

        //价格计算
        //平台优惠券
        //商户优惠券
        //积分~
        //订单号判断~

    }

    /*
     * 创建订单成功~
     * **/
    public function createOrderSuccess()
    {

    }


}
