<?php
use yii\helpers\Url;
use common\components\Tools;
use app\components\Acl;
$isAclAdd = Acl::isAclAuth('','menu','create');
?>
<?php if($showWay == 1):?>
    <link href="/css/admin/jquery.treeTable.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/js/admin/jquery.treetable.js"></script>
    <script>
        //屏蔽下面js则把所有组织机构都展示出来，若不屏蔽则折叠展示
        $(document).ready(function() {
            $("#dnd-example").treeTable({
                indent: 20
            });
        });
    </script>
<?php endif;?>

<style type="text/css">
    html{_overflow-y:scroll}
</style>
<div class="pad-6">
    <form name="searchform" action="<?php echo Url::toRoute(['/menu/index']); ?>" method="get">
        <table width="100%" cellspacing="0" class="search-form">
            <tbody>
            <tr>
                <td>
                    <div class="explain-col">
                        名称：
                        <input type="text" name="name" value="<?=Yii::$app->request->get('name')?>" size="20" class="input-text">&nbsp;
                        <input type="submit" class="button" value="搜索">
                        <input type="button" onclick="create()" class="button isAclAuth" isAcl="<?=$isAclAdd?>" value="新增菜单">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <div class="table-list">
        <table width="100%" cellspacing="0" id="dnd-example">
            <thead>
            <tr>
                <th width="20%" align="left">菜单名称</th>
                <th width="20%" align="left">排序</th>
                <th width="20%" align="left">描述</th>
                <th width="20%" align="left">是否显示</th>
                <th width="20%" align="left">管理操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if($categorys){
                echo $categorys;
            }?>
            </tbody>

        </table>
    </div>
</div>
<script type="text/javascript">

    function create() {
        window.top.art.dialog({
                title:'添加菜单',
                id:'edit',
                iframe:'<?php echo Url::toRoute(['/menu/create'])?>',
                width:'800px',
                height:'460px'
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

    function createsonmenu(id) {
        window.top.art.dialog({
                title:'添加子菜单',
                id:'edit',
                iframe:'<?php echo Url::toRoute(['/menu/create-child'])?>?id='+id,
                width:'800px',
                height:'460px'
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
     * 编辑菜单信息
     */
    function edit(id, username) {
        window.top.art.dialog({
                title:'修改菜单-'+username+'信息',
                id:'edit',
                iframe:'<?php echo Url::toRoute(['/menu/update'])?>?id='+id,
                width:'800px',
                height:'460px'
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
     * 修改菜单顺序
     */
    function changeorder(obj){
        var name = $(obj).attr('data-name');
        var listorder = $(obj).val();
        var oldlistorder = $(obj).attr('data-listorder');
        var menuid = parseInt($(obj).attr('data-menuid'));
        if(listorder != oldlistorder){
            //artConfirm('确定要修改'+name+'菜单排序吗?', function(){
            $.ajax({
                'type':'POST',
                'url':'<?php echo Url::toRoute(['/setting/menu/updateorder'])?>',
                'dataType':"json",
                'data':{id:menuid,listorder:listorder},
                'success':function(data){
                    if(data.status == 201){
                        artAlert('更新失败!');
                        // alert('更新失败!');
                    }
                    if(data.status == 200){
                        //alert('更新成功!');
                        artAlert('更新成功!');
                    }
                }
            });

            //});
        }
    }

    /**
     * 只能输入数字
     */
    $('.listorder').keydown(function(event){
        var theEvent = window.event||event;
        var keycode = theEvent.keyCode || theEvent.which;
        if(!(keycode==46)&&!(keycode==8)&&!(keycode==37)&&!(keycode==39))
            if(!(( keycode >= 48 && keycode <= 57) || (keycode>=96 && keycode<=105))){
                if(document.all)
                {
                    window.theEvent.returnValue = false;
                }else{
                    theEvent.preventDefault();
                }
            }
    });
</script>