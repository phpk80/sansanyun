<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>领券中心 - <?php echo $ym_endtitle; ?></title>
		<meta name="keywords" content="<?php echo $ym_keywords; ?>" />
		<meta name="description" content="<?php echo $ym_description; ?>" />
		<link rel="stylesheet" href="<?php echo $tpl; ?>/css/common.css" />
		<link rel="stylesheet" href="<?php echo $tpl; ?>/css/quan.css" />
	</head>

	<body>
		<?php include $this->arrayConfig['compiledir'].'/'.md5('header').'.php'; ?>
		<div class="problem-body">
			<div class="nb">
				<div class="list">
					<div class="couponlist"></div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		
		<?php include $this->arrayConfig['compiledir'].'/'.md5('footer').'.php'; ?>
		<?php include $this->arrayConfig['compiledir'].'/'.md5('toolbar').'.php'; ?>
		<script type="text/javascript" src="<?php echo $tpl; ?>/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo $tpl; ?>/js/main.js" ></script>
       <script type="text/javascript">
      
      	var page =1, totalpage=0, is_count =1,isLoading=false;
		$(function () {
			loadLayer();
			get_coupon_list();			
		});
		
		function get_coupon_list() {
			isLoading=true;
			$.getJSON("/quan/get_coupon", {act: "get_coupon", page:page,is_count:is_count}, function(res) {
				isLoading = false;
				var html ='';
				if(res.err) {
					msg("加载失败");return;
				}
				var n =0;
				$.each(res.data, function(k, v) {
					n++;
					html +='<div class="cp-it '+(n%5==0?"last":"")+'" id="'+v.id+'"><div class="hd">';
					html +='<span class="amount">￥<b>'+v.amount+'</b></span>';
					html +='<span class="amount_reached">满 '+v.amount_reached+'元 可用</span>';
					html +='<span class="date">'+v.date_start+'-'+v.date_end+'</span></div>';
					html +='<div class="bt"><div class="option">';
					html +='<span><label>限品类</label>：<b class="tit" title="'+v.name+'">'+v.name+'</b></span>';
					html +='<span><label>限平台</label>：'+v.client_name+'</span>';	
					html +='<span><label>限等级</label>：'+v.grade_name+'<i class="tip-down"></i></span></div>';
					html +='<div class="opbtn">';
					if(v.receive_status == '') {
						html +='<a href="javascript:void(0);" class="btn getcoupon" data-id="'+v.id+'">立即领取</a>';
					} 
					else
					{
						html +='<i class="quan-ico quan-'+ v.receive_status +'"></i>';
					}
					
					html +='</div></div><i class="i-arrow"></i></div>';
				});
				
				if(is_count == 1) {
					totalpage = res.total;
					is_count = 0;
				}
				page++;
				
				$(".couponlist").append(html);
			});			
		}
		
		var sc_up=0,sc_down=0;//判断向上/下滚
		$(window).scroll(function() {
			sc_up = $(this).scrollTop();  
			if($(this).scrollTop()+$(this).height()+368 > $(document).height() && sc_down <= sc_up && page<=totalpage && isLoading==false) {
				get_coupon_list(page);
			}
			setTimeout(function(){sc_down = sc_up;},0); 
		});
				
      </script>

	</body>

</html>