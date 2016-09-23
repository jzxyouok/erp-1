<?php
use yii\helpers\Url;
use app\components\Acl;
use app\components\Tools;

$isAclAdd = Acl::isAclAuth('','role','create');
$isAclUpdate = Acl::isAclAuth('','role','update');
$isAclDel = Acl::isAclAuth('','role','delete');
$isAclSetting = Acl::isAclAuth('','role','roleSetting');
?>
<div class="pad-6">
    <form name="searchform" action="<?php echo Url::toRoute(['/role/index']); ?>" method="GET">
        <table width="100%" cellspacing="0" class="search-form">
            <tbody>
            <tr>
                <td>
                    <div class="explain-col">
                        角色名称：
                        <input type="text" name="name" value="<?php echo isset($params['name']) ? $params['name'] : ''?>" size="40" placeholder="角色名称" class="input-text">&nbsp;
                        <input type="submit" name="search" class="button" value="搜索">
                        <input type="button" onclick="create()" name="search" class="button" value="添加角色">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <div class="table-list">
        <form name="myform" action="<?php echo Url::toRoute('/role/create'); ?>" method="post">
            <table width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th width="5%" align="center">ID</th>
                    <th width="10%" align="center">角色名称</th>
                    <th width="20%" align="center">角色描述</th>
                    <th width="20%" class="text-c" align="center">管理操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if($list = $dataProvider->getModels()):?>
                    <?php $k= ($dataProvider->pagination->getPage())*PAGE_SIZE;foreach($list as $val):?>
                        <tr>
                            <td width="5%" align="center"><?=++$k;?></td>
                            <td width="10%" align="center"><?=$val->name;?></td>
                            <td width="20%" align="center"><?=$val->description;?></td>
                            <td class="text-c" width="20%" align="center">
                                <?php if($val->id != ROLE_ADMIN_ID):?>
                                    <a href="javascript:allot_priv('<?=$val->name;?>','<?php echo Url::toRoute(['/role/role-setting','id' =>Tools::sysAuth($val->id)]); ?>')" class="isAclAuth" isAcl="<?=$isAclSetting?>">[权限设置]</a>
                                    <a href="javascript:edit('<?=$val->name;?>','<?php echo Url::toRoute(['/role/update','id' =>Tools::sysAuth($val->id)]); ?>')" class="isAclAuth" isAcl="<?=$isAclUpdate?>">[修改]</a>
                                    <a href="javascript:confirmurl('<?=Url::toRoute(['/role/delete', 'id' => Tools::sysAuth($val->id)])?>', '確定要刪除用戶<?=$val->name?>嗎?')" class="isAclAuth" isAcl="<?=$isAclDel?>">[刪除]</a>
                                <?php endif;?>
                            </td>
                        </tr>
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
    $(function(){
        $('#listorder').on('click', function(){
            var id_str = '';
            $('.input_id').each(function(i){
                id_str += $(this).val() + ',';
            })

        });
    });
    /**
     * 添加角色
     */
    function create() {
        window.top.art.dialog({
                title:'添加角色',
                id:'edit',
                iframe:'<?php echo Url::toRoute('/role/create'); ?>' ,
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
     * 编辑角色信息
     */
    function edit(name, url) {
        window.top.art.dialog({
                title:'修改角色'+name+'信息',
                id:'edit',
                iframe:url,
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
     * 分配权限
     */
    function allot_priv(title, url) {
        window.top.art.dialog({
                title:'为角色<font color="red">'+title+'</font>分配权限',
                id:'edit',
                iframe:url,
                width:'700px',
                height:'600px'
            },
            function(){
                return false;
            },
            function(){
                window.top.art.dialog({id:'edit'}).close()
            }
        );
    }
</script>
