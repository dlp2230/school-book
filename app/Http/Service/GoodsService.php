<?php
/**
 * Created by PhpStorm.
 * User: denglixing
 * Date: 2018/9/26
 * Time: 13:49
 */
namespace App\Http\Service;

use App\Models\GoodsModel;
class GoodsService extends BaseService
{
    protected $goodsModel;
    public function __construct()
    {
        parent::__construct();
        $this->goodsModel = new GoodsModel();
    }

    //goods_list
    public function getGoodsList()
    {
        $param = request()->all();
        $goodsModel = new GoodsModel();
        $page = request('page', $this->page);
        $pageSize = request('pageSize', $this->pageSize);
        $goods_name = request("goods_name","");
        $goodsModel = $goodsModel->where('deleted',0)->where('status',1)->where('merchant_id',$this->merchantId);
        if (!empty($goods_name)) {
            $goodsModel = $goodsModel->where('goods_name','like','%'.$goods_name.'%');
        }

        $count = $goodsModel->count();
        $list = $goodsModel->offset(($page-1)*$pageSize)->limit($pageSize)->get();

        parent::initPagination($list, $count, $pageSize, $page, array('path' => url('/goods/list'), 'query' => $param));

        return $list;
    }

    //添加~
    public function add()
    {
        if(request()->isMethod('post')){
            $param = request()->all();
            $goodsName = $param['goods_name'];
            $goodsSn = $param['goods_sn'];
            $originPrice = $param['origin_price'];
            $salePrice = $param['sale_price'];
            $goodsImage = isset($param['goods_image']) ? $param['goods_image'] : [];
            if(empty($goodsName)){
                return $this->failJson("商品名称不能为空~");
            }
            if(empty($goodsSn)){
                return $this->failJson("商品编号不能为空~");
            }
            //验证商品编号~
            $goodsSnOne = $this->goodsModel->getGoodsSnInfo($goodsSn);

            if($goodsSnOne){
                return $this->failJson("该商品编号已存在~");
            }

            if($originPrice ==0){
                return $this->failJson("请输入正确的商品价格~");
            }
            if($salePrice ==0){
                return $this->failJson("请输入正确的市场价格~");
            }

            if($salePrice > $originPrice){
                return $this->failJson("活动价不能大于市场价~");
            }
            //封面~
            if(empty($goodsImage)){
                return $this->failJson("封面不能为空~");
            }
            $goodsModel = new GoodsModel();
            $goodsModel->merchant_id = $this->merchantId;
            $goodsModel->goods_sn = $goodsSn;
            $goodsModel->goods_name = $goodsName;
            $goodsModel->goods_image = $goodsImage[0];
            $goodsModel->origin_price = $originPrice;
            $goodsModel->sale_price = $salePrice;
            $goodsModel->status = 1;
            $goodsId = $goodsModel->save();
            if($goodsId){
                return $this->successJson("添加成功~");
            }
            return $this->failJson("添加失败~");

        }
        return $this->failJson("提交方式有误~");
    }

    //edit
    public function edit()
    {
        if(request()->isMethod('post')){
            $param = request()->all();
            $goodsName = $param['goods_name'];
            $goodsSn = $param['goods_sn'];
            $oldGoodsSn = $param['old_goods_sn'];
            $originPrice = $param['origin_price'];
            $salePrice = $param['sale_price'];
            $goodsImage = isset($param['goods_image']) ? $param['goods_image'] : [];
            if(empty($goodsName)){
                return $this->failJson("商品名称不能为空~");
            }
            if(empty($goodsSn)){
                return $this->failJson("商品编号不能为空~");
            }
            if($goodsSn != $oldGoodsSn){
                //验证商品编号~
                $goodsSnOne = $this->goodsModel->getGoodsSnInfo($goodsSn);

                if($goodsSnOne){
                    return $this->failJson("该商品编号已存在~");
                }
            }

            if($originPrice ==0){
                return $this->failJson("请输入正确的商品价格~");
            }
            if($salePrice ==0){
                return $this->failJson("请输入正确的市场价格~");
            }

            if($salePrice > $originPrice){
                return $this->failJson("活动价不能大于市场价~");
            }
            //封面~
            if(empty($goodsImage)){
                return $this->failJson("封面不能为空~");
            }
            unset($param['goods_image'],$param['old_goods_sn']);
            $param['goods_image'] = $goodsImage[0];
            $goodsModel = new GoodsModel();

            $res = $goodsModel->updateGoods($param);

            if($res == true){
                return $this->successJson("修改成功~");
            }
            return $this->failJson("修改失败~");

        }
        return $this->failJson("提交方式有误~");
    }

    //delete
    public function delete($id)
    {
        $goods = GoodsModel::find($id);
        if ($goods) {
            $goods->deleted = 1;
            if ($goods->save()) {
                return true;
            }
        }
        return false;
    }

    //商品详情信息~
    public function getGoodsById($goodsId)
    {
        $result = $this->goodsModel->getGoodsById($goodsId);

        return $result;
    }

}