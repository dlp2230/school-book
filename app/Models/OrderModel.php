<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/9/14
 * Time: 10:11
 */

namespace App\Models;

use DB;
class OrderModel extends BaseModel
{
    protected $table;

    const PAYMENT_TYPE_FULL = 1;
    const PAYMENT_TYPE_DEPOSIT = 2;

    public static $paymentTypeMap = [
        self::PAYMENT_TYPE_FULL  =>'全款支付',
        self::PAYMENT_TYPE_DEPOSIT  =>'定金支付',
    ];
    protected $primaryKey = 'order_id';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'order';
    }

    /*
     * 获取商户展届数据
     * @param[activityId] $activityId
     * @param[merchantId] $merchantId
     * **/
    public function getMerchantActivityOrderNums($merchantId,$activityId = 0)
    {
        $query = DB::table($this->table)->where('deleted',0)
                                         ->where('order_type',1)
                                          ->where('merchant_id',$merchantId)
                                          ->where('status',1);
        if($activityId > 0){
            $query->where('activity_id',$activityId);
        }

        $result = $query->select(DB::raw('count(*) as num'),DB::raw('SUM(order_total) as order_total'),
                                DB::raw('SUM(order_deposit) as order_deposit'),
                                DB::raw('SUM(order_paid) as order_paid'))
                        ->first();

        return $result;

    }

    public function orderCoupons()
    {
        return $this->hasMany(OrderCouponModel::class,'order_id','order_id');
    }

    /*
     * 查询纸质订单号信息~
     * @param[orderSn] $orderSn 纸质订单号
     * @param[merchantId] $merchantId 商户ID
     * **/
    public function getOrderSnByMerchantInfo($orderSn,$merchantId,$activityId)
    {
        $result = $this->where('order_sn',$orderSn)
                       ->where('merchant_id',$merchantId)
                       ->where('activity_id',$activityId)
                       ->where('deleted',0)
                       ->where('status',0)
                       ->first();

        return !empty($result) ? $result->toArray() : [];

    }


}