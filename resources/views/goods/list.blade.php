@extends('layouts.master')
@section('title', 'dashboard')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                展口入库
                <small>展品列表</small>
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
                                    <label for="">商品名称：</label>
                                    <input type="text" name="goods_name" value="<?php echo !empty($where['goods_name']) ? $where['goods_name'] : ''?>" class="form-control">
                                </div>

                                <button class="btn btn-primary" id="search-btn">搜索</button>
                                <a class="btn btn-info" href="{{url('goods/add')}}">添加</a>
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
                                    <th>展品名称</th>
                                    <th>型号</th>
                                    <th>市场价</th>
                                    <th>家博价</th>
                                    <th>操作</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if(empty($list))
                                    <tr>暂无数据</tr>
                                @else
                                    @foreach($list as $item)
                                        <tr>
                                            <td>{{$item['goods_name']}}</td>
                                            <td>{{$item['goods_sn']}}</td>
                                            <td>{{$item['origin_price']}}</td>

                                            <td>{{$item['sale_price']}}</td>
                                            <td>
                                                <a href="{{url('goods/edit',$item['goods_id'])}}">编辑</a>
                                                <a href="javascript:;" onclick="return false" class="btn btn-xs btn-danger tooltips" data-container="body" data-original-title="删除"  data-placement="top" id="destory"><i class="fa fa-trash"></i><form action="{{url('goods/delete/'.$item['goods_id'])}}" method="GET" name="delete_item" style="display:none"><input type="hidden" name="_method" value="delete"><input type="hidden" name="_token" value="{{csrf_token()}}"></form></a>
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
    <script type="text/javascript">
        $(document).on('click','#destory',function() {
            var _this = $(this);
            layer.msg('你确定要操作吗?', {
                time: 0, //不自动关闭
                btn: ['确定', '取消'],
                icon: 5,
                yes: function(index){
                    _this.find('form[name="delete_item"]').submit();
                    layer.close(index);
                }
            });
        });

    </script>
@endsection
