<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $GLOBALS['ym_title']; ?></title>
    <meta name="keywords" content="<?php echo $ym_keywords; ?>">
    <meta name="description" content="<?php echo $ym_description; ?>">
    <link rel="stylesheet" href="<?php echo $css; ?>/iconfont.css">
    <link rel="stylesheet" href="<?php echo $css; ?>/common.css">
    <link rel="stylesheet" href="<?php echo $css; ?>/index.css">
    <style>
        .nav .menu-mainnav {
            display: block;
        }

        .slidebanner .bd ul li {
            display: none
        }

        .slidebanner .bd ul li:first-child {
            display: block
        }
    </style>
    <link rel="stylesheet" href="<?php echo $public; ?>/js/layer/skin/layer.css" id="layui_layer_skinlayercss" style="">
</head>
<body class="greybg">
<?php include $this->arrayConfig['compiledir'].'/'.md5('header').'.php'; ?>
<div class="banner">
    <div class="slidebanner">
        <div class="hd">
            <ul>
                <li class="">1</li>-->
                <li class="">2</li>-->
                <li class="on">3</li>-->
                <li class="">4</li>-->
                <li class="">5</li>-->
            </ul>
        </div>
        <div class="bd">
            <ul>

                <?php if(is_array($indexbanner)){foreach($indexbanner as $v){ ?>
                <li style="display: none;"><a href="<?php echo $v['url']; ?>"
                                         style="background:url(<?php echo $v['img']; ?>) no-repeat top center"></a>
                </li>
                <?php }} ?>
            </ul>
        </div>
    </div>
    <div class="nb">
        <div class="lognew">
            <div class="login-quick">
                <?php if($user_name!='') { ?>                <a href="user.html" class="head"><img src="<?php if($user[img]) { ?>                <?php echo $user[img]; ?>
                <?php }else{ ?><?php echo $images; ?>/avatar.jpg
                <?php } ?>" alt="70*70"/></a>
                <p>Hi~您好！</p>
                <?php if($user[grade_name]) { ?>-->
                <a href="user.html" class="btn-login"><?php echo $user[grade_name]; ?></a>
                <?php } ?>-->
                <a href="user.html" class="btn-regist"><?php echo $user_name; ?></a>
                <?php }else{ ?>
                <a href="login.html" class="head"><img src="$images/avatar.jpg" alt="70*70"/></a>
                <p>Hi~您好！</p>
                <a href="login.html" class="btn-login">请登录</a>
                <a href="reg.html" class="btn-regist">免费注册</a>
                <?php } ?>            </div>
            <div class="new-quick">
                <div class="quicknew-bar">
                    <span>资讯</span><a href="n-news.html" target="_blank" class="rside">更多</a>
                </div>
                <ul>
                    <?php if(is_array($diy_mobiletuisong)){foreach($diy_mobiletuisong as $p){ ?>-->
                    <li><a href="<?php echo $p['url']; ?>"><?php echo $p[name]; ?></a></li>
                    <?php }} ?>-->
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="nb">
        <?php echo $diy_indexad; ?>
        <?php if($diy_timespike) { ?>        <div class="time-limit">
            <div class="timeslimit-bar">
                <span>限时秒杀</span>
                <p data-time="<?php echo $nowtime; ?>" class="time" id="time1">还剩：<span id="v_day1">0</span>天<span
                        id="v_hour1">0</span>小时<span id="v_minute1">0</span>分<span id="v_second1">0</span>秒</p>

                <a href="timespike.html" target="_blank" class="rside">查看全部 &gt;</a>
            </div>
            <div class="slide-timeslimit" style="height: 268px;">
                <div class="prev-next">
                    <a href="javascript:void(0);" class="prev"></a>
                    <a href="javascript:void(0);" class="next"></a>
                </div>
                <div class="bd">
                    <ul>
                        <?php if(is_array($spike['goods'])){foreach($spike['goods'] as $p){ ?>-
                        <li>
                            <div class="nr">
                                <a class="picbox" href="<?php echo $p[url]; ?>" target="_blank"><img src="<?php echo $p[thumb]; ?>" alt=""/></a>
                                <p class="word"><a href="<?php echo $p[url]; ?>"><?php echo $p[name]; ?></a></p>
                                <div class="pricebox">
                                    <div class="price">￥<span><?php echo $p[price]; ?></span></div>
                                    <del>￥<?php echo $p[marketprice]; ?></del>
                                </div>
                            </div>
                        </li>
                        <?php }} ?>
                    </ul>
                </div>
                <div class="hd" style="display: none;">
                    <ul>
                        <li class="on">1</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php } ?>        <div class="floor-fontainer">
            <?php if($diy_indexfloor) { ?>            <div class="leftgood-bar" style="left: 0px; display: none;">
               <?php if(is_array($diy_indexfloor)){foreach($diy_indexfloor as $k=>$c){ ?>
                <div class="lgb <?php if(!$k) { ?>                            onlgb
                            <?php } ?>                            lgb<?php echo $k; ?>">
                    <a href="javascript:void(0)" name="#floor<?php echo $k; ?>">
                        <span><?php echo $c[name]; ?></span>
                    </a>
                </div>
              <?php }} ?>


            </div>
            <div class="rightgood-body">
                <?php if(is_array($diy_indexfloor)){foreach($diy_indexfloor as $k=>$c){ ?>
                <div class="floor floor<?php echo $k; ?>" id="floor<?php echo $k; ?>">
                    <div class="floor-title">
                        <h3><a href="<?php echo $c[url]; ?>"><?php echo $c[name]; ?></a></h3>
                        <a href="<?php echo $c[url]; ?>" class="more">查看全部 &gt;</a>
                        <ul class="navson">
                            <?php if(is_array($c[child])){foreach($c[child] as  $p){ ?>
                            <li><a href="<?php echo $p[url]; ?>"><?php echo $p[name]; ?></a></li>
                            <?php }} ?>
                        </ul>
                    </div>
                    <div class="floornr">
                        <div class="itemsale">
                            <a href="<?php echo $c[url]; ?>" class="picbox">
                                <img src="<?php echo $c[img]; ?>" alt="" style="background-image:url(/public/images/icon-duo.png)"/>
                            </a>
                            <a href="<?php echo $c[url]; ?>" class="itemtitle">
                                <h4 class="ellipsis"><?php echo $c[name]; ?></h4>
                                <p class="ellipsis"><?php echo $c[remark]; ?></p>
                            </a>
                        </div>
                        <div class="style-sale">
                            <ul>
                               <?php if(is_array($c[goods])){foreach($c[goods] as $v){ ?>
                                <li>
                                    <a href="<?php echo $v['url']; ?>" target="_blank">
                                        <h4 class="ellipsis"><?php echo $v['name']; ?></h4>
                                        <p class="ellipsis"><?php echo $v['subname']; ?></p>
                                        <span class="picbox">
													<img src="<?php echo $v['thumb']; ?>" alt="238*130"/>
												</span>
                                    </a>
                                </li>
                               <?php }} ?>
                            </ul>
                        </div>
                        <div class="brand-sale">
                            <ul>
                                <?php if(is_array($c['brands'])){foreach($c['brands'] as $b){ ?>
                                <li><a href="<?php if($b[url]) { ?>                                             <?php echo $b[url]; ?>
                                             <?php }else{ ?>../brand.html?id=<?php echo $b[id]; ?>
                                             <?php } ?>" target="_blank">
                                            <img src="<?php echo $b[logo]; ?>" alt="80*60" width="60" height="60"/></a></li>
                                <?php }} ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php }} ?>
            </div>
            <?php } ?>        </div>
        猜你喜欢-->
       <?php if($remmend_goods) { ?>        <div class="guesslike">
            <div class="title-ab">
                <h4>猜你喜欢</h4>
            </div>
            <ul class="yourlike indyourlike">
                <?php if(is_array($remmend_goods)){foreach($remmend_goods as $p){ ?>
                <li>
                    <a href="<?php echo $p[url]; ?>" class="picbox" target="_blank">
                        <img src="<?php echo $p[thumb]; ?>" alt=""/>
                    </a>
                    <div class="elli">
                        <a href="<?php echo $p[url]; ?>" target="_blank"><?php echo $p[name]; ?></a>
                    </div>
                    <a href="<?php echo $p[url]; ?>" class="price" target="_blank">￥<span><?php echo $p[price]; ?></span></a>
                </li>
                <?php }} ?>
            </ul>
        </div>
       <?php } ?>    </div>
</div>
<?php include $this->arrayConfig['compiledir'].'/'.md5('footer').'.php'; ?>
<?php include $this->arrayConfig['compiledir'].'/'.md5('toolbar').'.php'; ?>
<script src="<?php echo $public; ?>/default/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="<?php echo $public; ?>/default/js/jquery.SuperSlide.2.1.1.js" type="text/javascript"></script>
<script src="<?php echo $public; ?>/default/js/main.js" type="text/javascript"></script>

<script>

    $(".nav .nav-classify").addClass("nav-classifyopaity");
    $(function () {
        loadLayer();
    });
    jQuery(".slidebanner").slide({mainCell: ".bd ul", titCell: ".hd ul", autoPlay: true, autoPage: true});
    jQuery(".slide-timeslimit").slide({
        titCell: ".hd ul",
        mainCell: ".bd ul",
        autoPage: true,
        effect: "left",
        autoPlay: false,
        scroll: 6,
        vis: 6
    });
    //秒杀计时
    InterValObj1 = window.setInterval(function () {
        SetRemainTime(1);
    }, 1000);
    //侧栏浮动定位显示隐藏
    $(window).scroll(function () {
        var topH = $(".header").height() + $(".banner").height();
        var footH = $(document).height() - $(".footer").height() - 500;

        if ($(this).scrollTop() > topH - 250 && $(this).scrollTop() <= footH) {
            $(".leftgood-bar").fadeIn(200);
        } else {
            $(".leftgood-bar").fadeOut(200);
        }
    });
    setLeftBar();
    function setLeftBar() {
        if ($(window).width() > 1800) {
            $(".leftgood-bar").css("left", '250px');
        }
        if ($(window).width() <= 1800) {
            $(".leftgood-bar").css("left", '120px');
        }
        if ($(window).width() < 1600) {
            $(".leftgood-bar").css("left", '0px');
        }
    }
    $(window).resize(function () {
        setLeftBar();
    });
    $(".leftgood-bar .lgb").each(function () {
        $(this).click(function () {
            $("body ,html").animate({scrollTop: $($(this).find("a").attr("name")).offset().top}, 500);

        });
    });
    $(window).scroll(function () {
        var scrollTop = $(window).scrollTop();
        var len = $(".leftgood-bar").children().length;
        for (var i = 0; i < len; i++) {
            if (scrollTop + 250 >= $("#floor" + i).offset().top) {
                $(".lgb").eq(i).addClass("onlgb").siblings().removeClass("onlgb");
            }
        }
    });
    //ie hask
    var DEFAULT_VERSION = "8.0";
    var ua = navigator.userAgent.toLowerCase();
    var isIE = ua.indexOf("msie") > -1;
    var safariVersion;
    if (isIE) {
        safariVersion = ua.match(/msie ([\d.]+)/)[1];
    }
    if (safariVersion <= DEFAULT_VERSION) {
        $(".indyourlike li:nth-child(5n)").css("margin-right", "0px");
        $(".cont-item a:last-child").css("margin-right", "0px");
        $(".time-limit .slide-timeslimit .bd li:nth-child(6n)").children(" .nr").css("border-right", "none");
    }
</script>


<script src="<?php echo $public; ?>/js/layer/layer.min.js"></script>

</body>
</html>