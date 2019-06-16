@extends('layouts.master')
@section('title', 'dashboard')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                同学录管理
                <small>同学录列表</small>
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
                                    <input type="text" name="name" value="<?php echo !empty($where['name']) ? $where['name'] : ''?>" class="form-control" placeholder="姓名~">
                                    <input type="text" name="mobile" value="<?php echo !empty($where['mobile']) ? $where['mobile'] : ''?>" class="form-control" placeholder="请输入手机号码~">
                                </div>

                                <button class="btn btn-primary" id="search-btn">搜索</button>
                                <a class="btn btn-info" href="{{url('account/add')}}">添加</a>
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
                                    <th>ID</th>
                                    <th>姓名</th>
                                    <th>公司</th>
                                    <th>手机号码</th>
                                    <th>邮箱</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(empty($list))
                                    <tr>暂无数据</tr>
                                @else
                                    @foreach($list as $item)
                                        <tr>
                                            <td> {{$item['id']}} </td>
                                            <td> {{$item['name']}}</td>
                                            <td>{{$item['company']}}</td>
                                            <td>{{$item['mobile']}}</td>
                                            <td>{{$item['e_mail']}}</td>
                                            <td>
                                                <a href="{{url('account/edit',$item['id'])}}">编辑</a>
                                                <a href="javascript:;" onclick="return false" class="delete" data-id="{{$item['id']}}" style="background: red;color: #ffffff">删除</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        {{ $list->render() }}
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

            $(document).on('click','.delete',function() {
                if (confirm("确定要删除吗？")) {
                    var id=$(this).attr("data-id")

                    var adminid=$(this).attr("id");
                    $.ajax({
                        url : "{{url('account/delete')}}",
                        data : {
                            'id':id,
                            '_token':"{{csrf_token()}}"

                        },
                        type : "POST",
                        success : function(result) {
                           alert('删除成功')
                            window.onload();
                        },
                        error : function(request) {
                            alert("删除失败!");
                        }
                    }, "json");
                }
            });

        })
    </script>
@endsection
