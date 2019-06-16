<?php
/**
 * Created by PhpStorm.
 * User: denglixing
 * Date: 2018/9/30
 * Time: 15:49
 */
namespace App\Http\Controllers\User;

use App\Models\UserModel;
use App\Http\Service\User\UserService;
use App\Http\Service\User\CouponService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $couponService;
    //init
    public function __construct()
    {
        parent::__construct();
        $this->couponService = new CouponService();
    }

    /*
     * 获取用户信息~
     * @param[mobile] $mobile 手机号
     * **/
    public function ajaxUserInfo()
    {
        $mobile = parent::requestInput('mobile');
        if($mobile == "" || strlen($mobile) != 11){
            return $this->fail("请输入正确的手机号码~");
        }

        $userModel = new UserModel();
        $result =  $userModel->getUserMobileByInfo($mobile);

        return $this->success($result);
    }

    /*
     * 获取用户优惠券
     * @param[uid] $uid 用户Id
     * **/
    public function getUserCoupons()
    {
        $uid = parent::requestInput('uid');
        if(empty($uid)){
           return $this->fail('用户UID不能为空~');
        }
        //优惠券相关信息
        $coupons = $this->couponService->getUserCoupons($uid,$this->merchantId,$this->activityId,$this->merchant['brand_id']);
        return view('user.coupons',[
            'coupons'=>$coupons,
            'supplier_count'=>isset($coupons['merchant_coupons']) ? count($coupons['merchant_coupons']) : 0,
            'common_count' =>isset($coupons['platform_coupons']) ? count($coupons['platform_coupons']) : 0,
        ]);

    }


}