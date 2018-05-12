<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>我的订单 - <?php echo $ym_title; ?></title>
	<link rel="stylesheet" href="<?php echo $tpl; ?>/css/common.css" />
	<link rel="stylesheet" href="<?php echo $tpl; ?>/css/center.css" />
</head>

<body>
<?php include $this->arrayConfig['compiledir'].'/'.md5('header').'.php'; ?>
<div class="center-body">
	<div class="nb">
		<div class="center-lside">
			<?php include $this->arrayConfig['compiledir'].'/'.md5('usermenu').'.php'; ?>
		</div>
		<div class="center-rside">
			<div class="cen-center center1">
				<div class="centerbar">
					<div class="lside">
						<ul>
							<li><a href="/myorder.html"<?php if(!$t) { ?> class="cur"<?php } ?>>全部订单</a><i class="line-grey"></i></li>
							<li><a href="/myorder.html?t=1"<?php if($t==1) { ?> class="cur"<?php } ?>>待付款</a><i class="line-grey"></i></li>
							<li><a href="/myorder.html?t=2"<?php if($t==2) { ?> class="cur"<?php } ?>>待收货</a><i class="line-grey"></i></li>
							<li><a href="/myorder.html?t=3"<?php if($t==3) { ?> class="cur"<?php } ?>>待评价</a><i class="line-grey"></i></li>
						</ul>
					</div>
					<form action="" method="post">
						<div class="rside">
							下单时间 <input type="text" name="trade_start_date" value="" maxlength="10" readonly="readonly" class="time"/>-<input type="text" name="trade_end_date" value="" readonly="readonly" maxlength="10" class="time"/>
							<div class="set-box">
								<div class="search-box">
									<input type="txt" name="keyword" id="" value="" placeholder="订单号/商品名称"  class="btn-search"/>
									<input type="submit" value=""  class="btn-sub"/>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="dingdan">
					<?php if($order) { ?><?php if(is_array($order)){foreach($order as $p){ ?>
					<div class="dd-list">
						<div class="dd-title">
							<p>下单时间：<span><?php echo $p['add_time']; ?></span></p>
							<p>订单编号：<span><?php echo $p['order_sn']; ?></span></p>
							<i class="icon-del"></i>
						</div>
						<div class="dd-detail">
							<ul>
								<li class="dd-col1">
									<?php if(is_array($p['goods'])){foreach($p['goods'] as $v){ ?>
									<div class="more-pro">
										<div class="box">
											<a href="<?php echo $v['url']; ?>" target="_blank" class="pro-pic"><img src="<?php echo $v['thumb']; ?>" width="80" height="80" alt=""/></a>
											<div class="pro-details">
												<a href="<?php echo $v['url']; ?>" target="_blank">
													<h3><?php echo $v['name']; ?></h3>
													<p class="qiangrey"><?php if(is_array($v['spec'])){foreach($v['spec'] as  $s){ ?>
														<span><?php echo $s['name']; ?>：<?php echo $s['val']; ?></span>
														<?php }} ?></p>
												</a>
											</div>
										</div>
										<div class="price">
											￥<?php echo $v['price']; ?>
										</div>
										<div class="num">
											<?php echo $v['num']; ?>
										</div>
										<div class="function">
											<?php if($p['status']==order_finish) { ?><a href="applyservice.html?oid=<?php echo $v['order_sn']; ?>&gid=<?php echo $v['goods_id']; ?>&spec=<?php echo $v['spec_name']; ?>" class="qiangrey" target="_blank">申请售后</a>
											<?php } ?></div>
									</div>
									<?php }} ?>
								</li>
								<li class="dd-col2" style="padding: 20px 5px;">
									总额：￥<?php echo $p['amount']; ?>
									<br /><br />
									应付：<b>￥<?php echo $p['payble_amount']; ?></b>
									<p class="qiangrey"></p>
								</li>
								<li class="dd-col3">
									<p class="qiangrey"><?php echo $p['status_name']; ?></p>
									<a href="details.html?oid=<?php echo $p['order_sn']; ?>" class="qiangrey" target="_blank">查看详情</a>
								</li>
								<li class="dd-col4">
									<?php if($p['status']==order_finish) { ?><?php if($p['is_comment']==0) { ?><a href="comment.html?oid=<?php echo $p['order_sn']; ?>" target="_blank">评价</a>
									<?php } ?><?php }else if($p['status']==order_paying){ ?>
									<a href="/pay/index?oid=<?php echo $p['order_sn']; ?>" target="_blank" style="border-color: #de342f;color: #de342f;">付款</a>
									<a href="javascript:void(0);" onclick="order_cancel(<?php echo $p['order_sn']; ?>);">取消订单</a>
									<?php }else if($p['status']==order_receiving){ ?>
									<a href="details.html?oid=<?php echo $p['order_sn']; ?>" target="_blank" style="border-color: #de342f;color: #de342f;">确认收货</a>
									<?php } ?><?php if($p['status'] !=order_paying) { ?><a href="javascript:void(0);" onclick="addcartMult('<?php if(is_array($p['goods'])){foreach($p['goods'] as $v){ ?>' +
									 													'<?php echo $v['goods_id']; ?>- ' +
									 													 '<?php }} ?>',1,1,'');">再次购买</a><?php } ?></li>
							</ul>
						</div>
					</div>
					<?php }} ?>
					<?php }else{ ?>
					<div style="width: 100%; height: 200px;padding-top: 50px; background-color: #FFF;text-align: center;font-size: 16px;">
						没有符合条件的订单。
					</div>
					<?php } ?><div class="pages"><?php echo $pages['pages']; ?></div>
				</div>
			</div>

		</div>
	</div>
</div>

<?php include $this->arrayConfig['compiledir'].'/'.md5('footer').'.php'; ?>
<?php include $this->arrayConfig['compiledir'].'/'.md5('toolbar').'.php'; ?>
<script type="text/javascript" src="<?php echo $tpl; ?>/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $tpl; ?>/js/main.js" ></script>
<link rel="stylesheet" type="text/css" href="/static/datepicker/css/jquery-ui.css" />
<script src="/static/datepicker/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="/static/datepicker/js/jquery.ui.datepicker-zh-CN.js"></script>


<script type="text/javascript">
    $(".advance").click(function(){
        $(".slidedown-advance").slideToggle(300);
    });
    $(".i.icon-del").each(function(){
        $(this).click(function(){
            $(this).parentsUntil(".dd-list").hide();
        });
    });
    $(function () {
        $("input[name='trade_start_date'],input[name='trade_end_date']" ).datetimepicker();
        loadLayer();
    });
</script>

</body>

</html>