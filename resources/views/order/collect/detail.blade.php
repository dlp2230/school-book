@extends('layouts.master')
@section('title', $title)
@section('css')
    <link rel="stylesheet" href="/css/layui.css">
    <link rel="stylesheet" href="/css/order/detail.css?v=20181113">
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="create-order-detail">
                <p>首页> 商户中心> 创建家博会订单</p>
                <fieldset class="layui-elem-field" style="border:none;">

                    <div class="layui-field-box">
                        <?php if (!empty($error)) { ?>
                <?php echo $error; ?>
            <?php } else { ?>
                        <form class="layui-form layui-form-pane">
                            <div id="address_area" style="display:none;"></div>
                            <div class="goods-list" id="goods_area">
                                <div style="font-style: normal;color: #d70c18;font-size: 12px;text-align:left;"><em style="font-style:normal;">请再次向用户确认手机号：<?php echo $user['mobile']; ?></em></div>
                                <table class="layui-table" lay-skin="line" style="font-family:'宋体';">
                                    <colgroup>
                                        <col width="35%">
                                        <col width="20%">
                                        <col width="25%">
                                        <col width="20%">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>商品信息</th>
                                        <th>数量</th>
                                        <th>协议单价</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($goods_list as $k => $v) { ?>
                                    <tr class="goods-line"  id="goods_line<?php echo $v['goods_id']; ?>">
                                        <td>
                                            <dl class="goods-info">
                                                <dt>
                                                    <img height="67" width="67" src="<?php echo $v['goods_image'] ? $v['goods_image'] : '/public/images/sg-def.png'; ?>" alt="<?php echo $v['goods_name']; ?>" /></dt>
                                                <dd>
                                                    <p title="<?php echo $v['goods_name']; ?>">名称：<?php echo $v['goods_name']; ?></p>
                                                    <p title="<?php echo $v['goods_sn']; ?>">型号：<?php echo $v['goods_sn']; ?></p>
                                                </dd>
                                            </dl>
                                            <input type="hidden" name="goods_id[]" value="<?php echo $v['goods_id']; ?>"/>
                                        </td>
                                        <td>
                                            <div class="layui-clear goods-num">
                                                <span class="down" onclick="set_goods_num(this, false, <?php echo $v['goods_id']; ?>, 'false');">-</span>
                                                <input onkeyup="change_goods_num(this, <?php echo $v['goods_id']; ?>,'false')" value="1" name="goods_num[]"id="goods_num_<?php echo $v['goods_id']?>" autocomplete="off" type="text" class="layui-input number" />
                                                <span class="up" onclick="set_goods_num(this, true, <?php echo $v['goods_id']; ?>, 'false');">+</span>

                                            </div>
                                            <br />
                                            <p class="goods_num_<?php echo $v['goods_id']; ?>" style="color:#ff0000;display: none;"></p>
                                        </td>
                                        <td>
                                            <div class="layui-clear goods-price">
                                                <i>¥</i>
                                                <input onkeyup="set_price()"  onblur="change_goods_price(this);" class="layui-input price" type="text" value="<?php echo $v['origin_price']; ?>" autocomplete="off" name="origin_price[]"  style="color:#d70c18;" /><span class="price-unit">元</span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="javaScript:void(0);" onclick="del_goods('<?php echo $v['goods_id']; ?>');"><i class="layui-icon del-icon">&#xe640;</i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                            <div class="float_left" style="width:50%;float:left;">
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="50%">
                                        <col width="50%">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th colspan="2">消费者优惠与实付</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>成交价/定金</td><td>￥<span id="cost_user_money">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>家博折扣</td><td>￥<span id="cost_user_discount_money">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>商户优惠券</td><td>￥<span id="cost_user_supplier_coupon_money">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>平台优惠券&nbsp;<img src="{{asset('img/q.png')}}" width="20" height="20" onclick="pingtai_alert()" /></td><td>￥<span id="cost_user_common_coupon_money">0.00</span></td>
                                    </tr>
                                    <tr class="user_prerogative_class">
                                        <td>订金抵扣活动</td><td>￥<span id="cost_user_prerogative_money">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>积分抵扣</td><td>￥<span id="cost_user_score_money">0.00</span></td>
                                    </tr>
                                    <tr class="is_show_draw">
                                        <td colspan="2"><p class="hot">首单付款成功，可至抽奖中心抽取现金大奖</p></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table class="layui-table">
                                    <colgroup>
                                        <col width="50%">
                                        <col width="50%">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th colspan="2">商户成本与实收</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>成交价/定金</td><td>￥<span id="cost_supplier_money">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>家博折扣</td><td>￥<span id="cost_supplier_discount_money">0.00</span></td>
                                    </tr>
                                    <tr class="supplier_prerogative_class">
                                        <td>订金抵扣活动</td><td>￥<span id="cost_supplier_prerogative_money">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>商户优惠券</td><td>￥<span id="cost_supplier_supplier_coupon_money">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p class="hot">（即时结算收款仅供参考，具体以结算系统为准）</p></td>
                                    </tr>
                                    </tbody>
                                </table>




                            </div>
                            <div class="order-detail" style="float:right;width:50%;">
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="border:none;background:#fff;">总价：</label>
                                    <div class="layui-input-inline clearfix">
                                        <span class="order-price" id="total_price" style="text-align:left; display:inline-block; float:left;"></span>
                                        <div class="price-unit" style="float:left;color:#666;margin-top:3px;">&nbsp;元</div>
                                    </div>

                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="border:none;background:#fff;">协商价：</label>
                                    <div class="layui-input-inline" style="width:170px;">
                                        <input type="text" onblur="check_money()" name="agreement_price" value="" placeholder="请输入成交价" autocomplete="off" class="layui-input order-price" />
                                    </div>
                                    <div class="layui-form-mid layui-word-aux price-unit">&nbsp;元</div>
                                    <span class="price_is_height"></span>
                                    <div class="clear"></div>
                                </div>
                                <div class="layui-form-item supplier_prerogative">
                                    <label class="layui-form-label" style="border:none;background:#fff;">订金抵扣：</label>
                                    <div class="layui-input-inline" id="supplier_prerogative" style="text-align:left; width: 75%;">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="border:none;background:#fff;">购买方式：</label>
                                    <div class="layui-input-inline" style="width: 300px;">
                                        <input type="radio" value="1" name="pay_type" lay-filter="pay_type" title="支付全款" checked>
                                        <input type="radio" value="2" name="pay_type" lay-filter="pay_type" title="支付定金">
                                    </div>
                                </div>
                                <div class="layui-form-item" id="earnest_area" style="display:none;">
                                    <label class="layui-form-label" style="border:none;background:#fff;">定金：</label>
                                    <div class="layui-input-inline">
                                        <input placeholder="请输入定金金额" class="layui-input order-price" type="text" name="earnest_price" />
                                    </div>
                                    <div class="layui-form-mid layui-word-aux price-unit">&nbsp;元</div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="border:none;background:#fff;">商户优惠券：</label>
                                    <div class="layui-input-inline" style="text-align:left;">
                                        <span class="order-discount"><i id="supplier_coupon" style="font-style:normal;color: #d70c18;text-align:left;font-size: 13px;"></i></span>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="border:none;background:#fff;">平台优惠券：</label>
                                    <div class="layui-input-inline" style="text-align:left;">
                                        <span class="order-discount"><i id="common_coupon" style="font-style:normal;color: #d70c18;text-align:left;font-size: 13px;"></i></span>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="border:none;background:#fff;">积分抵扣：</label>
                                    <div class="layui-input-inline" style="text-align:left;">
                                        <!-- <input type="checkbox" name="use_score" value="1" lay-skin="switch" lay-filter="use_score"> -->
                                <span class="point bd" id="score_area">
                                    <span style="display:none;"><input type="text" class="order-score" name="score"> 点</span>
                                    <span class="hot">-￥</span><span class="hot" id="score_price">0.00</span>
                                    <span class="available_point">（使用<span id="max_score">0</span>积分）</span>
                                </span>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="border:none;background:#fff;">订单应付款：</label>
                                    <div class="layui-input-inline" style="text-align:left;width:auto;">
                                        <span class="order-price pay-money" id="real_pay" style="color: #d70c18;"></span>
                                    </div>
                                    <div class="layui-form-mid layui-word-aux price-unit">元</div>
                                </div>
                                <div class="layui-form-item" style="margin-top:40px;">
                                    <div class="layui-input-block" style="margin-left:-200px;">
                                        <button type="button" class="layui-btn layui-btn-danger" onclick="create_order();">提交订单</button>
                                        <button type="button" class="layui-btn layui-btn-primary" onclick="javascript:window.history.back(-1);" style="border:none;margin-left:53px;">返回</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php } ?>
                    </div>
                </fieldset>
            </div>

        </section>
</div>

@endsection
@section('js')
    <script type="text/javascript">
        var type = <?php echo $type; ?>; // 订单类型
        var userId = <?php echo $user['uid']; ?>; // 用户id
        var orderNum = "<?php echo $order_num; ?>"; // 订单号
        var userScore = parseInt(<?php echo $user['points']; ?>); // 用户积分
        var discountRate = parseFloat(<?php echo $discount_rate; ?>); // 折扣
        var cashOrderTotalMoney = parseFloat(<?php echo $cash_order_total_money; ?>); // 该城市抽现金奖总额
        var cashOrderPaidMoney = parseFloat(<?php echo $cash_order_paid_money; ?>); // 该城市抽现金奖应付
        var supplierDiscountRate = parseFloat(<?php echo isset($supplier_contract['assume_rate']) ? $supplier_contract['assume_rate'] : 0; ?>); // 商户承担比例
        var isCash = <?php echo $supplier_contract['payment_type'];?>;//是否是非统一收银：1统一收银；0:非统一收银
        var couponList = JSON.parse('<?php echo json_encode($coupon_data); ?>'); // 优惠券列表
        var goodsIdsList = JSON.parse('<?php echo json_encode($goods_ids); ?>');

        function pingtai_alert() {
            layer.open({
                type: 1,
                title: '平台优惠券使用条款',
                skin: 'layui-layer-rim', //加上边框
                area: ['600px', '300px'], //宽高
                content: '<p style="padding:30px;">平台优惠券使用条款：<br /><br />1、仅限当届展会3天使用<br />2、平台优惠券权益仅限本人使用，不得转让<br />3、若您使用平台优惠券非法获利，华夏家博会将中止您的使用权益，且有权不退还您的平台优惠券购买费用，若给华夏家博会造成损失，将对您进行追偿；<br />4、有以下行为的均可判定为使用平台优惠券进行非法获利<br />&nbsp;&nbsp;a.同一用户利用多个手机号购买、使用多张平台优惠券<br />&nbsp;&nbsp;b.利用工具下单、套取优惠利差、提供虚假交易信息等扰乱交易秩序，违反交易规则</p>'
            });
        }

        // 加载页面重置所有选项和输入框中的内容
        function check_money() {
            var total_money = $('#total_price').text();
            var charge_money = $("input[name=agreement_price]").val();
            if (Number(total_money) < charge_money) {
                $('#warning-money').text(total_money);
                $('.warning-msg').show();
            } else {
                $('.warning-msg').hide();
            }
        }

        $(function () {
            var total_price = $('#total_price').html();
            $('.price_is_height').html("(协商价不能高于"+total_price+"元)");
        })


    </script>
<script src="{{asset('js/order/detail.js')}}"></script>
@endsection
