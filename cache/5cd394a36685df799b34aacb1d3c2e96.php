<!DOCTYPE html>
<html>

	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>限时秒杀 - <?php echo $ym_title; ?></title>
		<link href="<?php echo $tpl; ?>/css/common.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="<?php echo $tpl; ?>/css/item.css" />
		<link rel="stylesheet" href="<?php echo $tpl; ?>/css/ie.css" />
	</head>

	<body>
		<?php include $this->arrayConfig['compiledir'].'/'.md5('header').'.php'; ?>
		<?php if(is_array($timebanner)){foreach($timebanner as $p){ ?>
		<div class="nybanner" style="background:url(<?php echo $p[img]; ?>) no-repeat top center;height: 350px;"></div>
		<<?php }} ?>
		<div class=" mblist3">
			<div class="nb">
				<div class="mblist-hd3">
					<ul>
						<li class="on">
							<a href="javascript:void(0);">抢购中</a><i class="arrow"></i></li>
						<li>
							<a href="javascript:void(0);">下期预告</a><i class="arrow"></i></li>
					</ul>
				</div>
				<div class="mblist-bd3">
					<!--抢购中-->
					<div class="mb-bd-block nowlimit" style="display: block;">
						<ul>
							<?php if($diy_timespike) { ?><?php if(is_array($spike['goods'] )){foreach($spike['goods']  as $k => $p){ ?>
							<li>
								<a href="<?php echo $p[url]; ?>" class="lside">
									<img src="<?php echo $p[thumb]; ?>" alt="" />
								</a>
								<div class="rside">
									<div class="time" id="time<?php echo $k; ?>" data-time="<?php echo $nowtime; ?>">距结束：<span id="v_day<?php echo $k; ?>">0</span>天<span id="v_hour<?php echo $k; ?>">0</span>时<span id="v_minute<?php echo $k; ?>">0</span>分<span id="v_second<?php echo $k; ?>">0</span>秒</div>
									<div class="nr">
										<a href="<?php echo $p[url]; ?>" class="title"><?php echo $p[cat_name]; ?></a>
										<a href="<?php echo $p[url]; ?>" class="txt"><?php echo $p[name]; ?></a>
										<p class="price">￥<span><?php echo $p[price]; ?></span></p>
									</div>
									<div class="mb-bottom">
										<span class="buyer"><span><?php if($p[salenum]) { ?><?php echo $p[salenum]; ?><?php }else{ ?>0<?php } ?></span>人已经购买</span>
										<a href="javascript:void(0);" onclick="addCart(<?php echo $p[goods_id]; ?>,0,1,0);" class="btn">加入购物车</a>
									</div>
								</div>
								<i class="mask"></i>
								<a href="<?php echo $p[url]; ?>" class="justlook btn">先去看看...</a>
								<script>
									InterValObj1 = window.setInterval(function() {
										SetRemainTime(<?php echo $k; ?>);
									}, 1000);
								</script>
							</li>
							<<?php }} ?>
							<?php } ?></ul>
						<div class="pages">
						</div>
					</div>
					<!--下期预告-->
					<div class="mb-bd-block nextlimit">
						<ul>
						<?php if($diy_timespike) { ?><?php if(is_array($nextspike['goods'])){foreach($nextspike['goods'] as $y=>$p){ ?>
							<li>
								<a href="<?php echo $p[url]; ?>" class="lside">
									<img src="<?php echo $p[thumb]; ?>" alt="" />
								</a>
								<div class="rside">
									<div class="time" id="time5<?php echo $y; ?>" data-time="<?php echo $nexttime; ?>">距开始：<span id="v_day5<?php echo $y; ?>">0</span>天<span id="v_hour5<?php echo $y; ?>">0</span>时<span id="v_minute5<?php echo $y; ?>">0</span>分<span id="v_second5<?php echo $y; ?>">0</span>秒</div>
									<div class="nr">
										<a href="<?php echo $p[url]; ?>" class="title"><?php echo $p[cat_name]; ?></a>
										<a href="<?php echo $p[url]; ?>" class="txt"><?php echo $p[name]; ?></a>
										<p class="price">￥<span><?php echo $p[price]; ?></span></p>
									</div>
									<div class="mb-bottom">
										<span class="buyer"><span><?php if($p[salenum]) { ?><?php echo $p[salenum]; ?><?php }else{ ?>0<?php } ?></span>人已经购买</span>
										<a href="#" class="btn">加入购物车</a>
									</div>
								</div>
								<i class="mask"></i>
								<a href="<?php echo $p[url]; ?>" class="justlook btn">先去看看...</a>
								<script>
									InterValObj1 = window.setInterval(function() {
										SetRemainTime(5<?php echo $y; ?>);
									}, 1000);
								</script>
							</li>
						<?php }} ?>
						<?php } ?></ul>
						<div class="pages">
						</div>
					</div>
				</div>
			</div>
		</div>
		<p class="clear"></p>
		<?php include $this->arrayConfig['compiledir'].'/'.md5('footer').'.php'; ?>
		<script type="text/javascript" src="<?php echo $tpl; ?>/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo $tpl; ?>/js/jquery.SuperSlide.2.1.1.js"></script>
		<script type="text/javascript" src="<?php echo $tpl; ?>/js/main.js"></script>
		<script>
			$(".mblist-bd3 li:nth-child(odd)").css("margin-right", "1%");
			$(".mblist-hd3 li").each(function() {
				$(this).click(function() {
					$(".mblist-bd3 .mb-bd-block").eq($(this).index()).show().siblings().hide();
					$(this).addClass("on").siblings().removeClass("on");
				});
			});
			$(function () {
 				loadLayer();
 			});
		</script>
	</body>

</html>