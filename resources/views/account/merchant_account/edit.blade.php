@extends('layouts.master')
@section('title','后台用户列表    ')
@section('css')
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                子帐号列表
                <small>编辑</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box-body ">
                <form class="form-horizontal " id="edit_account" onsubmit="return false">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="row box-header with-border">
                                    <div class="col-sm-12">
                                        <h4 class="box-title">基本信息</h4>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-1">
                                            <label for="" class="col-sm-offset-8 pull-right">姓名<span style="color: red">*</span></label>
                                        </div>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="name" value="{{$result['name']}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-1">
                                            <label for="" class="col-sm-offset-8 pull-right">公司<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="company" maxlength="50" value="{{$result['company']}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-1">
                                            <label for="" class="col-sm-offset-8 pull-right">手机号<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="mobile" maxlength="15" value="{{$result['mobile']}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-1">
                                            <label for="" class="col-sm-offset-8 pull-right">邮箱<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" class="form-control" name="e_mail" maxlength="30" value="{{$result['e_mail']}}" required>
                                        </div>
                                    </div>
                                </div>



                                <input type="hidden" name="id" value="{{$id}}">
                                <table class="table table-bordered text-center row">
                                    <tbody>
                                    <tr>
                                        <div class="col-sm-6">
                                            <div class="col-sm-3">
                                                <td>
                                                    <button class="btn btn-block btn-primary btn-lg add_city">保存</button>
                                                </td>
                                            </div>
                                            <div class="col-sm-3">
                                                <td>
                                                    <button class="btn btn-block btn-default btn-lg" onclick="javascript :history.back(-1);">取消</button>
                                                </td>
                                            </div>
                                        </div>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>

                </form>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        //图片上传
        //base.uploadImgUse('banner-upload',1,'city_icon');
        //提交方法
        $('.add_city').click(function () {
            base.universalSubJump("{{url('account/doEdit')}}","{{url('account/list')}}","#edit_account");
        })


    </script>
@endsection
