<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/9/14
 * Time: 10:11
 */

namespace App\Models;


class ContractModel extends BaseModel
{
    protected $table;
    protected $primaryKey = 'contract_id';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'contract';
    }

    /*
     * @param[merchantId] $merchantId 商户ID
     * @param[activityId] $activityId 活动ID
     * **/
    public function getContractInfo($merchantId,$activityId)
    {
        $result = $this->where('merchant_id',$merchantId)
                       ->where('activity_id',$activityId)
                       ->where('deleted',0)
                       ->first();

        return !empty($result) ? $result->toArray() : [];
    }



}