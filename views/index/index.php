<?php
use yii\helpers\Url;
use app\components\Tools;
?>
<!DOCTYPE html>
<html class="off">
<head>
    <title>后台管理中心</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="/css/admin/reset.css" rel="stylesheet" type="text/css" />
    <link href="/css/admin/system.css" rel="stylesheet" type="text/css" />
    <link href="/css/admin/dialog.css" rel="stylesheet" type="text/css">
    <script language="javascript" type="text/javascript"
            src="/js/admin/jquery.min.js"></script>
    <script language="javascript" type="text/javascript"
            src="/js/admin/dialog.js" charset="UTF-8"></script>
    <script language="javascript" type="text/javascript"
            src="/js/admin/popup.js"></script>
</head>
<body scroll="no">
<div class="header">
    <div class="logo lf">
        <a href="<?php echo Url::toRoute('/login/role-select') ?>" ><span
                class="invisible">后台管理系统</span></a>
    </div>

    <div class="col-auto" style="overflow: visible">
        <div class="log white cut_line">
            您好！<span style="color: red"><?php echo Yii::$app->session->get('username');?></span>
            <span style="color: #fff">(<?php echo Yii::$app->session->get('roleName');?>)</span>

            <a href="<?php echo Url::toRoute('/login/logout'); ?>">[退出]</a><span>|</span>
            <a href="<?php echo Url::toRoute('/login/role-select') ?>" id="site_homepage">[切换角色]</a>
        </div>
        <ul class="nav white" id="top_menu">
            <?php if($menuList):?>
                <?php $mNum = 1;?>
                <?php foreach($menuList as $key => $menu):?>
                    <li id="_M<?=$menu['id']?>" class="top_menu <?php if($mNum == 1):?>on<?php endif;?>">
                        <a href="javascript:_MTAB(<?=$menu['id']?>)" hidefocus="true" style="outline: none;">
                            <?=$menu['name']?>
                        </a>
                    </li>
                    <?php $mNum ++;?>
                <?php endforeach;?>
            <?php endif;?>
        </ul>
    </div>
</div>
<div id="content">
    <div class="col-left left_menu">
        <!--------------左侧菜单-------------------->
        <div id="leftMain">
            <?php if($menuList):?>
                <?php $lNum = 1;?>
                <?php foreach($menuList as $key => $topMenu):?>
                    <div id="leftMenu_<?=$key?>" <?php if($lNum != 1):?>style="display: none;"<?php endif;?>>
                        <?php $lNum ++;?>
                        <?php foreach($topMenu['child'] as $dMenu):?>
                            <div class="Purview_verify">
                                <h3 class="f14">
                                    <span class="switchs cu on" title="展开与收缩"></span><?=$dMenu['name']?>
                                </h3>
                                <?php if($dMenu):?>
                                    <ul>
                                        <?php foreach($dMenu['child'] as $dm):?>
                                            <li id="_MP<?=$dm['id']?>" class="sub_menu Purview_verify"><a href="javascript:_MP('<?=$dm['id']?>', '<?=Tools::createUrl($dm['m'], $dm['c'], $dm['a'])?>');" hidefocus="true" style="outline: none;"><?=$dm['name']?></a></li>
                                        <?php endforeach;?>
                                    </ul>
                                <?php endif;?>
                            </div>
                        <?php endforeach;?>
                    </div>
                <?php endforeach;?>
            <?php endif;?>

            <script type="text/javascript">
                $(".switchs").each(function(i){
                    var ul = $(this).parent().next();
                    $(this).click(
                        function(){
                            if(ul.is(':visible')){
                                ul.hide();
                                $(this).removeClass('on');
                            }else{
                                ul.show();
                                $(this).addClass('on');
                            }
                        })
                });
                $(document).ready(function(){
                    $(".Purview_verify").each(function(){ str =  $(this).attr("is_show");if(str == 'false'){
                        $(this).hide();
                    }
                    });
                });
            </script>
        </div>
        <!--------------左侧菜单-------------------->

        <a href="javascript:;" id="openClose"
           style="outline-style: none; outline-color: invert; outline-width: medium;"
           hideFocus="hidefocus" class="open" title="展开与关闭"><span
                class="hidden">展开</span></a>
    </div>
    <div class="col-1 lf cat-menu" id="display_center_id"
         style="display: none" height="100%">
        <div class="content">
            <iframe name="center_frame" id="center_frame" src=""
                    frameborder="false" scrolling="auto" style="border: none"
                    width="100%" height="auto" allowtransparency="true"></iframe>
        </div>
    </div>
    <div class="col-auto mr8">
        <!--			<div class="crumbs">
                        <div class="shortcut cu-span"></div>
                        当前位置:<span id="current_pos"></span>
                    </div>-->
        <div class="col-1">
            <div class="content" style="position: relative; overflow: auto">
                <iframe name="right" id="rightMain"
                        src="<?php echo Url::toRoute('/index/main'); ?>"
                        frameborder="false" scrolling="auto"
                        style="border: none; margin-bottom: 30px" width="1760px"
                        height="auto" allowtransparency="true"></iframe>
                <div class="fav-nav" style="width:1760px;">
                    <div id="panellist">
                        <?php if($panelList)foreach($panelList as $panel):?>
                            <span>
                                    <a onclick="paneladdclass(this);" target="right" href="<?php echo $panel->url;?>"><?php echo $panel->name;?></a>
                                    <a class="panel-delete" data="<?php echo $panel->menuid;?>" ></a>
                                </span>
                        <?php endforeach;?>
                    </div>
                    <div id="paneladd"></div>
                    <input type="hidden" id="menuid" value="">
                    <input type="hidden" id="userid" value="<?php echo Yii::$app->session->get('userid');?>">
                    <input type="hidden" id="menuname" value="">
                    <div id="help" class="fav-help"></div>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //clientHeight-0; 空白值 iframe自适应高度
    function windowW() {
        if ($(window).width() < 980) {
            $('.header').css('width', 980 + 'px');
            $('#content').css('width', 980 + 'px');
            $('body').attr('scroll', '');
            $('body').css('overflow', '');
        }
    }
    windowW();
    $(window).resize(function () {
        if ($(window).width() < 980) {
            windowW();
        } else {
            $('.header').css('width', 'auto');
            $('#content').css('width', 'auto');
            $('body').attr('scroll', 'no');
            $('body').css('overflow', 'hidden');
        }
    });
    window.onresize = function () {
        var heights = document.documentElement.clientHeight - 150;
        document.getElementById('rightMain').height = heights;
        var openClose = $("#rightMain").height() + 39;
        $('#center_frame').height(openClose + 9);
        $("#openClose").height(openClose + 30);
    }
    window.onresize();
    //站点下拉菜单
    $(function () {
        var offset = $(".tab_web").offset();
        $(".tab_web").mouseover(function () {
            $(".tab-web-panel").css({"left": +offset.left + 4, "top": +offset.top + $('.tab_web').height() + 2});
            $(".tab-web-panel").show();
        });
        $(".tab_web span").mouseout(function () {
            hidden_site_list_1()
        });
        $(".tab-web-panel").mouseover(function () {
            clearh();
            $('.tab_web a').addClass('on')
        }).mouseout(function () {
            hidden_site_list_1();
            $('.tab_web a').removeClass('on')
        });
        //默认载入左侧菜单
        //$("#leftMain").load("<?php echo Url::toRoute('/manager/leftmenu/main') ?>");
    })

    //隐藏站点下拉。
    var s = 0;
    var h;
    function hidden_site_list() {
        s++;
        if (s >= 3) {
            $('.tab-web-panel').hide();
            clearInterval(h);
            s = 0;
        }
    }
    function clearh() {
        if (h)
            clearInterval(h);
    }
    function hidden_site_list_1() {
        h = setInterval("hidden_site_list()", 1);
    }

    //左侧开关
    $("#openClose").click(function () {
        if ($(this).data('clicknum') == 1) {
            $("html").removeClass("on");
            $(".left_menu").removeClass("left_menu_on");
            $(this).removeClass("close");
            $(this).data('clicknum', 0);
        } else {
            $(".left_menu").addClass("left_menu_on");
            $(this).addClass("close");
            $("html").addClass("on");
            $(this).data('clicknum', 1);
        }
        return false;
    });

    function _M(menuid, targetUrl) {
        $("#menuid").val(menuid);
        $("#bigid").val(menuid);

        $("#leftMain").load('<?php echo Url::toRoute('/manager/leftmenu/main') ?>?act=' + targetUrl);

        $('.top_menu').removeClass("on");
        $('#_M' + menuid).addClass("on");

        $('#display_center_id').css('display', 'none');
        //显示左侧菜单，当点击顶部时，展开左侧
        $(".left_menu").removeClass("left_menu_on");
        $("#openClose").removeClass("close");
        $("html").removeClass("on");
        $("#openClose").data('clicknum', 0);
        $("#current_pos").data('clicknum', 1);
    }

    /**
     * 切换顶部菜单
     * @param menuid
     * @private
     */
    function _MTAB(menuid){
        $("#leftMenu_"+menuid).show().siblings().hide();

        $("#menuid").val(menuid);
        $("#bigid").val(menuid);
        $('.top_menu').removeClass("on");
        $('#_M' + menuid).addClass("on");

        $('#display_center_id').css('display', 'none');
        //显示左侧菜单，当点击顶部时，展开左侧
        $(".left_menu").removeClass("left_menu_on");
        $("#openClose").removeClass("close");
        $("html").removeClass("on");
        $("#openClose").data('clicknum', 0);
        $("#current_pos").data('clicknum', 1);
    }
    function _MP(menuid, targetUrl) {
        $("#menuid").val(menuid);
        $("#paneladd").html('<a class="panel-add" ><em></em></a>');
        $("#rightMain").attr('src', targetUrl);
        $('.sub_menu').removeClass("on fb blue");
        $('#_MP' + menuid).addClass("on fb blue");

    }

    $(document).on("click", ".panel-add", function(e){
        var menuid = $("#menuid").val();
        //var menuids;
        var menuidarr = new Array();
        $(".panel-delete").each(function(){
            menuidarr.push($(this).attr("data"))
        });
        if($.inArray(menuid, menuidarr)>=0){
            alertpop('已经存在','200','50');
            return false;
        }
        var userid = $("#userid").val();
        var url = $("#rightMain").attr('src');
        var name = $('#_MP' + menuid).find("a").html()
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{"menuid":menuid, "userid":userid, "url":url, "name":name},//json
            url:"/common/ajax/addpanel",//YII 的生成地址
            success:function(data) {//成功获得的也是json对象

                if (data.status==200){
                    var html = '<span>'+
                        '<a onclick="paneladdclass(this);" target="right" href="'+url+'">'+name+'</a>'+
                        '<a class="panel-delete" data="'+menuid+'" ></a>'+
                        '</span>';
                    $("#panellist").append(html);
                }else{
                    console.log(data)
                }
            },
            error:function(){
                alert("服务器出错，请稍后再试！");
            }
        });
    })

    function paneladdclass(id) {
        $("#panellist span a[class='on']").removeClass();
        $(id).addClass('on')
    }

    $(document).on("click", ".panel-delete", function(e){
        var _this = $(this);
        var userid = $("#userid").val();
        var menuid = $(this).attr("data");
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{"menuid":menuid, "userid":userid},//json
            url:"/common/ajax/delpanel",//YII 的生成地址
            success:function(data) {//成功获得的也是json对象
                if(data.status==200){
                    _this.parent().remove();
                }else{

                }
            },
            error:function(){
                alert("服务器出错，请稍后再试！");
            }
        });
    })


    //            function delete_panel(menuid, id) {
    //                console.log(id)
    //                var userid = $("#userid").val();
    //
    //            }

    $(document).bind('keydown', 'return', function (evt) {
        check_screenlock();
        return false;
    });
</script>
</body>
</html>