<div class="keep_address">
    <?php if (count($address_list) > 0) { ?>
    <ul class="keep_address_con layui-clear">
        <?php foreach ($address_list as $k => $address) { ?>
        <li class="J_AddrItem<?php if ($address['is_default'] == 1) { ?> address-default<?php } ?>" id="address_block<?php echo $address['address_id'];?>" data-id="<?php echo $address['address_id'];?>" onclick="check_address('<?php echo $address['address_id'];?>')">
            <div class="keep_name clearfix">
                <div class="leep_name_l fl">
                    <span title="<?php echo $address['province'];?> <?php if ($address['province'] !== $address['city']) {echo $address['city'];} ?> <?php echo $address['district'];?> 【<?php echo $address['consignee'];?>】 收"><?php echo $address['province'];?> <?php if ($address['province'] !== $address['city']) {echo $address['city'];} ?> <?php echo $address['district'];?> 【<?php echo $address['consignee'];?>】 收</span>
                </div>
            </div>
            <div class="personal_information clearfix">
                <div class="information fl wordwrap">
                    <p title="<?php echo $address['address'];?>" class="layui-elip">详细地址：<?php echo $address['address'];?></p>
                    <p>联系电话：<?php echo $address['mobile'];?></p>
                </div>
            </div>
            <i class="layui-icon addr-check-icon">&#xe618;</i>
        </li>
        <?php } ?>
    </ul>
    <button type="button" class="layui-btn layui-clear" onclick="edit_address(0)"><i class="layui-icon">&#xe61f;</i>&nbsp;添加新收货地址</button>
    <?php } else { ?>
    <dir>还没有地址信息，请先添加地址信息</dir>
    <button type="button" class="layui-btn layui-clear" onclick="edit_address(0)"><i class="layui-icon">&#xe61f;</i>&nbsp;添加新收货地址</button>
    <?php } ?>
</div>
<script type="text/javascript">
    check_address(checkAddressId);
</script>