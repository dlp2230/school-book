<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/11/14
 * Time: 10:11
 */

namespace App\Models;

class UserAddressModel extends BaseModel
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'user_address';
    }

    /*
     * 获取用户地址~
     * @param[uid] $uid 用户UID
     * **/
    public function getUserAddressList($uid)
    {
        $result = $this->where('uid',$uid)->where('deleted',0)->orderBy('is_default','DESC')->get();

        return !empty($result) ? $result->toArray() : [];
    }



}