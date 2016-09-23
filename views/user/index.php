<?php
use yii\helpers\Url;
use app\models\sys\Role;
use app\models\sys\UserRole;
use app\components\Acl;
use app\components\Tools;

$isAclAdd = Acl::isAclAuth('','user','create');
$isAclUpdate = Acl::isAclAuth('','user','update');
$isAclDel = Acl::isAclAuth('','user','delete');
$isAclAllot = Acl::isAclAuth('','user','allot');
?>
<div class="pad-6">
    <form name="searchform" action="<?php echo Url::toRoute(['/user/index']); ?>" method="GET">
        <table width="100%" cellspacing="0" class="search-form">
            <tbody>
            <tr>
                <td>
                    <div class="explain-col">
                        用户名：
                        <input type="text" name="username" value="<?php echo isset($params['username']) ? $params['username'] : ''?>" size="40" placeholder="用户名" class="input-text">&nbsp;
                        手机：
                        <input type="text" name="mobile" value="<?php echo isset($params['mobile']) ? $params['mobile'] : ''?>" size="40" placeholder="手机号码" class="input-text">&nbsp;
                        <input type="submit" name="search" class="button" value="搜索">
                        <input type="button" onclick="create()" name="search" class="button" value="添加用户">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <div class="table-list">
        <form name="myform" action="?m=admin&amp;c=role&amp;a=listorder"
              method="post">
            <table width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th width="2%" align="center">ID</th>
                    <th width="7%" align="center">用户名</th>
                    <th width="7%" align="center">手机号</th>
                    <th width="7%" align="center">出生日期</th>
                    <th width="7%" align="center">所属角色</th>
                    <th width="5%" align="center">状态</th>
                    <th width="10%" align="center">注册时间</th>
                    <th width="7%" align="center">最后登陆时间</th>
                    <th width="12%">管理操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if($data = $dataProvider->getModels()):?>
                <?php $k= ($dataProvider->pagination->getPage())*PAGE_SIZE;foreach($data as $val):?>
                <tr>
                    <td align="center"><?=++$k;?></td>
                    <td align="center"><?=$val->username?><a href="javascript:view('<?=Url::toRoute(['/user/view','id' => Tools::sysAuth($val->id)])?>', '<?=$val->username?>')"><img src="/images/admin/admin_img/detail.png"></a></td>
                    <td align="center"><?=$val->mobile?></td>
                    <td align="center"><?=$val->birthday?></td>
                    <td align="center">
                        <?php if($roleList = UserRole::getUserRole($val->id)):?>
                            <?php foreach($roleList as $rid):?>
                                <?php echo Role::getRoleName($rid).'<br />';?>
                            <?php endforeach;?>
                        <?php endif;?>
                    </td align="center">
                    <td align="center"><?php echo $val->status == USER_STATUS_YES ? '正常' : '禁用'?></td>
                    <td align="center"><?=date('Y-m-d H:i:s', $val->addTime)?></td>
                    <td align="center"><?php echo $val->lastTime ? date('Y-m-d H:i:s', $val->lastTime) : ''?></td>
                    <td align="center">
                        <a href="javascript:edit('<?=Tools::sysAuth($val->id)?>', '<?=$val->username?>')" class="isAclAuth" isAcl="<?=$isAclUpdate?>">[修改]</a>
                        <?php if($val->id != USER_ADMIN_ID):?>
                            <a href="javascript:allot('<?=Tools::sysAuth($val->id)?>', '<?=$val->username?>')" class="isAclAuth" isAcl="<?=$isAclAllot?>">[分配角色]</a>
                            <a href="javascript:confirmurl('<?=Url::toRoute(['/user/delete', 'id' => Tools::sysAuth($val->id)])?>', '確定要刪除用戶<?=$val->username?>嗎?')" class="isAclAuth" isAcl="<?=$isAclDel?>">[刪除]</a>
                        <?php endif;?>
                    </td>
                <tr>
                    <?php endforeach;?>
                    <?php endif;?>
                </tbody>
            </table>
        </form>

        <div class="pagenavi">
            总数:<?=$dataProvider->pagination->totalCount?>,第<?php echo $dataProvider->pagination->getPage() + 1;?>/<?=$dataProvider->pagination->getPageCount()?>页  <?=\yii\widgets\LinkPager::widget(['pagination' => $dataProvider->getPagination(),'options' => ['class' => 'yiiPager'],'activePageCssClass' => 'selected','nextPageLabel' => '下一页','prevPageLabel' => '上一页']);?>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    /**
     * 创建用户
     */
    function create() {
        window.top.art.dialog({
                title:'添加用户',
                id:'edit',
                iframe:'<?php echo Url::toRoute('/user/create')?>' ,
                width:'600px',
                height:'500px'
            },
            function(){
                var d = window.top.art.dialog({id:'edit'}).data.iframe;
                var form = d.document.getElementById('dosubmit').click();
                return false;
            },
            function(){
                window.top.art.dialog({id:'edit'}).close()
            }
        );
    }

    /**
     * 编辑用户信息
     */
    function edit(id, username) {
        window.top.art.dialog({
                title:'修改用户'+username+'信息',
                id:'edit',
                iframe:'<?php echo Url::toRoute(['/user/update'])?>?id='+id,
                width:'600px',
                height:'500px'
            },
            function(){
                var d = window.top.art.dialog({id:'edit'}).data.iframe;
                var form = d.document.getElementById('dosubmit').click();
                return false;
            },
            function(){
                window.top.art.dialog({id:'edit'}).close()
            }
        );
    }

    /**
     * 用户详情
     */
    function view(url, name) {
        window.top.art.dialog({
                title:name+'的个人信息',
                id:'edit',
                iframe: url,
                width:'600px',
                height:'500px'
            },
            function(){
                var d = window.top.art.dialog({id:'edit'}).data.iframe;
                var form = d.document.getElementById('dosubmit').click();
                return false;
            },
            function(){
                window.top.art.dialog({id:'edit'}).close()
            }
        );
    }

    /**
     * 分配角色
     * @param id
     * @param username
     */
    function allot(id, username) {
        window.top.art.dialog({
                title:'为'+username+'分配角色',
                id:'edit',
                iframe:'<?php echo Url::toRoute(['/user/allot-role'])?>?id='+id,
                width:'600px',
                height:'500px'
            },
            function(){
                var d = window.top.art.dialog({id:'edit'}).data.iframe;
                var form = d.document.getElementById('dosubmit').click();
                return false;
            },
            function(){
                window.top.art.dialog({id:'edit'}).close()
            }
        );
    }

</script>