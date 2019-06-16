/**
 * Created by Admininstrator on 2018/11/9.
 */

var page = 1; // 商品列表初始页
var pageFlag = false; // 分页是否需要初始化
var checkGoods = Cookie.getCookie("createOrderCheckGoods") == "" ? '[]' : Cookie.getCookie("createOrderCheckGoods"); // 选中的商品
var checkGoodsIds = Cookie.getCookie("createOrderCheckGoodsIds") == "" ? '[]' : Cookie.getCookie("createOrderCheckGoodsIds"); // 选中的商品id
var keyword = ""; // 筛选商品的关键词
var layer = layui.layer;
var userMobile = Cookie.getCookie("createOrderUserMobile"); // 检索出的用户手机号码
var userId = ""; // 检索出的用户id
var orderNum = Cookie.getCookie("createOrderOrderNum"); // 订单号
var isPrerogativeLoad = false;

// 是否需要显示订单号选择项
if (!orderFlag) {
    $("#search_order_area").hide();
} else if (orderNum != "") { // 初始Cookie中记录已选订单号
    $("input[name=order_num]").val(orderNum);
}

// 初始Cookie中记录已检索手机号码
if (userMobile != "") {
    $("input[name=mobile]").val(userMobile);

    search_user();
}

// 初始Cookie中记录已选商品
if (JSON.parse(checkGoodsIds).length > 0) {
    show_goods();
}

// 回车事件绑定搜索用户
document.onkeydown = function(event){
    var e = event || window.event || arguments.callee.caller.arguments[0];

    if(e && e.keyCode == 13) search_user();
};

// 手机号码更换
$("input[name=mobile]").change(function() {
    $("#search_user").html("");

    userMobile = "";
    userId = "";
});

// 搜索用户
function search_user() {
    isPrerogativeLoad = false;
    var mobile = $("input[name=mobile]").val().trim();
    if (mobile.length == 0) {
        layer.msg(type == 1 ? "请输入手机号码 / VIP专享卡号" : "请输入手机号码 / 会员卡号");
    } else {
        var layerLoading = layer.load(1);
        var url = baseUrl + "order/unified/ajax_order_user_coupons";
        $.ajax({
            url:url,
            data:{type:type,mobile:mobile},
            type:'POST',
            async:false,
            scriptCharset: 'utf-8',
            dataType:'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            success:function(result){
                layer.close(layerLoading);

                if (result.status == -1) {
                    layer.msg(result.msg);
                } else if (result.status == 1) {
                    var html = "<span>用户存在</span>";

                    userinfo = result.data;
                    if (userinfo.coupon_count > 0) {
                        html += '<a href="javascript:;" onclick="show_coupons(' + userinfo.uid + ')"><i>可用优惠券' + userinfo.coupon_count + '张，点击查看</i></a>';
                    } else {
                        html += "<i>没有可用的优惠券</i>";
                    }
                    console.log('coupons~');
                    $("#search_user").html(html);

                    userMobile = userinfo.mobile;
                    userId = userinfo.uid;
                    Cookie.deleteCookie("createOrderCheckGoodsIds");
                    Cookie.deleteCookie("createOrderCheckGoods");
                    checkGoodsIds = "[]";
                    checkGoods = "[]";
                    search_goods();
                    show_goods();
                } else if (result.status == 0) {
                    if (result.ismobile) {
                        if (type == 1) {
                            layer.alert("未找到账号信息，需到现场售卡处办理VIP返现卡");
                        } else {
                            var layerConfirm = layer.confirm("未找到账号信息，是否生成新账号？", {
                                btn: ["确定","取消"], //按钮
                                shade: false //不显示遮罩
                            }, function() {
                                $.post(baseUrl + "create_order/create_user", {mobile:mobile}, function(result) {
                                    layer.close(layerConfirm);

                                    if (result.status == 0) {
                                        layer.msg(result.msg);
                                    } else {
                                        layer.msg(result.msg);

                                        var html = "<span>用户存在</span><i>没有可用的优惠券</i>";

                                        userinfo = result.data;

                                        $("#search_user").html(html);

                                        userMobile = userinfo.mobile;
                                        userId = userinfo.id;
                                    }
                                }, "json");
                            });
                            Cookie.deleteCookie("createOrderCheckGoodsIds");
                            Cookie.deleteCookie("createOrderCheckGoods");
                            checkGoodsIds = "[]";
                            checkGoods = "[]";
                            search_goods();
                            show_goods();
                        }
                    } else {
                        layer.msg("会员卡号有误");
                    }
                }
            },
            error: function(){

            }
        });
    }
}

// 点击订单号下拉框
$("#search_order").on("click", function() {
    $("#order_area").show();
    $("input[name=search_order_num]").focus();
});

// body点击时关闭下拉框
$("body").on("click", function(e) {
    var target = e.target || e.srcElement;

    if ($(target).parents("#search_order").length == 0) {
        $("#order_area").hide();
        $("input[name=search_order_num]").blur();
    }
})

// 搜索订单号
$("input[name=search_order_num]").keyup(function() {
    var search_order_num = $(this).val().trim();

    if (search_order_num.length == 4) {
        var url = baseUrl + "order/unified/search_order_num" + "?order_num=" + search_order_num + "&t=" + type;
        $.get(url, function(data) {
            $("#order_area dd").remove(); // 移除原有选项
            var result = data.data;
            if (result.length > 0) {
                var html = '';
                for (var i = 0; i < result.length; i ++) {
                    html += '<dd data-id="' + result[i].order_sn + '">' + result[i].order_sn + '</dd>';
                }
                console.log(html)
                $("#order_area").append($(html)); // 添加选项

                $("#order_area dd").on("click", function() { // 选项点击事件
                    var order_num = $(this).attr("data-id");

                    $("input[name=order_num]").val(order_num);

                    setTimeout(function() {
                        $("#order_area").hide();
                        $("input[name=search_order_num]").blur();
                    }, 200);
                });
            } else {
                var html = "<dd>没有找到可用的订单号</dd>";

                $("#order_area").append($(html)); // 添加选项
            }

        }, "json");
    } else {
        $("#order_area dd").remove();
    }
});

// 设置筛选关键词
function set_keyword(resetFlag) {
    if (resetFlag) { // 是否是重置
        keyword = "";

        $("input[name=keyword]").val("");
    } else {
        keyword = $("input[name=keyword]").val();
    }

    search_goods();
}

// 筛选商品
function search_goods() {
    pageFlag = false;
    var layerLoading = layer.load(1);

    var url = baseUrl + "goods/search_goods" + "/" + page + "?keyword=" + keyword + "&type=" + type + "&mobile=" + userMobile;

    $.get(url, function(content) {
        layer.close(layerLoading);

        $("#goods_list").html(content);

        pageFlag = true;
    }, "html");
}

// 页面初始加载需要加载商品页面
search_goods();

// 选择商品
function check_goods(obj) {
    obj = $(obj);

    var goods_id = obj.attr("data-id");

    var checkGoodsIdsArray = JSON.parse(checkGoodsIds);
    var checkGoodsArray = JSON.parse(checkGoods);

    if (checkGoodsIdsArray == goods_id) {
        var index = 0;
    } else {
        var index = checkGoodsIdsArray.indexOf(goods_id);
    }

    if (index > -1) {
        checkGoodsIdsArray.splice(index, 1);
        checkGoodsArray.splice(index, 1);

    } else {
        var goods_name = obj.find(".goods_name").text();
        var goods_num = obj.find(".goods_num").text();

        checkGoodsIdsArray.push(goods_id);

        var goods = new Object();

        goods.id = goods_id;
        goods.name = goods_name;
        goods.num = goods_num;

        checkGoodsArray.push(goods);
    }

    checkGoods = JSON.stringify(checkGoodsArray);
    checkGoodsIds = JSON.stringify(checkGoodsIdsArray);

    show_goods();
}

// 展示已选择的商品
function show_goods() {
    var checkGoodsArray = JSON.parse(checkGoods);

    var goodsHtml = '';

    if (checkGoodsArray.length > 0) {
        for (var i = 0; i < checkGoodsArray.length; i ++) {
            goodsHtml += '<div class="check-goods-block">'
            goodsHtml += '<p>' + checkGoodsArray[i].name + '</p>';
            if(checkGoodsArray[i].num){
                goodsHtml += '<p>(' + checkGoodsArray[i].num + ')</p>';
            }
            goodsHtml += '<i class="layui-icon" onclick="del_goods(\'' + checkGoodsArray[i].id + '\')">&#x1007;</i>';
            goodsHtml += '</div>';
        }
    }

    $("#check_goods").html(goodsHtml);

    refresh_check();
}

// 删除已选商品
function del_goods(goods_id) {

    var checkGoodsIdsArray = JSON.parse(checkGoodsIds);
    var checkGoodsArray = JSON.parse(checkGoods);

    if (checkGoodsIdsArray == goods_id) {
        var index = 0;
    } else {
        var index = checkGoodsIdsArray.indexOf(goods_id);
    }

    if (index > -1) {
        checkGoodsIdsArray.splice(index, 1);
        checkGoodsArray.splice(index, 1);
    }

    checkGoods = JSON.stringify(checkGoodsArray);
    checkGoodsIds = JSON.stringify(checkGoodsIdsArray);

    show_goods();
}

// 刷新选中框
function refresh_check() {
    var checkGoodsIdsArray = JSON.parse(checkGoodsIds);

    var checkLines = $("#goods_list .check-tr");

    for (var i = 0; i < checkLines.length; i ++) {
        var id = $(checkLines[i]).attr("data-id");

        $(checkLines[i]).find("input[type=checkbox]")[0].checked = false;

        for (var j = 0; j < checkGoodsIdsArray.length; j ++) {
            if (id == checkGoodsIdsArray[j]) {
                // var isChecked = false;
                // $.get("/create_order/is_prerogative", {'checked':1}, function(data, status) {
                // isChecked = true;
                $(checkLines[i]).find("input[type=checkbox]")[0].checked = true;
                // });
                break;
            }
        }
    }
}

// 显示优惠券页面
function show_coupons(userId) {
    var layerLoading = layer.load(1);
    var url = baseUrl + "user/coupons" + "?type=" + type + "&uid=" + userId;

    $.get(url, function(content) {
        layer.close(layerLoading);

        layer.open({
            type: 1,
            skin: "layui-layer-molv",
            title: "可用的优惠券",
            area: "700px",
            content: content
        });
    }, "html");
}

// 提交订单
function submit_order() {
    var order_num = $("input[name=order_num]").val();
    var goods_ids = checkGoodsIds;

    if (userMobile == "" || userId == "") {
        layer.msg("请先检索用户信息");
    } else if (orderFlag && order_num.length == 0) {
        layer.msg("请选择纸质订单号");
    } else if (JSON.parse(goods_ids).length == 0) {
        layer.msg("请至少选择一个商品");
    } else {
        var layerLoading = layer.load(1);

        //验证订单是否可以生成~
        var url = baseUrl + "order/unified/ajax_create_check";
        $.ajax({
            url:url,
            data:{type:type,mobile:userMobile,order_num:order_num,goods_ids:goods_ids},
            type:'POST',
            async:false,
            scriptCharset: 'utf-8',
            dataType:'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            success:function(data){
                layer.close(layerLoading);

                if (data.status == 1) {
                    // 设置本地Cookie
                    Cookie.setCookie("createOrderUserMobile", userMobile);
                    Cookie.setCookie("createOrderOrderNum", order_num);
                    Cookie.setCookie("createOrderCheckGoodsIds", checkGoodsIds);
                    Cookie.setCookie("createOrderCheckGoods", checkGoods);

                    window.location.href = baseUrl + "order/unified/detail" + "?t=" + type + "&user_id=" + userId + "&mobile=" + userMobile + "&goods_ids=" + goods_ids + "&order_num=" + order_num;
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
}