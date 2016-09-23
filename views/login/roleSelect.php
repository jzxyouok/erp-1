<?php
use yii\helpers\Url;
?>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="webkit" name="renderer">
    <meta content="no-siteapp" http-equiv="Cache-Control">
    <link type="text/css" rel="stylesheet" href="/css/admin/public.css">
    <link type="text/css" rel="stylesheet" href="/css/admin/style.css">
    <title>ERP管理系统</title>
</head>
<body class="index">
<div class="logintopNav">
    <div class="topLogo"></div>
    <span class="topLogoTitle">汇金行ERP管理系统</span>
    <div class="topTextphone"> 总部客服：4008-616-616 </div>
</div>
<div class="loginMain">
    <div class="mainCon ">
        <div class="welcome">
            欢迎您，
            <b id="userloginname" class="blue"><?= $username; ?></b>
            ，请先选择您要登录的角色。
        </div>
        <div class="roleCon">
            <?php if ($roleList): ?>
                <?php foreach ($roleList as $val): ?>
                    <div id="<?= $val->id; ?>" class="role" onclick="location.href = '<?= Url::toRoute(['/login/role-select', 'id' => $val->id]) ?>'">
                        <span><?= $val->name; ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div id="53" class="role" onclick="location.href = '<?= Url::toRoute(['/']) ?>'">
                    <span>进入系统</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>