<?php

use yii\helpers\Html;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="/js/admin/jquery.min.js"></script>
    <script language="javascript" src="/js/admin/admin_common.js"></script>
    <script language="javascript"  src="/js/admin/formvalidator.js" charset="UTF-8"></script>
    <script language="javascript" src="/js/admin/formvalidatorregex.js" charset="UTF-8"></script>
    <script language="javascript" src="/js/admin/popup.js" charset="UTF-8"></script>
    <link href="/css/admin/reset.css" rel="stylesheet" type="text/css">
    <link href="/css/admin/system.css" rel="stylesheet" type="text/css">
    <link href="/css/admin/table_form.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/js/calendar/jscal2.css">
    <link rel="stylesheet" type="text/css" href="/js/calendar/border-radius.css">
    <link rel="stylesheet" type="text/css" href="/js/calendar/win2k.css">
    <script type="text/javascript" src="/js/calendar/calendar.js"></script>
    <script type="text/javascript" src="/js/calendar/lang/en.js"></script>
    <link href="/css/admin/zTreeStyle.css" rel="stylesheet" type="text/css">
    <link href="/css/admin/demo.css" rel="stylesheet" type="text/css">
    <script language="javascript" type="text/javascript" src="/js/admin/jquery.ztree.core.js"></script>
    <script language="javascript" type="text/javascript" src="/js/admin/jquery.ztree.excheck.js"></script>
    <title><?= Html::encode(Yii::$app->name) . '-' . $this->title ?></title>
</head>
<body>
<?php $this->beginBody() ?>
<?php echo $content; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>
    $(document).ready(function () {
        $(".Purview_verify").each(function () {
            str = $(this).attr("is_show");
            if (str == 'false') {
                $(this).hide();
            }
        });
        //没有权限的操作都隐藏掉
        $(".isAclAuth").each(function () {
            if ($(this).attr('isacl') == 1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
</script>
