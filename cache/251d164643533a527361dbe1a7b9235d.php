<div class="footer">
			<div class="nb">
<?php echo $diy_indexfoot; ?>
				<div class="foot-navson">
					<div class="navsonbox">
					<?php if(is_array($help)){foreach($help as $p){ ?>
						<dl>
							<dt><a href="<?php echo $p['url']; ?>" target="_blank"><?php echo $p['name']; ?></a></dt>
						<?php if(is_array($p['son'])){foreach($p['son'] as $v){ ?>
							<dd><a href="<?php echo $v[url]; ?>" target="_blank"><?php echo $v[name]; ?></a></dd>
						<?php }} ?>
						</dl>
						<?php }} ?>
					</div>
					<div class="foot-service">
						<p>客服电话</p>
						<div class="tel"><?php echo $diy_mobile; ?></div>
						<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $diy_qq; ?>&site=qq&menu=yes" target="_blank" class="btn-service">在线客服</a>
					</div>
				</div>
				<div class="foot-sortnav">
					<div class="box"><a href="/" target="_blank">首页</a></div>
					<?php if(is_array($nav_footer)){foreach($nav_footer as $p){ ?>
					<div class="box"><a href="<?php echo $p[url]; ?>"
										<?php if($p['target']==1) { ?>target="_blank"
										<?php } ?>><?php echo $p[name]; ?></a></div>
					<?php }} ?>
				</div>
				<?php if($link2) { ?><div class="foot-sortnav">
					<?php if(is_array($link)){foreach($link as $p){ ?>
					<a href="<?php echo $p[url]; ?>" <?php if($p['target']==1) { ?>target="_blank"
												<?php } ?>><img src="<?php echo $p['img']; ?>" width="120" height="60"/>
												<br />
						<?php echo $p['title']; ?></a>
					<?php }} ?>
				</div>
				<?php } ?><div class="copyright">
					<p>@2018<a href="http://www.yunec.cn/" target="_blank">三三电商系统</a>&nbsp;版权所有</p>
					<br />
					<p>工信部备案号：<?php echo $ym_icp; ?></p>
				</div>
			</div>
		</div>
		<p style="display: none;">
			<?php if($ym_site_statice) { ?><?php echo $ym_site_statice; ?>
			<?php } ?></p>