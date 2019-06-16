@extends('layouts.master')
@section('title', 'dashboard')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                统一收银
                <small>收银列表</small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body form-inline">
                            <form class="" method="get" action="">

                                <div class="form-group">
                                    <select class="form-control select2"  name="serach_type" style="width: 120px">
                                        @if($serach_type ?? '')
                                            @foreach($serach_type as $key=>$val)
                                                <option value="{{$key}}" <?php echo (!empty($where['serach_type']) && $key==$where['serach_type']) ?'selected':'';?>>{{$val}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="text" name="keyword" value="<?php echo !empty($where['keyword']) ? $where['keyword'] : ''?>" class="form-control" placeholder="输入关键词">
                                </div>

                                <div class="form-group">
                                    <select class="form-control select2"  name="goods_id" style="width: 150px">
                                        <option value="0">所有商品</option>
                                        @foreach($goods_list as $key=>$val)
                                            <option value="{{$val['goods_id']}}" <?php echo (isset($where['goods_id']) && $where['goods_id'] == $val['goods_id']) ? 'selected':'';?>>{{$val['goods_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <select class="form-control select2"  name="payment_type" style="width: 150px">
                                        @if($payment_types ?? '')
                                            @foreach($payment_types as $key=>$val)
                                                <option value="{{$key}}">{{$val}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <select class="form-control select2"  name="coupon_type" style="width: 150px">
                                        <option value="0">所有类型</option>
                                        @foreach($coupon_types as $key=>$val)
                                            <option value="{{$key}}" <?php echo (isset($where['coupon_type']) && $where['coupon_type'] == $key) ? 'selected':'';?>>{{$val}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <select class="form-control select2"  name="activity_id" style="width: 150px">
                                        @if($activity_list ?? '')
                                            <option value="0">全部展届</option>
                                            @foreach($activity_list as $key=>$val)
                                                <option value="{{$key}}" <?php echo (isset($where['activity_id']) && $where['activity_id'] == $key) ? 'selected':'';?>>第{{$val['session']}}届</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <button class="btn btn-primary" id="search-btn">搜索</button>
                                <a class="btn btn-info" href="{{url('order/unified/create')}}">创建订单</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="admin-table" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>商品</th>
                                    <th>成交价</th>
                                    <th>优惠信息</th>
                                    <th>实付款</th>
                                    <th>抽奖成本</th>
                                    <th>收货人信息</th>
                                    <th>订单状态</th>
                                    <th>售后状态</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if(empty($list))
                                    <tr>暂无数据</tr>
                                @else
                                    @foreach($list as $item)
                                        <tr>
                                            <td>
                                                {{$item['order_sn']}} <br>
                                                <?php if(!empty($item['goods_info'])){?>
                                                   <?php foreach($item['goods_info'] as $goods){?>
                                                       <img src="<?php echo $goods['goods_image'].'?imageMogr2/auto-orient/thumbnail/!120x120r/gravity/Center/crop/120x120/'?>">
                                                        名称:<?php echo $goods['goods_name'].'<br>'?>
                                                        型号:<?php echo $goods['goods_sn'].'<br>'?>
                                                   <?php }?>
                                                <?php }?>

                                            </td>
                                            <td>{{$item['order_total']}}</td>
                                            <td>统一收银优惠￥{{$item['order_discount']}}</td>

                                            <td>{{$item['order_paid']}}</td>
                                            <td>/</td>
                                            <td>买家:
                                                <?php if(!empty($item['user_info'])){?>
                                                      <?php echo $item['user_info']['real_name'];?>
                                                      <?php echo $item['user_info']['mobile'];?>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php if($item['status'] == 1){?>
                                                <?php echo isset($payment_types[$item['payment_type']]) ? $payment_types[$item['payment_type']] : $item['payment_type'] ?>
                                                <?php }else{?>
                                                    <?php echo $item['payment_type'] == 1 ? '全款':'定金'?>
                                                <?php }?>

                                            </td>
                                            <td>商品无申请售后</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        {{ $page }}
                                <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>

            <!-- /.col -->
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection
@section('js')
    <script>
        $(function () {
            $('.select2').select2();

            search.getSearchContent([
                {'tagName':'input' ,'searchName':'category_name'},
                {'tagName':'select','searchName':'parent_id'}
            ])
        })
    </script>
@endsection
