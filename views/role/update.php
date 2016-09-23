<?php
use yii\helpers\Url;
use app\models\sys\Role;
use app\components\Tools;
?>
<style type="text/css">
    html{_overflow-y:scroll}
</style>

<div class="pad_10">
    <div class="common-form">
        <form name="myform" action="<?php echo Url::toRoute(['/role/update','id'=> Tools::sysAuth($model->id)]);?>" method="post" id="myform">
            <table width="100%" class="table_form contentWrap">
                <tr>
                    <th width="100">角色名称：</th>
                    <td><input type="text" name="Role[name]" class="input-text" id="name" value="<?=$model->name;?>"></td>
                </tr>
                <tr>
                    <th width="100">角色描述：</th>
                    <td><textarea name="Role[description]" rows="2" cols="10" id="description" class="inputtext"  style="height:100px;width:400px;"><?=$model->description?></textarea></td>
                </tr>
            </table>
            <div class="bk15"></div>
            <div class="btn"><input type="submit" id="dosubmit" class="dialog" name="dosubmit" value="提交"/></div>
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>"/>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $.formValidator.initConfig({formid: "myform", autotip: true, onerror: function (msg, obj) {
            window.top.art.dialog({content: msg, lock: true, width: '200', height: '50'}, function () {
                this.close();
                $(obj).focus();
            })
        }});

        $("#name").formValidator({onshow:"",onfocus:""}).inputValidator({min:1,empty:{leftEmpty:true,rightEmpty:true,emptyError:""}, onerror: "名称不能为空"});
        $("#description").formValidator({onshow: "", onfocus: "", oncorrect: ""}).inputValidator({min: 0, onerror: "请选择城市"});
    });
</script>