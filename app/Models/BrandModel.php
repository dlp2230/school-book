<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/9/14
 * Time: 10:11
 */

namespace App\Models;


class BrandModel extends BaseModel
{
    protected $table;
    protected $primaryKey = 'brand_id';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'brand';
    }

   /*
    * 获取商户品牌信息
    * @param[merchantId] $merchantId 商户ID
    * @param[brandId]   $brandId 品牌ID
    * **/
    public function getMerchantBrand($brandId)
    {
        $result = $this->where('brand_id',$brandId)->first();

        return !empty($result) ? $result->toArray() : [];

    }

}