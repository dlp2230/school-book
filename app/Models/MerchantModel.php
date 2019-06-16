<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/9/14
 * Time: 10:11
 */

namespace App\Models;


class MerchantModel extends BaseModel
{
    protected $table;
    protected $primaryKey = 'merchant_id';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'merchant';
    }

    //获取商户信息~
    public function getMerchantById($merchantId)
    {
        $result = $this->where('merchant_id',$merchantId)->first();

        return !empty($result) ? $result->toArray() : [];
    }

}