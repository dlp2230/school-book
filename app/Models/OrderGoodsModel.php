<?php

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/9/14
 * Time: 10:11
 */

namespace App\Models;

use DB;
class OrderGoodsModel extends BaseModel
{
    protected $table;
    protected $primaryKey = 'order_goods_id';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->prefix . 'order_goods';
    }

    //订单商品
    public function getOrderGoodsByIds($orderIds)
    {
        $result = DB::table($this->table.' as og')
                    ->leftJoin('hx_goods as g',function($join){
                        $join->on('og.goods_id','=','g.goods_id');
                    })
                    ->whereIn('og.order_id',$orderIds)
                    ->select("g.*","og.order_id","og.num")
                   ->get();

        $data = [];
        if(!empty($result)){
            $result = $result->toArray();
            foreach($result as $key=>$item){
                if(isset($item->order_id)){
                    $data[$item->order_id][]  = (array)$item;
                }else{
                    $data[$item->order_id][0] = (array)$item;
                }
            }
        }

        return $data;
    }

    /*
     * 商品ID反查-订单~
     * @param[$goods_id]
     * **/
    public function getOrderGoodsByGoodsId($goodsId)
    {
         $result = $this->where('goods_id',$goodsId)->get();

        return !empty($result) ? $result->toArray() : [];
    }


}