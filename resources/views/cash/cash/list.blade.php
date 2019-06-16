@extends('layouts.master')
@section('title', 'dashboard')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                收银概览
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
                                    <label for="">开始时间：</label>
                                    <input type="text" name="start_date" class="form-control start_date" value="<?php echo !empty($where['start_date']) ? $where['start_date'] : '' ?>" id="start_date" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="">结束时间：</label>
                                    <input type="text" name="end_date" class="form-control end_date" value="<?php echo !empty($where['end_date']) ? $where['end_date'] : '' ?>" id="$end_date" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="">收银员：</label>
                                    <select class="form-control" name="merchant_id" id="merchant_id">
                                        <option value="0">全部</option>
                                        {{--@if($city_id != '' || $city_id != 0)--}}
                                            {{--@foreach($activityList as $item)--}}
                                                {{--<option value="{{$item['activity_id']}}" {{$activity_id != '' && $activity_id == $item['activity_id'] ? 'selected' : '' }}>第{{$item['session']}}届</option>--}}
                                            {{--@endforeach--}}
                                        {{--@endif--}}

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">订单号：</label>
                                    <input type="text" name="order_sn" value="<?php echo !empty($where['order_sn']) ? $where['order_sn'] : ''?>" class="form-control">
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
                                    <th>日期</th>
                                    <th>订单号</th>
                                    <th>应收</th>
                                    <th>实收</th>
                                    <th>收银账号</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if(empty($list))
                                    <tr>暂无数据</tr>
                                @else
                                    @foreach($list as $item)
                                        <tr>
                                            <td>{{$item['pay_time']}}</td>
                                            <td>{{$item['order_sn']}}</td>
                                            <td>{{$item['order_total']}}</td>

                                            <td>{{$item['order_paid']}}</td>
                                            <td>{{$user['real_name']}}</td>
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
    <script type="text/javascript">
        base.baseJeDate('.start_date');
        base.baseJeDate('.end_date');
    </script>
@endsection
