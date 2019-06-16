
var SystemErrorMsg = "网络异常!";

var screenWidth = 980;

var STATUS_FAIL = 2;
var STATUS_OK = 1;
var STATUS_ERR = 0;
var STATUS_UNLOGIN = -1;

var SUFFIX = '';

var submitFlag = false;

var commonCallback = function() {}

const upload_url = '/upload';


var base = {
    // 验证是否为正确的手机号码
    isMobile: function(mobile) {
        var reg = /^1\d{10}$/;

        if(!mobile){
            return false;
        }
        if (mobile.length === 11 && reg.test(mobile)) {
            return true;
        } else {
            return false;
        }
    },
    load:function(){
        var ss_w = Number(document.body.offsetWidth);//window.screen.width;
        if(ss_w > screenWidth){
            return layer.load(2,{shadeClose: false,shade: [0.5,'#000']});
        }else{//手机端的时候
            return layer.open({type: 2,shadeClose: false});
        }
    },

    close:function(index){
        layer.close(index);
    },
    alert:function(msg){
        return new Promise((resolve, reject)=> {
            var ss_w = Number(document.body.offsetWidth);//window.screen.width;
            if (ss_w > screenWidth) {
                layer.alert(msg, {
                    title: '信息'
                    , shadeClose: false
                });
            } else {//手机端的时候
                layer.open({
                    content: msg
                    , btn: ['确定']
                    , shadeClose: false
                    , yes: function (index) {
                        layer.close(index);
                        resolve()
                        // if (callback && typeof callback == 'function') callback();
                    }
                });
            }
        })

    },
    msg:function (msg) {
        layer.msg(msg);
    },

    confirm:function(msg,data){
        var msg = msg;
        var yes_callback = data.yes_callback;
        var no_callback = data.no_callback;

        var btn = data.btn ? data.btn : ['确定','取消'];

        var ss_w = Number(document.body.offsetWidth);//window.screen.width;
        if(ss_w > screenWidth){
            var index = layer.confirm(msg, {
                btn: btn //按钮
            }, function(){
                layer.close(index);
                if(yes_callback && typeof yes_callback == 'function') yes_callback();
            }, function(){
                layer.close(index);
                if(no_callback && typeof no_callback == 'function') no_callback();
            });
        }else{//手机端的时候
            var index = layer.open({
                content: msg
                ,btn: btn
                ,shadeClose: false
                ,yes: function(index){
                    layer.close(index);
                    if(yes_callback && typeof yes_callback == 'function') yes_callback();
                }
                ,no : function(index){
                    layer.close(index);
                    if(no_callback && typeof no_callback == 'function') no_callback();
                }
            });
        }

    },

    // 公共上传
    ajax: function (url,form,dataType='json'){
        //return new Promise((resolve, reject) => {
            var data = $(form).serialize();
           // var index = base.load();
            $.ajax({
                url:url,
                data:data,
                type:'POST',
                async:false,
                scriptCharset: 'utf-8',
                dataType:dataType,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                success:function(res){
                    if(res.status == 200){
                        alert(res.msg)
                        window.location.href = form
                    }else{
                        alert(res.msg)
                    }
                },
                complete: function(){

                }
            })
       // })
    },


    /**
     * 通用提交跳转
     * @param sub_url   提交url
     * @param jump_url  跳转url
     * @param selectorName jquery选择器名称,form标签的id/class
     */
    universalSubJump:function(sub_url,jump_url,selectorName){
        var data = $(selectorName).serialize();
        // var index = base.load();
        $.ajax({
            url:sub_url,
            data:data,
            type:'POST',
            async:false,
            scriptCharset: 'utf-8',
            dataType:'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            success:function(res){
                if(res.status == 200){
                    alert(res.msg)
                    window.location.href = jump_url
                }else{
                    alert(res.msg)
                }
            },
            complete: function(){

            }
        })
    },


    /**
     * 基础图片上传
     * @param idName
     * @param maxNum
     * @param hiddenInputName
     * @returns {boolean}
     */
    uploadImg:function (idName,maxNum,hiddenInputName) {
        return new Promise((resolve,reject)=>{
            $("#" + idName).ajaxImageUpload({
                url: upload_url, //上传的服务器地址
                maxNum: maxNum, //允许上传图片数量
                hiddenInputName:hiddenInputName, // 上传成功后追加的隐藏input名，注意不要带[]，会自动带[]，不写默认和上传按钮的name相同
                success:function(res){
                    if(res.code == 200){
                        return resolve(res);
                    }else{
                        return reject(res.msg)
                    }
                },
                error:function (e) {
                    return reject('网络错误');
                }
            })
        })
    },
    /**
     * 上传图片使用
     * @param idName
     * @param maxNum
     * @param hiddenInputName
     */
    uploadImgUse:function (idName,maxNum,hiddenInputName) {
        base.uploadImg(idName,maxNum,hiddenInputName).then(request=>{
            if(request.code == 200){
                base.alert(request.msg);
            }
        }).catch (err=>{
            base.alert(err);
        })
    },

    /**
     * 时间插件设置
     * @param className
     * @param <bool> singleDatePicker 是否是单个框显示
     */
    dateConfig:function (className,singleDatePicker) {
        //定义locale汉化插件
        var locale = {
            format: 'YYYY-MM-DD HH:mm:ss',
            customRangeLabel: "自定义",
            applyLabel: "确定",
            cancelLabel: "取消",
            fromLabel: "起始时间",
            toLabel: "结束时间'",
            weekLabel: "W",
            daysOfWeek: ["日", "一", "二", "三", "四", "五", "六"],
            monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            firstDay: 1
        };
        $(className).daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            autoUpdateInput: true,
            singleDatePicker: singleDatePicker,
            locale: locale
        }, function () {
            // $('.data').html(locale);
        });
    },

    /**
     * 可以清除输入框的时间插件设置
     * @param className
     */
    defClearDateConfig:function (className) {
        $(className).datepicker({
            // showSecond: true, //显示秒
            // timeFormat: '',//格式化时间
            clearBtn:true,
            format:"yyyy-mm-dd",
            autoclose: true,
            language: "cn"
        })
    },
     /**
     * 可以清除输入框的时间插件设置,带时分秒
     * @param className
     */
    baseJeDate:function (className){
        jeDate(className,{
            format:"YYYY-MM-DD hh:mm:ss",
            isTime:false,
            minDate:"2014-09-19 00:00:00"
        })
    },

    //获取url参数方法
    getQueryString:function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);  //获取url中"?"符后的字符串并正则匹配
        var context = "";
        if (r != null)
            context = r[2];
        reg = null;
        r = null;
        return context == null || context == "" || context == "undefined" ? "" : decodeURI(context);
    }

};

var search = {

    /**
     * 搜索后将搜索内容放回搜索框
     * @param searchInfo    数组对象，k-v形式
     *        eg:[{'tagName':'input','searchName':'user_name'}]
     */
    getSearchContent:function (searchInfo) {
        for (var key in searchInfo){

            var value      = base.getQueryString(searchInfo[key].searchName);
            var tagName    = searchInfo[key].tagName;
            var searchName = searchInfo[key].searchName;

            switch (tagName){
                case 'input':
                    $("input[name='"+searchName+"']").val(value);
                    break;
                case 'select':
                    $("select[name='"+searchName+"']").val(value).trigger("change");
                    break;
            }
        }
    }
};
// js 除法
function accDiv(arg1, arg2) {
    var t1 = 0, t2 = 0, r1, r2;
    try {
        t1 = arg1.toString().split(',')[1].length;
    } catch (e) { }

    try {
        t2 = arg2.toString().split(',')[1].length;
    } catch (e) { }

    with (Math) {
        r1 = Number(arg1.toString().replace(',', ''));
        r2 = Number(arg2.toString().replace(',', ''));
        return (r1 / r2) * pow(10, t2 - t1);
    }
}
// js 乘法
function accMul(arg1, arg2) {
    var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
    try {
        m += s1.split('.')[1].length;
    } catch (e) { }

    try {
        m += s2.split('.')[1].length;
    } catch (e) { }

    return Number(s1.replace('.', '')) * Number(s2.replace('.', '')) / Math.pow(10, m)

}

// js 加法
function accAdd(arg1, arg2) {
    var r1, r2, m, c;
    try {
        r1 = arg1.toString().split('.')[1].length;
    } catch (e) {
        r1 = 0;
    }

    try {
        r2 = arg2.toString().split('.')[1].length;
    } catch (e) {
        r2 = 0;
    }

    c = Math.abs(r1 - r2);
    m = Math.pow(10, Math.max(r1, r2))
    if (c > 0) {
        var cm = Math.pow(10, c);
        if (r1 > r2) {
            arg1 = Number(arg1.toString().replace('.', ''));
            arg2 = Number(arg2.toString().replace('.', '')) * cm;
        } else {
            arg1 = Number(arg1.toString().replace('.', '')) * cm;
            arg2 = Number(arg2.toString().replace('.', ''));
        }
    } else {
        arg1 = Number(arg1.toString().replace('.', ''));
        arg2 = Number(arg2.toString().replace('.', ''));
    }
    return (arg1 + arg2) / m;
}

// js 减法
function accSub(arg1, arg2) {
    var r1, r2, m, n;
    try{
        r1 = arg1.toString().split('.')[1].length;
    } catch (e) {
        r1 = 0;
    }
    try{
        r2 = arg2.toString().split('.')[1].length;
    } catch (e) {
        r2 = 0;
    }
    m = Math.pow(10, Math.max( r1, r2 ));
    // last modify by deeka
    // 动态控制精度长度
    n = ( r1 >= r2 ) ? r1 : r2;
    return ((arg1 * m - arg2 * m)/m).toFixed(n);
}

// 保留两位小数
function changeTwoDecimal(x) {
    var f_x = parseFloat(x);
    if (isNaN(f_x)) {
        return false;
    }

    f_x = accDiv(Math.floor(accMul(f_x, 100)), 100);

    var s_x = f_x.toString();
    var pos_decimal = s_x.indexOf('.');

    if (pos_decimal < 0) {
        pos_decimal = s_x.length;
        s_x += '.';
    }

    while (s_x.length <= pos_decimal + 2) {
        s_x += '0';
    }

    return s_x;
}
