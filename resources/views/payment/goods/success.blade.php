@extends('layouts.master')
@section('title', $title)
@section('css')
    <link rel="stylesheet" href="/css/layui.css">
    <link rel="stylesheet" href="/css/payment/goods_success.css?v=20181114">
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <!-- 右边 -->
            <div class="main_con">
                <!-- 创建订单成功 -->
                <div class="success_box">
                    <div class="success_box_top">
                        <p><i><img src="<?php echo base_url('public/img/icon-7.png'); ?>"></i><?php if ($is_update == 1) { ?>恭喜您~订单修改成功~<?php } else { ?>恭喜您~订单创建成功~<?php } ?></p>
                    </div>
                    <dl class="clearfix">
                        <dt>订单号：</dt>
                        <dd><?php echo $order_data['order_num'];?></dd>
                    </dl>
                    <?php if ($order_data['pay_type'] == 1) { ?>
                    <dl class="clearfix">
                        <dt>定&nbsp;&nbsp;&nbsp;金：</dt>
                        <dd><?php echo (int)$order_data['earnest']? sprintf("%.2f",($order_data['earnest']+$order_data['jb_discount_money']+@$coupon_value)):sprintf("%.2f",$order_data['agreement_price']);?></dd>
                    </dl>
                    <?php } ?>
                    <dl class="clearfix">

                        <dt>成交价：</dt>
                        <dd><?php echo $order_data['agreement_price'];?></dd>
                    </dl>
                    <div class="layui-clear button-list">
                        <a onclick="go_same_user();" href="javascript:;" class="layui-btn">继续创建该用户的订单</a>
                        <?php if ($isCash) { ?>
                        <a href="<?php echo base_url('pay/order') . '?order_num=' . $order_data['order_num'] . '&is_cash=' . $is_cash;?>" class="layui-btn layui-btn-danger" id="go-pay">进入订单收银</a>
                        <?php } ?>
                        <a href="<?php echo base_url('create_order') . '?t=' . $type;?>" class="layui-btn layui-btn-primary">创建其他用户的订单</a>
                    </div>
                </div>
                <!-- 创建订单成功 -->
            </div>
        </section>
    </div>

@endsection
@section('js')
    <script>
        // 清空Cookie中保留的信息
        Cookie.deleteCookie("createOrderUserMobile");
        Cookie.deleteCookie("createOrderOrderNum");
        Cookie.deleteCookie("createOrderCheckGoodsIds");
        Cookie.deleteCookie("createOrderCheckGoods");

        //非统一收银取消收银按钮
        var is_cash = <?php echo $is_cash;?>;

        if (is_cash == 0) {
            console.log(is_cash);
            $("#go-pay").css({"display":"none"});
        }

        // 创建该用户的订单
        function go_same_user() {
            Cookie.setCookie("createOrderUserMobile", "<?php echo $order_data['buyer_tel']; ?>");

            window.location.href = baseUrl + "create_order?t=" + <?php echo $type; ?>;
        }
    </script>
    <?php if ($is_update == 1): ?>
    <script>
        $('.inactives').next().find('li').eq(0).removeClass('current');
        $('.inactives').next().find('li').eq(1).addClass('current');
    </script>
    <?php endif ?>
@endsection
