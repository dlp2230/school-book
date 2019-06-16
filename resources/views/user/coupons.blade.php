<div class="coupon-list">
    <?php if ($common_count > 0) { ?>
    <blockquote class="layui-elem-quote layui-quote-nm">平台优惠券</blockquote>
    <div class="list-area">
        <?php foreach ($coupons['platform_coupons'] as $k => $v) { ?>
        <div class="item-item orange">
            <div class="item-info">
                <p class="item-title"><?php echo $v['coupon_name']; ?></p>
                <p class="item-tips">订单满<?php echo (int)$v['coupon_limit']?>元可用</p>
                <p class="item-date"><?php echo date('Y.m.d', strtotime($v['begin_date']))?> - <?php echo date('m.d', strtotime($v['end_date']))?></p>
            </div>
            <div class="item-action">
                <p class="item-value"><?php echo (int)$v['coupon_val']?><span class="value-icon">￥</span></p>
            </div>
        </div>

        <?php } ?>
        <div class="layui-clear"></div>
    </div>
    <?php } ?>
    <?php if ($supplier_count > 0) { ?>
    <blockquote class="layui-elem-quote layui-quote-nm">商户优惠券</blockquote>
    <div class="list-area">
        <?php foreach ($coupons['merchant_coupons'] as $k => $v) { ?>
        <?php if (!empty($v['merchant_id'])) { ?>
        <div class="item-item blue">
            <div class="item-brand">
                <img class="item-logo" src="<?php echo $v['brand_logo'];?>">
            </div>
            <div class="item-info">
                <p class="item-title"><?php echo $v['brand_info']['brand_name'];?></p>
                <p class="item-tips">订单满<?php echo (float)$v['coupon_limit']?>元可用</p>
                <p class="item-date"><?php echo date('Y.m.d', strtotime($v['begin_date']))?> - <?php echo date('m.d', strtotime($v['end_date']))?></p>
            </div>
            <div class="item-action">
                <p class="item-value"><?php echo (int)$v['coupon_val']?><span class="value-icon">￥</span></p>
            </div>
        </div>
        <?php } ?>
        <?php } ?>
        <div class="layui-clear"></div>
    </div>
    <?php } ?>
</div>