<?php
use yii\helpers\Url;
?>
<!DOCTYPE html >
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>后台登录</title>
    <script language="javascript" type="text/javascript" src="/js/admin/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/admin/login.css">
</head>
<body>
<div class="login_box">
    <div class="login_box_top"></div>
    <div class="login_box_mid">
        <div class="login_logo"></div>
        <div class="logo_content">
            <div class="tit">
                <img src="/images/login/guanli_center.gif" />
            </div>
            <form action="<?php echo Url::toRoute('/login/login') ?>"
                  method="post" name="myform" id="myform">
                <ul class="login_list">
                    <li><label>账号</label><input type="text" class="login_input" placeholder="请输入账号"
                                                name="username" id="username" size="18" value="" maxlength="20"/>

                    <li>

                    <li><label>密码</label><input type="password" class="login_input" placeholder="请输入密码"
                                                name="password" id="password" size="18" maxlength="20"/></li>
                    <li><label>验证码</label><input size="8" name="verifyCode"
                                                 type="text" class="ipt ipt_reg"
                                                 onfocus="document.getElementById('yzm').style.display = 'block'" />
                        <div id="yzm" class="yzm">
                            <img id='code_img'
                                 onclick='this.src = this.src + "&" + Math.random()'
                                 src='<?php echo Url::toRoute('/login/checkcode') ?>?a=check'><br />
                            <a
                                href="javascript:document.getElementById('code_img').src='<?php echo Url::toRoute('/login/checkcode') ?>?a='+Math.random();void(0);">看不清</a>
                        </div>

                    <li><input type="submit" class="login_button" name="dosubmit"
                               value="登陆"></li>
                    <input type="hidden" name="_csrf"
                           value="<?= Yii::$app->request->csrfToken ?>" />
                </ul>
            </form>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#username').focus();
            })
        </script>
    </div>
    <div class="login_box_bot"></div>
</div>
</body>
</html>