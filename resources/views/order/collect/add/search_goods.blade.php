
            <table class="layui-table">
                <colgroup>
                    <col width="25%">
                    <col width="25%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead>
                <tr>
                    <th>操作</th>
                    <th>商品名称</th>
                    <th>商品型号</th>
                    <th>活动价</th>
                </tr>
                </thead>
                <?php if (!empty($list)) { ?>
                <tbody>
                <?php foreach ($list as $k => $goods) { ?>
                <tr class="check-tr goods_<?php echo $goods['goods_id'];?>" <?php echo $goods['goods_id'] == 1 ? 'style="background:#f1cb6c""' : null;?> onclick="check_goods(this);" data-id="{{$goods['goods_id']}}">
                    <td class="checkbox"><input type="checkbox" name="ids[]" value="{{$goods['goods_id']}}"></td>
                    <td class="goods_name">{{$goods['goods_name']}}</td>
                    <td class="goods_num">{{$goods['goods_sn']}}</td>
                    <td>￥{{$goods['origin_price']}}<br></td>
                </tr>
                <?php } ?>
                </tbody>
                <?php } else { ?>
                <tr><td colspan="4">暂无数据</td></tr>

                <?php } ?>
            </table>

            <div style="float:right" id="page"></div>




            <script type="text/javascript">
                // 分页
                var laypage = layui.laypage;
                laypage({
                    cont: "page",
                    pages: "<?php echo $total_pages;?>",
                    first: "首页",
                    last: "尾页",
        //            skip: true,
                    curr: "<?php echo $page;?>",
                    jump: function(obj, first) {
                        page = obj.curr;
                        if(!first) {
                            if (pageFlag) {
                                pageFlag = false;
                                search_goods();
                            }
                        }
                    }
                });
                console.log(isPrerogativeLoad)
                if (isPrerogativeLoad == false) {
                    <?php
                    foreach ($list as $k => $goods) {
                    if(isset($goods['prerogative_goods']) && $goods['prerogative_goods'] == 1) {
                    ?>
                    $(".goods_<?php echo $goods['id']; ?>").trigger("click");
                    <?php
                            }
                            }
                            ?>
                            isPrerogativeLoad = true;
                }
                // search_goods();
                refresh_check();
            </script>

