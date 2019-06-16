<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/11/8
 * Time: 10:11
 */

namespace App\Models;
use DB;
class CouponModel extends BaseModel
{
    protected $table;
    protected $primaryKey = 'coupon_id';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'coupon';
    }

    /*
     * 用户领取通用券(平台券)
     * @param[uid] $uid 用户UID
     * @param[activityId] 活动ID
     * **/
    public function getUserMerchantCoupons($uid,$merchant_id = 0,$activityId = 0,$limit = 0)
    {
        $now = date("Y-m-d H:i:s");
        $sql = "SELECT c.*
                    FROM hx_user_coupon uc
                        LEFT JOIN hx_coupon c ON uc.coupon_id = c.coupon_id
                    WHERE uc.uid = $uid
                        AND c.merchant_id = $merchant_id
                        AND c.activity_id = $activityId
                        AND c.end_date > '{$now}'
                        AND c.begin_date < '{$now}'
                        AND uc.`status` = 0";
        if($limit > 0){
            $sql .=" limit $limit";
        }
        $result = DB::select($sql);

        return $result;
    }

    /*
     * 平台优惠券
     * @param[uid] 用户ID
     * @param[activityId] $activityId 活动ID
     * **/
    public function getUserPlatformCoupon($uid,$activityId,$limit = 0)
    {
        $now = date("Y-m-d H:i:s");
        $sql = "SELECT c.*,uc.user_coupon_id as coupon_get_id
                    FROM hx_user_coupon uc
                        LEFT JOIN hx_coupon c ON uc.coupon_id = c.coupon_id
                    WHERE uc.uid = $uid
                        AND c.merchant_id = 0
                        AND c.activity_id = $activityId
                        AND c.end_date > '{$now}'
                        AND c.begin_date < '{$now}'
                        AND uc.`status` = 0";
        if($limit > 0){
            $sql .= " limit $limit";
        }

        $result = DB::select($sql);

        return $result;
    }


}