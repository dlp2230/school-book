<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/11/8
 * Time: 10:11
 */

namespace App\Models;
use DB;
class OrderCouponModel extends BaseModel
{
    protected $table;
    protected $primaryKey = 'order_coupon_id';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'order_coupon';
    }

    /*
     * 优惠券订单数据
     * @param[orderIds] $orderIds 订单Ids
     * **/
    public function getOrderCouponByIds($orderIds = [])
    {
        $orderIds = implode(',',$orderIds);
            $sql = "SELECT
                        oc.*, uc.coupon_id,
                        c.merchant_id
                    FROM hx_order_coupon oc
                        LEFT JOIN hx_user_coupon uc ON uc.user_coupon_id = oc.user_coupon_id
                        LEFT JOIN hx_coupon c ON uc.coupon_id = c.coupon_id
                    WHERE oc.deleted = 0
                        AND c.merchant_id > 0
                    AND oc.order_id IN ({$orderIds})";

        $result = DB::select($sql);

        $data = [];
        if(!empty($result)){
            foreach($result as $item){
                $data[$item->order_id] = (array)$item;
            }
        }
        return $data;
    }


}