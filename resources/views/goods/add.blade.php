@extends('layouts.master')
@section('title','后台用户列表    ')
@section('css')

@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商品列表
                <small>新增</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="row box-header with-border">
                            <div class="col-sm-12">
                                <h4 class="box-title">基本信息</h4>
                            </div>
                        </div>
                        <form id="add-form">
                            <div class="box-body">

                                <div class="row form-group">
                                    <div class="col-sm-8">
                                        <div class="col-sm-2">
                                            <label class="col-sm-offset-8 pull-right">展品名称:<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="goods_name" value="" placeholder="请输入展品名称~" required/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-8">
                                        <div class="col-sm-2">
                                            <label class="col-sm-offset-8 pull-right">展品型号:<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="goods_sn" maxlength="50" value="" placeholder="请输入1-26个字节~" required/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-8">
                                        <div class="col-sm-2">
                                            <label class="col-sm-offset-8 pull-right">市场价:<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" name="origin_price" value="0" min="0" placeholder="请输入8位内数字~" required/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-8">
                                        <div class="col-sm-2">
                                            <label class="col-sm-offset-8 pull-right">活动价:<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" name="sale_price" value="0" min="0" placeholder="请输入8位内数字~" required/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-8">
                                        <div class="col-sm-2">
                                            <label class="col-sm-offset-8 pull-right">橱窗图片:<span style="color: red">(图片尺寸大小500X280)</span></label>
                                        </div>
                                        <div class="upload-box col-sm-6" style="margin-top:5px;padding:0 15px;">
                                            <div class="image-box clear">
                                                <section class="upload-section">
                                                    <div class="upload-btn"></div>
                                                    <input type="file" name="banner_image" class="upload-input" id='banner-image' value=""/>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="col-md-6">
                                <button class="btn btn-primary pull-right" id="add-btn">提交</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-success" onClick="javascript:history.back()">返回</button>
                            </div>
                        </div>
                    </div>
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
        $('.select2').select2();
        //图片上传
        base.uploadImgUse('banner-image',1,'goods_image');

        base.dateConfig('.begin-date',true);
        base.dateConfig('.end-date',true);

        //提交方法
        $('#add-btn').click(function () {
            base.universalSubJump("{{url('goods/doAdd')}}","{{url('goods/list')}}","#add-form");
        })
    </script>

@endsection