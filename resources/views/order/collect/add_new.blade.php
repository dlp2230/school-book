@extends('layouts.master')
@section('title', $title)
@section('css')
    <link rel="stylesheet" href="/css/layui.css">
    <link rel="stylesheet" href="/css/order/add.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="create-order">
                <div class="bread_nav" style="font-size:12px;color:#666;">
                    <p>首页> 商户中心> 创建家博会订单</p>
                </div>
                <!-- 面包屑导航 -->
                <div class="main_con_in_tit clearfix" style="margin-top:20px;">
                    <div class="left_line"></div>
                    <span>创建家博会订单-基本信息</span>
                </div>
                <fieldset class="layui-elem-field" style="border:none;">
                    <div class="layui-field-box">
                        <div class="search-area">
                            <form class="layui-form layui-form-pane" action="" onsubmit="return false">
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="background:#fff;"><i class="required-star">*</i>&nbsp;关联用户</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="mobile" placeholder="请输入手机号码" autocomplete="off" class="layui-input" maxlength="11" />
                                    </div>
                                    <div class="layui-inline" style="float:left">
                                        <button type="button" class="layui-btn" onclick="search_user();"style="background:#db1621;font-size:12px;height:35px;line-height:35px;">检测手机号</button>
                                    </div>
                                    <div class="layui-form-mid layui-word-aux" id="search_user"></div>
                                </div>
                                <div class="layui-form-item" id="search_order_area">
                                    <label class="layui-form-label" style="background:#fff;"><i class="required-star">*</i>&nbsp;纸质订单</label>
                                    <div class="layui-input-inline">
                                        <div class="layui-form-select" id="search_order">
                                            <input type="text" name="order_num" readonly="readonly" placeholder="请选择订单号" autocomplete="off" class="layui-input">
                                            <i class="layui-edge"></i>
                                            <dl class="layui-anim layui-anim-upbit" id="order_area">
                                                <dt><input style="cursor:text;font-size:14px;" maxlength="4" type="text" name="search_order_num" placeholder="请输入纸质订单号后4位" maxlength="4" autocomplete="off" class="layui-input"></dt>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <blockquote class="layui-clear layui-elem-quote" id="check_goods" style="border-left:none;"></blockquote>
                        <form class="layui-form" action="" style="float:right;">
                            <div class="layui-form-item">
                                <label class="layui-form-label"></label>
                                <div class="layui-input-inline">
                                    <input type="text" name="keyword" placeholder="请输入商品名称 / 型号" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline" style="float:left">
                                    <button type="button" class="layui-btn" onclick="set_keyword(false);" style="background:#db1621;font-size:12px;">搜索</button>
                                    <button type="button" class="layui-btn layui-btn-danger" onclick="set_keyword(true);" style="background:#fff;border:none;color:#333;font-size: 12px;">重置</button>
                                </div>
                            </div>
                        </form>
                        <div class="layui-clear" id="goods_list"></div>
                        <button type="button" class="layui-btn layui-btn-big layui-btn-danger submit-order" onclick="submit_order();">提交订单</button>
                    </div>
                </fieldset>
            </div>
</section>
</div>

@endsection
@section('js')
<script>
    var type = 1; // 订单类型
    var orderFlag = true;
</script>
<script src="{{asset('js/order/add.js')}}"></script>
@endsection
