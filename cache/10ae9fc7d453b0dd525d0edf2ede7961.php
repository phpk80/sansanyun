<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title><?php echo $ym_title; ?></title>
		<meta name="keywords" content="<?php echo $ym_keywords; ?>" />
		<meta name="description" content="<?php echo $ym_description; ?>" />
		<link rel="stylesheet" href="<?php echo $tpl; ?>/css/common.css" />
		<link rel="stylesheet" href="<?php echo $tpl; ?>/css/animate.min.css" />
		<style>
			.nav nav ul li a.ahover {
				border-bottom-color: #de342f;
			}
		</style>
	</head>

	<body>
<?php include $this->arrayConfig['compiledir'].'/'.md5('header').'.php'; ?>
		<div class="navtitleright nb"><a href="index.html">首页</a> <?php echo $crumbs_nav; ?></div>
		<div class="indbody pro-style">
			<div class="nb recom-box">
				<div class="part-price-sort">
					<ul class="pps">
						<li class="readychoose">
							<div class="lside-name">已选择</div>
							<div class="rside-navtitle">
								<?php if($catinfo[name]) { ?><a href="<?php if($lasturl) { ?><?php echo $lasturl; ?>
										 <?php }else{ ?>list?at=<?php echo $at; ?>&page=<?php echo $page; ?>&pr=<?php echo $pr; ?>
								<?php } ?>" title="取消选择" style="margin-right:10px;">分类：<span><?php echo $catinfo[name]; ?><em>×</em></span></a>
								<?php } ?><?php if($pr !='全部') { ?><a href="<?php echo $price_grade[0]['url']; ?>" title="取消选择" style="margin-right:10px;">价格：<span><?php echo $pr; ?><em>×</em></span></a>
								<?php } ?><?php if(is_array($at_param)){foreach($at_param as $p){ ?>
								<a href="<?php echo $p[url]; ?>" title="取消选择" style="margin-right:10px;"><?php echo $p[name]; ?>：<span><?php echo $p[val]; ?><em>×</em></span></a>
								<?php }} ?>
								<?php if($coupon) { ?><span style="font-size: 16px; color: #2ab6b9;">优惠券：<?php echo $coupon['name']; ?> 【满 <?php echo $coupon['amount_reached']; ?>元减<?php echo $coupon['amount']; ?>元】</span>
								<?php } ?></div>
						</li>
						<?php if($cat_child) { ?><li>
							<div class="lside-name">分类</div>
							<div class="rside-navtitle">
								<?php if(is_array($cat_child)){foreach($cat_child as $p){ ?>
								<a href="<?php echo $p[url]; ?>"
								   <?php if($son==$p['id']) { ?> class="red"
								   <?php } ?>><?php echo $p[name]; ?></a>
								<?php }} ?>
							</div>
						</li>
						<?php } ?><?php if($brand) { ?><li>
							<div class="lside-name">品牌</div>
							<div class="rside-navtitle">
								<?php if(is_array($brand )){foreach($brand  as $p){ ?>
								<a href="<?php echo $p[url]; ?>" <?php if($bid==$p['id']) { ?>class="red"
								                    <?php } ?>><?php echo $p['name']; ?></a>
								<?php }} ?>
							</div>
						</li>
						<?php } ?><li>
							<div class="lside-name">价格</div>
							<div class="rside-navtitle">
								<?php if(is_array($price_grade)){foreach($price_grade as  $k1){ ?>

								<a href="<?php echo $k1['url']; ?>" <?php if(trim($pr)==trim($k1['name'])) { ?> class="red"
 								                       <?php } ?>><?php echo $k1['name']; ?></a>
								<?php }} ?>
								 <span class="setprice">
								 	<input type="text" id="price-min" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"/> - <input type="text" id="price-max" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
								 	<input type="button" id="btnprice" value="确定"/>
								 </span>
							</div>
						</li>
						<?php if(!$word && $word =='') { ?><?php if(is_array($attr)){foreach($attr as $p){ ?>
						<li>
							<div class="lside-name"><?php echo $p[name]; ?></div>
							<div class="rside-navtitle">
								<?php if(is_array($p[value] )){foreach($p[value]  as $v){ ?>
								<a href="<?php echo $v['url']; ?>" <?php if($v['cur']== 1) { ?>class="red"
								<?php } ?>><?php echo $v['name']; ?></a>
								<?php }} ?>
							</div>
						</li>
						<?php }} ?>
						<?php } ?><li>
							<div class="lside-name">排序方式</div>
							<div class="rside-navtitle some-px">
								<a href="<?php echo $sort_add_time; ?>" <?php echo $cur['a1']; ?>>默认</a>
								<a href="<?php echo $sort_sale; ?>" <?php echo $cur['s1']; ?>><span>销量</span></a>
								<a href="<?php echo $sort_price; ?>" <?php echo $cur['p1']; ?><?php echo $cur['p2']; ?>><span class="price">价格</span><b <?php if($sort=='p2') { ?>class="down"
																												  <?php }else if($sort=='p1'){ ?>class="up"
																												  <?php }else{ ?>
																												  <?php } ?>></b></a>
							</div>
						</li>
					</ul>
				</div>
                   <ul class="yourlike clearovermartb nobottompad">
                    	<?php if($goods) { ?>                    	<?php if(is_array($goods)){foreach($goods as $p){ ?>
                    	<li>
							<a href="<?php echo $p['url']; ?>" class="picbox" target="_blank">
								<img src="<?php echo $p['thumb']; ?>" alt="">
							</a>
							<div class="elli">
								<a href="<?php echo $p['url']; ?>" target="_blank"><?php echo $p['name']; ?></a>
							</div>
							<a href="<?php echo $p['url']; ?>" class="price" target="_blank">￥<span><?php echo $p['goods_price']; ?></span></a>
							<div class="probottom">
                    			<a href="javascript:void(0);" onclick="addCart(<?php echo $p[goods_id]; ?>,0,1);" class="buy">加入购物车</a><i class="line"></i>
                    			<a href="javascript:void(0);" onclick="addCart(<?php echo $p[goods_id]; ?>,1,1);" class="addcart">立即购买</a>
                    		</div>
						</li>
                    	<?php }} ?>
                    	<?php }else{ ?>
                    	<div style="width: 100%; height: 200px;padding-top: 50px; background-color: #FFF;text-align: center;font-size: 16px;">
                    		没有符合条件的商品，请尝试其他搜索条件。
                    	</div>
                    	<?php } ?></ul>
					<div class="pages"><?php echo $pages['pages']; ?></div>
			</div>
		</div>
		
		<?php include $this->arrayConfig['compiledir'].'/'.md5('footer').'.php'; ?>
		<?php include $this->arrayConfig['compiledir'].'/'.md5('toolbar').'.php'; ?>
		<script type="text/javascript" src="<?php echo $tpl; ?>/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo $tpl; ?>/js/main.js" ></script>
		<script>
 			$(function () {
 				loadLayer();
 			});
		$(".ul-pro-box li").each(function(){
			if($(this).index()%4==3){
				$(this).css("margin-right","4px");
			}
			if($(this).index()%4==0){
			   $(this).css("margin-left","4px");
			}
		});
		$(".pps li:last-child").css("border-bottom","none");
		$(".some-px a").each(function(){
			$(this).click(function(){
				$(this).addClass("red").siblings().removeClass("red");	
				if($(".some-px b").hasClass("down") || $(".some-px b").prop("class")==undefined){
					 $(this).find("b").removeClass("down").addClass("up");
				}
				else
				{
					$(this).find("b").removeClass("up").addClass("down");
				}
			});
		})
		</script>
	</body>

</html>