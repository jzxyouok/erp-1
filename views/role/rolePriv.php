<link href="/css/admin/jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/admin/jquery.treetable.js"></script>
<style>
    .aui_main{text-align: left;}
    .selBtn{
        color: #FFF;
        border: solid 1px #3399dd;
        background: #2288cc;
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#33bbee',
        endColorstr='#2288cc');
        background: linear-gradient(top, #33bbee, #2288cc);
        background: -moz-linear-gradient(top, #33bbee, #2288cc);
        background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#33bbee),
        to(#2288cc));
        text-shadow: -1px -1px 1px #1c6a9e;
        z-index: 2012;margin-top:20px;
        width:53px;height:26px;
        margin-left:30px;
    }
</style>
<?php if($result) {?>
    <div class="table-list" id="load_priv">
        <form name="myform" id="rolePriv" action="<?php echo \yii\helpers\Url::toRoute(['/role/role-priv'])?>" method="post">
            <input type="hidden" name="roleId" value="<?php echo $roleId?>">
            <table width="100%" cellspacing="0" id="dnd-example">
                <tbody>
                <?php echo $categorys;?>
                </tbody>
            </table>
            <input type="button"  class="selBtn" id="dosubmit" value="分配"/>
        </form>
    </div>
<?php } else {?>
    <center>
        <div class="guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;">
            暂无菜单!
        </div>
    </center>
<?php }?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#dnd-example").treeTable({
            indent: 20,
        });
    });

    /**
     * 递归选中
     * @param pid
     */
    function findNodes(pid, check){
        if($("#"+pid).hasClass('parent')){
            $(".child-of-"+pid).each(function(){
                var id = $(this).attr('id');
                $(this).find('input').prop("checked", check);
                findNodes(id, check);
            });
        }else{
            $("#"+pid).find('input').prop("checked", check);
            $(".child-of-"+pid).find('input').prop("checked", check);
        }
    }

    /**
     * 递归选中父级
     * @param pid
     */
    function findPNodes(obj, check){
        var pid = $(obj).attr('parent_node');
        var selObj = $(".child-of-node-"+pid).find(":input[name='menuid[]']:checked");
        if(selObj.size() == 0){
            check = false;
        }else{
            check = true;
        }
        if(pid != undefined && pid != 0){
            $('#node-'+pid).find('input').prop("checked", check);
            findPNodes($('#node-'+pid).find('input'), check);
        }
    }

    function checknode(obj) {
        //选中子级
        //if($(obj).parent().parent().hasClass('parent')){
        var pid = $(obj).parent().parent().attr('id');
        var check = false;
        if ($(obj).is(":checked") == true) {
            check = true;
        } else {
            check = false;
        }
        findNodes(pid, check);//选中子级
        findPNodes(obj, check);//选中父级
        //}
    }

    $(".selBtn").click(function(){
        var MenuObj = $(":input[level='2']:checked");//查询权限操作为2并且被选中的
        if(MenuObj.size() <= 0)
        {
            artAlert('请选择菜单!');
            return false;
        }else{
            //$("#rolePriv").submit();
            var menuids = [];
            var mlevels = [];
            var selMenuObj =  $(".treeTable").find(":input[name='menuid[]']:checked");//查询权限操作为2并且被选中的
            selMenuObj.each(function(){
                menuids.push(parseInt($(this).val()));
                mlevels.push(parseInt($(this).attr('mlevel')));
            });
            $.ajax({
                'type':'POST',
                'url':'<?php echo \yii\helpers\Url::toRoute(['/role/role-priv','roleId' => $roleId])?>',
                'dataType':"json",
                'data':{roleId:'<?=$roleId?>',menuId:menuids,mLevel:mlevels},
                'success':function(data){
                    if(data.status == 200){
                        artAlert('分配成功!');
                        window.top.art.dialog({id:'edit'}).close();
                    }else{
                        artAlert('分配权限失败!');
                    }
                    return false;
                }
            });
        }

    });
</script>