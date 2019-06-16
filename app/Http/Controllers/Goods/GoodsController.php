<?php

namespace App\Http\Controllers\Goods;

use App\Http\Service\OrderService;
use App\Http\Service\GoodsService;
use App\Models\GoodsModel;
use App\Http\Controllers\Controller;
class GoodsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GoodsService $goodsService)
    {
        $param = parent::requestInput();
        $result = $goodsService->getGoodsList();

        $viewData = [];
        $viewData['where'] = $param;
        $viewData['list'] = $result;
        return view('goods.list',$viewData);
    }

    /*
     * add
     * **/
    public function add()
    {
        return view('goods.add');
    }

    //执行添加~
    public function doAdd(GoodsService $goodsService)
    {
        $res = $goodsService->add();

        return $res;
    }

    //edit
    public function edit($goodsId,GoodsService $goodsService)
    {
        $result = $goodsService->getGoodsById($goodsId);
        if(empty($result)){
            return $this->failJson("数据不存在~");
        }
        $viewData['result'] = $result;
        $viewData['goods_id'] = $goodsId;
        return view('goods.edit',$viewData);
    }

    //doEdit
    public function doEdit(GoodsService $goodsService)
    {
        $result = $goodsService->edit();

        return $result;
    }

    //delete
    public function delete($goodsId,GoodsService $goodsService)
    {
        $result = $goodsService->delete($goodsId);
        if($result == true){
            return redirect('goods/list');
        }
        return $this->failJson("删除失败~");
    }

    /*
     * 订单商品列表
     * @date 2018-11-10 21:11
     * @param[keyword] $keyword 搜索条件~
     * @param[type]  $type 商品类型
     * @param[mobile] $mobile 手机号码~
     * **/
    public function searchGoods($page = 0)
    {
        $type = parent::requestInput('type'); // 商品 or 特惠
        $mobile = parent::requestInput('mobile');
        $goods_name = parent::requestInput('keyword');

        $goodsModel = new GoodsModel();


        $goodsModel = $goodsModel->where('deleted',0)->where('status',1)->where('merchant_id',$this->merchantId);
        if (!empty($goods_name)) {
            $goodsModel = $goodsModel->where('goods_name','like','%'.$goods_name.'%');
        }
        $count = $goodsModel->count();
        // 分页配置
        $pageSize = 5;
        $total_pages = ceil($count/$pageSize);
        $page = $page ? $page : 1;
        if (!is_numeric($page) || $page < 1) $page = 1;

        if ($page > $total_pages) $page = $total_pages;

        $offset = $page * $pageSize - $pageSize;

        $list = $goodsModel->offset(($page-1)*$pageSize)->limit($pageSize)->get();
        $list = !empty($list) ? $list->toArray() : [];
        // 返回数据至页面
        $viewData['keyword'] = $goods_name;
        $viewData['type'] = $type;
        $viewData['count'] = $count;
        $viewData['total_pages'] = $total_pages;
        $viewData['page'] = $page;
        $viewData['list'] = $list;

        return view('order.collect.add.search_goods',$viewData);

    }

}
