<?php
use yii\helpers\Url;
?>
<body scroll="no">
<div style="padding:6px 3px">
    <div class="col-2 col-left mr6" style="width:140px">
        <h6><img src="/images/admin/sitemap-application-blue.png" width="16" height="16" /> 站点选择</h6>
        <div id="site_list">
            <ul class="content role-memu" >
                <li><a href="<?=Url::toRoute(['/role/role-priv','roleId'=> $id]); ?>" target="role"><span><img src="/images/admin/gear_disable.png" width="16" height="16" />设置</span><em>管理后台</em></a></li>
            </ul>
        </div>
    </div>
    <div class="col-2 col-auto">
        <div class="content" style="padding:1px">
            <iframe name="role" id="role" src="" frameborder="false" scrolling="auto" style="overflow-x:hidden;border:none" width="100%" height="483" allowtransparency="true"></iframe>
        </div>
    </div>
    <input type="button" class="dialog" name="dosubmit" id="dosubmit" onclick="window.top.art.dialog({id:'edit'}).close();">
</div>
</body>
</html>
