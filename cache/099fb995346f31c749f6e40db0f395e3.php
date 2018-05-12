<div class="header">
    <div class="head-top">
        <div class="nb">
            <div class="rside" style="float:right">
                <ul class="head-ul">
                    <?php if($user_name!='') { ?>                    <li><a href="../user.html"><?php echo $user_name; ?></a></li>
                    <li><a href="/login/logout">退出</a></li>
                    <?php }else{ ?>
                    <li><a href="login/login">登录</a></li>
                    <li><a href="/reg/reg">注册</a></li>
                    <?php } ?>                    <li><a href="/order/myorder">我的订单</a></li>
                    <li><a href="../user.html">会员中心</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="head-bot">
        <div class="nb">
            <div class="web-title-container">
                <a href="/" class="logo"><img src="<?php echo $ym_logo; ?>" alt=""></a>
                <div class="searchbox">
                    <form name="search" action="/goods/glist/cid/" method="get">
                        <input name="word" id="word" value="<?php echo $word; ?>" placeholder="请输入关键词" class="txt-search" type="search">
                        <input value="搜&nbsp;&nbsp;索" id="btn-search" class="btn-submit" type="submit">

                    </form>
                </div>
                <div class="cartbox">
                    <div class="cartbtn">
                        <a href="../cart.html" class="radv-nav"><span>购物车</span>(<span class="cartinfo"><?php echo $cnum; ?></span>)</a>
                    </div>
                    <div class="cart-slidedownbox">
                        <h3>最近加入</h3>
                        <ul class="cart-slidedown" id="cart-list">
                            <li id="[goods_id]" data-spec="[spec_ids]">
                                <div class="cart-pro">
                                    <a href="[url]" class="pic-box"><img src="[thumb]" alt="60*60"></a>
                                    <div class="cart-pro-num elli">
                                        <a href="[url]">[name]</a>
                                    </div>
                                    <div class="close-price">
                                        <button class="close delgoods"></button>
                                        <p class="red">￥[price]x[num]</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="sum-box">
                            <a class="slidecart-js" href="/cart/idex">立即结算111(<span class="cartinfo">0</span>)</a>
                            <div class="sum">合计：￥<span class="red" id="cart-total">0.00</span></div>
                            <div class="check">
                                <p>共<span class="red cartinfo">0</span>件商品</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="nav">
                <div class="nav-classify nav-classifyopaity">
                    <h3 class="nav-all-btn">全部分类</h3>
                    <ul class="menu-mainnav" style="height: 450px;">

                        <?php if(is_array($cats)){foreach($cats as $k=>$v){ ?>
                        <li>
                            <div class="sortmaintitle">
                                <a href="<?php echo $v['url']; ?>" target="_blank"><?php echo $v['name']; ?></a>
                            </div>
                            <div class="navsonbox">
                                <div class="nr">
                                    <div class="navson-classify">
                                        <?php if(is_array($v['child'])){foreach($v['child'] as $v1){ ?>
                                        <?php if($v1['status'] ==1) { ?>                                        <div class="navson-classify-box">
                                            <h3 class="maintitle"><a href="<?php echo $v1['url']; ?>"
                                                                     target="_blank"><?php echo $v1['name']; ?><span>&gt;</span></a>
                                            </h3>
                                            <div class="navson-classify-subtitle">
                                                <?php if(is_array($v1['child'])) { ?>                                                <?php if(is_array($v1['child'])){foreach($v1['child'] as $v2){ ?>

                                                <a href="<?php echo $v2['url']; ?>" target="_blank"><?php echo $v2['name']; ?></a>
                                                <?php }} ?>
                                                <?php } ?>                                            </div>
                                        </div>
                                        <?php } ?>                                        <?php }} ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php }} ?>
                    </ul>
                </div>
                <ul class="nav-othder">
                    <li><a href="/">首页</a></li>
                    <?php if(is_array($nav)){foreach($nav as $v){ ?>

                    <li><a <?php if($v[target]==1) { ?>  target="_blank"
                           <?php } ?> href="<?php echo $v['url']; ?>"><?php echo $v['name']; ?></a></li>

                    <?php }} ?>

                </ul>
            </div>
        </div>
    </div>
</div>