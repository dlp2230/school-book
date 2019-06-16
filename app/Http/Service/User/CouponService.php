<?php
/**
 * Created by PhpStorm.
 * User: denglixing
 * Date: 2018/9/30
 * Time: 16:14
 */
namespace App\Http\Service\User;

use App\Models\CouponModel;
use App\Models\BrandModel;
use App\Http\Service\BaseService;

class CouponService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * 获取优惠券
     * @param[uid] $uid 用户UID
     * @param[merchantId] 商户ID
     * @param[activityId] $activityId 活动ID
     * @param[brandId] $brandId 品牌ID
     * **/
    public function getUserCoupons($uid,$merchantId,$activityId,$brandId)
    {
        $coupons = [];

        $couponModel = new CouponModel();
        //商户优惠券~
        $merchantCoupons = $couponModel->getUserMerchantCoupons($uid,$merchantId,$activityId);

        //平台优惠券~
        $platformCoupon = $couponModel->getUserPlatformCoupon($uid,$activityId);
        if(!empty($platformCoupon)){
          foreach($platformCoupon as $k=>$item){
              $coupons['platform_coupons'][$k] = (array) $item;
          }
          unset($platformCoupon);
        }

        $brandModel = new BrandModel();
        //商户优惠券~
        if(!empty($merchantCoupons)){
            $brands = $brandModel->getMerchantBrand($brandId);
            foreach($merchantCoupons as $key=>$item){
                $item->brand_logo = isset($brands['brand_image']) ? $brands['brand_image'] : '';
                $item->brand_info = $brands;
                $coupons['merchant_coupons'][$key] = (array) $item;
            }
            unset($merchantCoupons);
        }

        return $coupons;
    }

    /*
    * 获取可使用的平台优惠券 && 商户优惠券
    * @param[uid] $uid 用户UID
    * @param[merchantId] 商户ID
    * @param[activityId] $activityId 活动ID
    * @param[brandId] $brandId 品牌ID
    * **/
    public function getUserCanCoupons($uid,$merchantId,$activityId,$brandId)
    {
        $coupons = [];

        $couponModel = new CouponModel();
        //商户优惠券~
        $merchantCoupons = $couponModel->getUserMerchantCoupons($uid,$merchantId,$activityId);

        //平台优惠券~
        $platformCoupon = $couponModel->getUserPlatformCoupon($uid,$activityId);

        $platform_coupons = [];
        $merchant_coupons = [];
        if(!empty($platformCoupon)){
            foreach($platformCoupon as $k=>$item){
                $platform_coupons[$k] = (array) $item;
            }
            unset($platformCoupon);
        }

        $brandModel = new BrandModel();
        //商户优惠券~
        if(!empty($merchantCoupons)){
            $brands = $brandModel->getMerchantBrand($brandId);
            foreach($merchantCoupons as $key=>$item){
                $item->brand_logo = isset($brands['brand_image']) ? $brands['brand_image'] : '';
                $item->brand_info = $brands;
                $merchant_coupons[$key] = (array) $item;
            }
            unset($merchantCoupons);
        }
        $coupons = array_merge($platform_coupons,$merchant_coupons);
        return $coupons;
    }

}