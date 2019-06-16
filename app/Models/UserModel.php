<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/9/14
 * Time: 10:11
 */

namespace App\Models;

use DB;
class UserModel extends BaseModel
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'user';
    }

    //用户信息
    public function getUserById($uid)
    {
        $result = $this->where('uid',$uid)->where('deleted',0)->first();

        return !empty($result) ? $result->toArray() : [];
    }

    /*
     * 用户信息
     * @param[mobile] $mobile 手机号码
     * @param[isRegister] $isRegister 是否注册 true or false
     * **/
    public function getUserMobileByInfo($mobile,$isRegister = false)
    {
        $result = $this->where('mobile',$mobile)->first();
        if(!empty($result)){
          return $result->toArray();
        }

        if(empty($result) && $isRegister == true){ //注册用户
           $user = new UserModel();
           $user->mobile = $mobile;
           $user->user_sn = date('Ymd').'00'.rand(100000,999999);
           $uid = $user->save();
           if($uid > 0){
               self::getUserMobileByInfo($mobile,true);
           }
        }

        return [];
    }

    //
    public function getUserByIds($uids)
    {
        $result = $this->whereIn('uid',$uids)->get();
        $data = [];
        if(!empty($result)){
            $result = $result->toArray();
            foreach($result as $item){
                $data[$item['uid']] = $item;
            }
        }

        return $data;
    }

    /*
     * 用户优惠券
     * **/
    public function getUserCouponCount($uid)
    {
        $now = date("Y-m-d H:i:s");
        $sql = "SELECT COUNT(*) AS num
                    FROM hx_user_coupon uc
                        LEFT JOIN hx_coupon c ON uc.coupon_id = c.coupon_id
                    WHERE uc.uid = {$uid}
                        AND uc.status = 0
                        AND c.end_date > '{$now}'";

        $result = DB::select($sql);

        return $result[0]->num;

    }

}