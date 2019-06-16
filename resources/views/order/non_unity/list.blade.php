@extends('layouts.master')
@section('title', 'dashboard')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                非统一收银
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
                                    <label for="">订单编号：</label>
                                    <input type="text" name="order_sn" value="<?php echo !empty($where['order_sn']) ? $where['order_sn'] : ''?>" class="form-control">
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label for="">支付类型：</label>
                                    <select class="form-control select2"  name="payment_type" style="width: 195px">
                                        @if($payment_types ?? '')
                                            @foreach($payment_types as $key=>$val)
                                                <option value="{{$key}}" <?php echo (isset($where['payment_type']) && $where['payment_type'] ==$key) ? 'selected':'';?>>{{$val}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <button class="btn btn-primary" id="search-btn">搜索</button>
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
                                    <th>商户</th>
                                    <th>订单号</th>
                                    <th>奖品</th>
                                    <th>定金</th>
                                    <th>总价</th>
                                    <th>抽奖成本</th>
                                    <th>订单状态</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if(empty($list))
                                    <tr>暂无数据</tr>
                                @else
                                    @foreach($list as $item)
                                        <tr>
                                            <td>{{$merchant['merchant_name']}}</td>
                                            <td>{{$item['order_sn']}}</td>
                                            <td></td>

                                            <td>{{$item['order_deposit']}}</td>
                                            <td>{{$item['order_total']}}</td>
                                            <td>/</td>
                                            <td>
                                                <?php if($item['status'] == 1){?>
                                                <?php echo isset($payment_types[$item['payment_type']]) ? $payment_types[$item['payment_type']] : $item['payment_type'] ?>
                                                <?php }else{?>
                                                    <?php echo $item['payment_type'] == 1 ? '全款':'定金'?>
                                                <?php }?>

                                            </td>
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
