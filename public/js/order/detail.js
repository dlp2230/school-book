// 框架定义
var layer = layui.layer;
var form = layui.form(); // 表单对象
// 参数定义
var buyType = 3; //支付方式: 1定金 3全款
var intReg = /^(?:[1-9]\d*|0)$/; // 非负整数正则表达式
var floatReg = /^(?!0+(\.0+)?$)\d+(\.\d+)?$/; // 正数正则表达式
var maxScore = 0; // 此次最大可用积分
var earnestPrice = parseFloat($("input[name=earnest_price]").val()); // 定金
var agreementPrice =  parseFloat($("input[name=agreement_price]").val()); // 成交价
var earnestPriceDom = $("input[name=earnest_price]");
var agreementPriceDom = $("input[name=agreement_price]");
var scoreDom = $("input[name=score]");
var realPay = $("#real_pay");
var scorePrice = $("#score_price");  //家博积分
var supplierCouponId = 0;
var commonCouponId = 0;
var supplierCouponValue = 0;
var commonCouponValue = 0;
var supplierCouponDom = $("#supplier_coupon");
var commonCouponDom = $("#common_coupon");
var checkAddressId = 0;
// 页面初始化
reset_all();
set_price();
// 加载页面重置所有选项和输入框中的内容
function reset_all() {
    // 默认选择全款状态
    $("input[name=pay_type]")[0].checked = true;
    // 定金框重置为0
    $("input[name=earnest_money]").val(0);
}
// 设置协商总价和成交价
function set_price() {
    var totalPrice = 0;
    $("input[name^=origin_price]").each(function(index) {
        totalPrice += accMul(parseInt($(this).val() == "" ? 0 : $(this).val()), parseInt($($("input[name^=goods_num]")[index]).val()));
    })
    $("#total_price").text(changeTwoDecimal(totalPrice));
    $(".price_is_height").text('(协商价不能高于'+changeTwoDecimal(totalPrice)+'元)');
    agreementPriceDom.val(changeTwoDecimal(totalPrice));
    refresh_coupon();
}
// 设置用户可使用的积分
function set_max_score() {
    var money = 0;
    if (buyType == 1) { // 付定金时
        money = !floatReg.test(earnestPriceDom.val()) ? 0 : parseFloat(earnestPriceDom.val() == '' ? 0 : earnestPriceDom.val());
    } else { // 付全款时
        money = !floatReg.test(agreementPriceDom.val()) ? 0 : parseFloat(agreementPriceDom.val() == '' ? 0 : agreementPriceDom.val());
    }
    var discountMoney = accDiv(Math.floor(accMul(accMul(money, accSub(1, discountRate)), 100)), 100);
    money = parseFloat(money) - parseFloat(discountMoney) - parseFloat(supplierCouponValue) - parseFloat(commonCouponValue);
    money = money < 0 ? 0 : money;

    var _maxScore = parseInt(accMul(money, 100));
    maxScore = _maxScore < userScore ? _maxScore : userScore;
    $("#max_score").text(maxScore);
    scoreDom.val(maxScore);
    scorePrice.text(changeTwoDecimal(accDiv(maxScore, 100)));
    set_real_pay();
}
// 设置实付款的值
function set_real_pay() {
    var money = 0;
    if(buyType == 1) { // 付定金时
        money = !floatReg.test(earnestPriceDom.val()) ? 0 : parseFloat(earnestPriceDom.val() == '' ? 0 : earnestPriceDom.val());
    } else { // 付全款时
        money = !floatReg.test(agreementPriceDom.val()) ? 0 : parseFloat(agreementPriceDom.val() == '' ? 0 : agreementPriceDom.val());
    }
    var discountMoney = accDiv(Math.floor(accMul(accMul(money, accSub(1, discountRate)), 100)), 100);
    money = parseFloat(money) - parseFloat(discountMoney) - parseFloat(supplierCouponValue) - parseFloat(commonCouponValue);
    money = money < 0 ? 0 : money;
    var score = scoreDom.val() == '' ? 0 : scoreDom.val();
    if (!intReg.test(score)) score = 0;
    var discharge = changeTwoDecimal(parseFloat(accDiv(score, 100)));
    money = changeTwoDecimal(parseFloat(accSub(money, discharge)));
    scorePrice.text(discharge);
    realPay.text(money);
    set_cost_list();
}
// 刷新优惠券
function refresh_coupon() {
    var supplierCouponComment = "暂无满足条件的商户优惠券";
    var commonCouponComment = "暂无满足条件的平台优惠券";
    // 初始化优惠券值
    supplierCouponId = 0;
    commonCouponId = 0;
    supplierCouponValue = 0;
    commonCouponValue = 0;
    console.log("优惠券列表:"+couponList);

    if (couponList.length > 0) {
        // 获取原价
        var money = buyType == 3 ? parseFloat(agreementPriceDom.val()) : parseFloat(earnestPriceDom.val() == '' ? 0 : earnestPriceDom.val());
        for (var i = 0; i < couponList.length; i ++) {
            var consumeAmount = parseFloat(couponList[i].coupon_limit); // 需满足金额
            var couponValue = parseFloat(couponList[i].coupon_val); // 优惠金额
            if (couponList[i].merchant_id != "0") { // 商户优惠券
                if (money >= consumeAmount && couponValue > supplierCouponValue) { // 满足金额条件且优惠券金额最大
                    supplierCouponValue = couponValue;
                    supplierCouponId = couponList[i].coupon_id;
                    supplierCouponComment = "满 " + changeTwoDecimal(consumeAmount) + " 减 " + changeTwoDecimal(couponValue);
                }
            } else { // 平台优惠券
                if (money >= consumeAmount && couponValue > commonCouponValue) { // 满足金额条件且优惠券金额最大
                    commonCouponValue = couponValue;
                    commonCouponId = couponList[i].coupon_id;
                    commonCouponComment = "满 " + changeTwoDecimal(consumeAmount) + " 减 " + changeTwoDecimal(couponValue);
                }
            }
        }
    }
    supplierCouponDom.text(supplierCouponComment);
    commonCouponDom.text(commonCouponComment);

    set_max_score();
}
// 监听付款方式选择事件
form.on("radio(pay_type)", function(data) {
    var value = $(data.elem).val();
    if (value == 1) { // 选择定金时
        $("#earnest_area").show();
        earnestPriceDom.focus();
    } else { // 选择全款时
        $("#earnest_area").hide();
        earnestPriceDom.blur();
    }
    buyType = value;
    refresh_coupon();
});
// 监听积分开关事件
form.on("switch(use_score)", function(data) {
    if (data.elem.checked) { // 使用积分
        $("#score_area").show();
        scoreDom.focus();
    } else {
        $("#score_area").hide();
        scoreDom.val("");
        scoreDom.blur();
        $(".point-error").text("").hide();
    }
});
// 修改定金时
earnestPriceDom.on("keyup", function() {
    if (buyType == 1) refresh_coupon();
});
// 修改全款时
agreementPriceDom.on("keyup", function() {
    if (buyType == 3) refresh_coupon();
});
// 修改积分时
scoreDom.on("keyup", function() {
    var point = scoreDom.val();
    point = parseFloat(point);
    if (point == "") {
        $(".point-error").text("").hide();
    } else if (!intReg.test(point)) {
        $(".point-error").text("使用家博积分点数必须为大于或等于0的整数").show();
    } else {
        if (point > maxScore) {
            $(".point-error").text("本次最多可用" + maxScore + "积分").show();
            scoreDom.val(_maxScore);
        } else {
            $(".point-error").text("").hide();
        }
    }
    set_real_pay();
});
// 验证商品价格
function change_goods_price(obj) {
    var goodsPriceDom = $(obj);
    var goodsPrice = goodsPriceDom.val();
    if (goodsPrice == '') goodsPrice = 0.00;
    goodsPrice = parseFloat(goodsPrice);
    if (goodsPrice <= 0) goodsPrice = 0.00;
    goodsPriceDom.val(changeTwoDecimal(goodsPrice));
    set_price();
}
// 修改商品数量
function change_goods_num(obj) {
    var goodsNumDom = $(obj);
    var goodsNum = goodsNumDom.val();
    if (goodsNum == '') goodsNum = 1;
    goodsNum = parseInt(goodsNum);
    if (goodsNum < 1) goodsNum = 1;
    goodsNumDom.val(goodsNum);
    set_price();
}
// 商品数量加减1
function set_goods_num(obj, addFlag) {
    var goodsNumDom = $(obj).parent().find("input[name^=goods_num]");
    var goodsNum = parseInt(goodsNumDom.val());
    if (addFlag) { // 数量加一
        if (isNaN(goodsNum)) {
            goodsNum = 1;
            goodsNumDom.val(goodsNum);
        } else {
            goodsNumDom.val(goodsNum + 1);
        }
    } else { // 数量减一
        if (isNaN(goodsNum)) {
            goodsNum = 1;
        }
        if (goodsNum <= 1) {
            goodsNum = 1;
            goodsNumDom.val(goodsNum);
        } else {
            goodsNumDom.val(goodsNum - 1);
        }
    }
    set_price();
}
// 删除商品
function del_goods(goodsId) {
    var layerConfirm = layer.confirm("是否确认要移除该商品？", {skin:"layui-layer-molv"}, function() {
        layer.close(layerConfirm);
        if (goodsIdsList.length == 1 && (goodsIdsList == goodsId || goodsIdsList.indexOf(goodsId) > -1)) {
            layer.msg("至少要保留一件商品");
        } else {
            var index = goodsIdsList.indexOf(goodsId);
            if (index > -1) {
                goodsIdsList.splice(index, 1);
                $("#goods_line" + goodsId).remove();
            }
            set_price();
        }
    });
}
// 添加、修改收货地址
function edit_address(address_id) {
    var layerLoading = layer.load(1);
    var url = baseUrl + "order/unified/edit_address" + "?address_id=" + address_id + "&uid=" + userId;
    $.get(url, function(content) {
        layer.close(layerLoading);
        layer.open({
            type: 1,
            skin: "layui-layer-molv",
            title: "保存地址",
            area: "600px",
            content: content
        });
    }, "html");
}
// 加载用户的地址信息
function load_address() {
    var layerLoading = layer.load(1);
    var url = baseUrl + "order/unified/add_address" + "?uid=" + userId;
    $.get(url, function(content) {
        layer.close(layerLoading);
        $("#address_area").html(content);
    }, "html");
}
// 选地址
function check_address(addressId) {
    if (addressId == 0) {
        $(".J_AddrItem").each(function(i, item) {
            if ($(item).hasClass("address-default")) { // 如果未选过地址则自动选中默认地址
                $(item).addClass("current").siblings("li").removeClass("current");
                checkAddressId = $(item).attr("data-id");
            }
        });
        if (checkAddressId == 0 && $(".J_AddrItem").length > 0) {
            $($(".J_AddrItem")[0]).click();
        }
    } else {
        $("#address_block" + addressId).addClass("current").siblings("li").removeClass("current");
        checkAddressId = addressId;
    }
}
// 页面加载自动加载地址区域
load_address();
// 保存地址
function save_address(addressId) {
    var recipients = $("#address_form input[name=recipients]").val();
    var mobile = $("#address_form input[name=tel]").val();
    var address = $("#address_form input[name=address]").val();
    var province = $("#address_form select[name=province]").val();
    var city = $("#address_form select[name=city]").val();
    var district = $("#address_form select[name=district]").val();
    if (province == 0 || city == 0 || district == 0) {
        layer.msg("请选择收货人地址");
    } else if (address.length == 0) {
        layer.msg("请输入详细地址")
    } else if (recipients.length == 0) {
        layer.msg("请输入收货人姓名");
    } else if (!base.isMobile(mobile)) {
        layer.msg("请输入11位有效手机号码");
    } else {
        var layerLoading = layer.load(1);
        var params = new function() {
            this.user_id = userId;
            this.address_id = addressId;
            this.recipients = recipients;
            this.mobile = mobile;
            this.province = province;
            this.city = city;
            this.district = district;
            this.address = address;
        };
        var url = baseUrl + "order/unified/edit_address";
        $.post(url, {params: params}, function(data) {
            layer.close(layerLoading);
            if (data.status == 1) {
                checkAddressId = data.address_id;

                load_address();
                layer.closeAll();
            } else {
                layer.msg(data.msg);
            }
        }, "json").error(function() {
            layer.close(layerLoading);
            layer.msg("系统错误~请重试");
        });
    }
}
// 设置商户成本列表数据
function set_cost_list() {
    var money = 0;
    if (buyType == 1) { // 付定金时
        money = !floatReg.test(earnestPriceDom.val()) ? 0 : parseFloat(earnestPriceDom.val() == '' ? 0 : earnestPriceDom.val());
    } else { // 付全款时
        money = !floatReg.test(agreementPriceDom.val()) ? 0 : parseFloat(agreementPriceDom.val() == '' ? 0 : agreementPriceDom.val());
    }
    // 用户折扣金额
    var userDiscount = changeTwoDecimal(parseFloat(accSub(money, accMul(money, discountRate))));
    // 商户承担折扣金额
    var supplierDiscount = changeTwoDecimal(parseFloat(accMul(money, supplierDiscountRate * 0.01)));
    // 消费者
    $("#cost_user_money").text(changeTwoDecimal(money));
    $("#cost_user_discount_money").text(userDiscount);
    $("#cost_user_supplier_coupon_money").text(changeTwoDecimal(supplierCouponValue));
    $("#cost_user_common_coupon_money").text(changeTwoDecimal(commonCouponValue));
    $("#cost_user_score_money").text(changeTwoDecimal(maxScore * 0.01));
    // 商户
    $("#cost_supplier_money").text(changeTwoDecimal(money));
    $("#cost_supplier_discount_money").text(supplierDiscount);
    $("#cost_supplier_supplier_coupon_money").text(changeTwoDecimal(supplierCouponValue));
}

// 创建订单
function create_order() {
    var layerLoading = layer.load(1);
    // 获取参数
    var params = $("form").serialize() + "&supplier_coupon_id=" + supplierCouponId + "&common_coupon_id=" + commonCouponId + "&address_id=" + checkAddressId + "&type=" + type + "&user_id=" + userId + "&order_num=" + orderNum;
    var url = baseUrl + "order/unified/do_create_order";

    $.ajax({
        url:url,
        data:params,
        type:'POST',
        async:false,
        scriptCharset: 'utf-8',
        dataType:'json',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        success:function(data){
            layer.close(layerLoading);

            if (data.status == 1) {
                //创建订单成功~
                //window.location.href = baseUrl + "order/unified/success?type=" + type + "&order_num=" + data.data.order_num + "&key=" + data.data.key;
            } else {
                layer.msg(data.msg);
            }
        },
        error: function(){
            layer.close(layerLoading);

            layer.msg("系统错误~请刷新页面重试");
        }
    });

}

// 修改订单
function update_order() {
    var layerLoading = layer.load(1);
    // 获取参数
    var params = $("form").serialize() + "&supplier_coupon_id=" + supplierCouponId + "&common_coupon_id=" + commonCouponId + "&user_id=" + userId + "&order_id=" + orderId + "&order_num=" + orderNum + "&sub_order_num=" + subOrderNum;
    var url = baseUrl + "update_order";
    $.post(url, params, function(data) {
        layer.close(layerLoading);
        if (data.status == 1) {
            window.location.href = baseUrl + "create_order/success?type=0&order_num=" + data.data.order_num + "&key=" + data.data.key + "&is_update=1";
        } else {
            layer.msg(data.msg);
        }
    }, "json").error(function() {
        layer.msg("系统错误~请刷新页面重试~");
        layer.close(layerLoading);
    });
}