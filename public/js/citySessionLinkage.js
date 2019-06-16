/**
 * Created by wangyang on 2018/9/18.
 * 城市展届联动插件
 *
 * 使用方式：
 *
 * html中需要定义2个标签,且必须包含如下属性
 * <select class="select2" id="city"></select>
 * <select class="select2" id="session"></select>
 *
 * 在联动需要为select赋初值时，必须包含id名称为session-hidden的标签，例如
 * <input type="hidden" id="session-hidden"/>
 * 在页面初始化完成时需要为该标签赋value值
 *
 * 注意，在使用本插件做编辑搜索等场景时应注意使session的select最后执行，
 *      在HTML中的js尽量不放到$(function(){})闭包中，否则第二级会选不上
 */
(function($){

    var getCityUrl = '/api/getCity';
    var getSessionUrl = '/api/getSession';
        // 公共上传
    var  getApiData= function (url,dataType='json'){
        return new Promise((resolve, reject) => {
                $.ajax({
                url:url,
                type:'get',
                async:false,
                scriptCharset: 'utf-8',
                dataType:dataType,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                success:function(res){
                    resolve(res)
                },
                error:function (e) {
                    reject('网络异常,刷新尝试');
                },
                complete: function(){
                    // base.close(index)
                }
            })
        })
    }

    getApiData(getCityUrl).then(request=>{
        if(request.status == 200){
            var data = request.data.map(function(info) {
                var obj = {};
                obj['id'] = info.city_id;
                obj['text'] = info.city_name;
                return obj;
            });
            $('#city').select2({
                data:data,
                placeholder:'请选择',
                allowClear:true
            })
        }else{
            base.alert('发生错误');
        }
    }).catch (e=>{
        base.alert(e);
    });

        //展届
    $('#city').change(function () {
        let city_id = $(this).val();
        let url = getSessionUrl + '/' + city_id;
        if (city_id){
            getApiData(url).then(request=>{
                if(request.status == 200){
                    var session = request.data.map(function(info) {
                        var obj = {};
                        obj['id'] = info.activity_id;
                        obj['text'] = info.session + '届';
                        return obj;
                    });

                    $('#session').empty();
                    $('#session').select2({
                        data:session,
                        // placeholder:'请选择',
                        // allowClear:true
                    });
                }else{
                    base.alert('发生错误');
                }
            }).catch (e=>{
                    base.alert(e);
            });
        }else{
            $('#session').empty();
            $('#session').prepend('<option value="">请选择</option>');
        }
    })

$(function () {
    let session_hidden = $('#session-hidden').val();

    if (session_hidden){    //编辑等情况
        $('#session').val(session_hidden).select2();
    }else if(activity_id = base.getQueryString('activity_id')){ //适配搜索
        $('#session').val(activity_id).select2();
    }
})

})(jQuery);