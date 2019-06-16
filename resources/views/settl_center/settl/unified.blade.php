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
                                {{--<a class="btn btn-info" href="{{url('goods.add')}}">添加</a>--}}
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            {{--商户信息~--}}
            <div class="row">

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="admin-table" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>商户名称</th>
                                    <th>订单数</th>
                                    <th>全款订单结算</th>
                                    <th>定金订单结算</th>
                                    {{--<th>现场退款</th>--}}
                                    {{--<th>统一收银补贴</th>--}}
                                    {{--<th>商户优惠券</th>--}}
                                    <th>结算总额</th>
                                    {{--<th>订金抵扣</th>--}}

                                </tr>
                                </thead>
                                <tbody>
                                @if(empty($order_data))
                                    <tr>暂无数据</tr>
                                @else
                                        <tr>
                                            <td>{{$merchant['merchant_name']}}</td>
                                            <td>{{$order_data->num}}</td>
                                            <td>{{$order_data->order_total}}</td>
                                            <td>{{$order_data->order_deposit}}</td>
                                            <td>{{$order_data->order_paid}}</td>
                                        </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- /.box -->
                </div>
            </div>


            {{--订单列表等信息~--}}
             <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="admin-table" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>订单号</th>
                                    <th>订单状态</th>
                                    <th>商品名称</th>
                                    <th>全款订单</th>
                                    <th>定金订单</th>
                                    {{--<th>现场退款</th>--}}
                                    <th>商户优惠券</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if(empty($list))
                                    <tr>暂无数据</tr>
                                @else
                                    @foreach($list as $item)
                                        <tr>
                                            <td>{{$item['order_sn']}}</td>
                                            <td>
                                                <?php if($item['status'] == 1){?>
                                                    <?php echo isset($payment_type_map[$item['payment_type']]) ? $payment_type_map[$item['payment_type']] : $item['payment_type'];?>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php if(!empty($item['goods_info'])){?>
                                                    <?php foreach($item['goods_info'] as $goods){?>
                                                        <?php echo $goods['goods_name'].'<br>'?>
                                                    <?php }?>
                                                <?php }?>

                                            </td>

                                            <td>{{$item['order_total'] > 0 ? $item['order_total'] : '/'}}</td>
                                            <td>{{$item['order_deposit'] > 0 ? $item['order_deposit'] : '/'}}</td>
                                            <td>{{$item['supplier_coupon_val'] > 0 ? '-'.$item['supplier_coupon_val'] : '/' }}</td>
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


            <!-- /.col -->
            <!-- /.row -->
                 <div class="summary">
                     <div class="summary_top">结算小贴士</div>
                     <div class="summary_con">
                         <span class=" heading">一、全款订单结算</span>
                         <p>支付全款的统一收银订单，以订单的成交价为基础结算</p>
                         <span class=" heading">二、定金订单结算</span>
                         <p>支付定金的统一收银订单，以订单的定金额为基础结算</p>
                         <span class=" heading">三、结算额</span>
                         <p>结算额=全款订单结算+定金订单结算</p>
                     </div>
                 </div>
        </div>
        <!-- /.content -->



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
