<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/9/14
 * Time: 10:11
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Builder;
class GoodsModel extends BaseModel
{
    protected $table;
    protected $primaryKey = 'goods_id';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'goods';
    }

    //修改商品
    public function updateGoods($param)
    {
        $goodsId = $param['goods_id'];

        unset($param['goods_id']);
        try{
            $this->where('goods_id',$goodsId)->update($param);
            return true;
        }catch(Exception $e){
            return false;
        }

    }

    //获取商品信息
    public function getGoodsById($goodsId)
    {
        $result = $this->where('goods_id',$goodsId)->first();

        return !empty($result) ? $result->toArray() : [];
    }

    /*
     * 商户商品列表~
     * **/
    public function getGoodsMerchant($merchantId)
    {
        $result = $this->where('merchant_id',$merchantId)
                        ->where('deleted',0)
                        ->get();

        return !empty($result) ? $result->toArray() : [];
    }

    //商品信息是否存在~
    public function getGoodsSnInfo($goodsSn)
    {
        $result = $this->where('goods_sn',$goodsSn)->first();

        return $result;
    }

    /*
     * 商品信息
     * @param[goodsIds] $goodsIds  商品ID
     * @param[merchantId] $merchantId 商户ID
     * **/
    public function getGoodsByIds($goodsIds,$merchantId)
    {
        $result = $this->whereIn('goods_id',$goodsIds)
                       ->where('merchant_id',$merchantId)
                       ->where('status',1)
                       ->where('deleted',0)
                       ->get();

        return !empty($result) ? $result->toArray() : [];
    }

    /*
    * 模型的 [启动] 方法
    *
    * @return void
    * **/
    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::addGlobalScope('deleted',function(Builder $builder){
            $builder->where('deleted','=',0);
        });
    }

}