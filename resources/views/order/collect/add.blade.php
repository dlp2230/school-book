@extends('layouts.master')
@section('title', 'dashboard')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                统一收银
                <small>创建家博会订单</small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body form-inline">
                            <form class="" id="form-1" action="{{url('user/ajax_user_info')}}" onsubmit="return false">
                                <div class="form-group">
                                    <label for="">关联用户：</label>
                                    <input type="text" name="keyword" value="<?php echo !empty($where['order_sn']) ? $where['order_sn'] : ''?>" class="form-control" placeholder="请输入用户手机号">
                                </div>
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <button class="btn btn-primary" id="search-btn" onclick="bindUser();return false;">检索</button>
                            </form>
                            <span class="grey" id="userinfo"></span>
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
                                @if(!empty($list))
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
        //检索用户
        function bindUser(){
            var _this = $("#form-1");
            var url = _this.attr("action");
            var data = _this.serializeArray();
            $.ajax({
                url:url,
                type:"post",
                data:data,
                success:function(data){
                   console.log(data)
                },
                error:function(e){
                    alert("错误！！");
                    //window.clearInterval(timer);
                }
            });

        }


    </script>
@endsection
