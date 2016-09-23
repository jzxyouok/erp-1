<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\Tools;
?>
<script type="text/javascript" src="/js/admin/select.js"></script>
<?php $form = ActiveForm::begin([
    'id' => 'menuForm',
    'options' => ['enctype' => 'multipart/form-data'],
    'enableClientScript' => false
]); ?>
<div class="common-form">
    <table width="100%" class="table_form contentWrap">
        <tr>
            <th width="120">上级菜单：</th>
            <td><select name="Menu[parentId]" id="menulist">
                    <option value="0" level = "0">根菜单</option>
                    <?=$str;?>
                </select></td>
        </tr>
        <tr>
            <th width="120">描述/名称：</th>
            <td><input type="text" name="Menu[name]" value="<?=$model->name;?>" id="menu_name" class="input-text" >
            </td>
        </tr>
        <tr>
            <th width="120">权限值：</th>
            <td>
                <table>
                    <tr><td><span id="a_tip" class="onShow">1.如果为非功能性菜单,则无需填写!2.功能性菜单分为MCA(模块->控制器->方法)和URL两种,任选其一填写即可</span></td></tr>
                    <tr><td>
                            模块:<input type="text" name="Menu[m]" id="menu_m" value="<?=$model->m;?>" class="input-text" style="width:60px;">
                            控制器:<input type="text" name="Menu[c]" id="menu_a" value="<?=$model->c;?>" class="input-text" style="width:60px;">
                            方法:<input type="text" name="Menu[a]" id="menu_c" value="<?=$model->a;?>" class="input-text"  style="width:60px;">
                            /</td></tr>
                    <tr><td>URL:<input type="text" name="Menu[url]" id="menu_url" value="<?=$model->url;?>" class="input-text" style="width:300px;"></td></tr>
                </table>


            </td>
        </tr>

        <tr>
            <th width="120">描述：</th>
            <td><textarea rows="2" cols="30" name="Menu[description]" class="input-text"><?=$model->description;?></textarea></td>
        </tr>
        <tr>
            <th width="120">是否显示为菜单：</th>
            <td><input type="radio" name="Menu[display]" value="<?=MENU_DISPLAY_YES?>" <?php if($model->display == MENU_DISPLAY_YES):?>checked<?php endif;?>>是
                <input type="radio" name="Menu[display]" value="<?=MENU_DISPLAY_NO?>" <?php if($model->display == MENU_DISPLAY_NO):?>checked<?php endif;?>> 否
            </td>
        </tr>
    </table>
    <div class="bk15"></div>
    <div class="btn">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>"/>
        <input type="button" id="dosubmit" class="dialog" name="dosubmit" value="提交"/>
    </div>
    <?php ActiveForm::end(); ?>
    <script type="text/javascript">
        $(function(){
            $.formValidator.initConfig({formid:"menuForm",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
            $("#menu_name").formValidator({onshow:"",onfocus:"",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"请输入菜单名称"});
            $("#menu_site").formValidator({}).inputValidator({min: 1, onerror: "请选择站点"});
            $("#menu_m").formValidator({empty:true}).regexValidator({regexp:"^[0-9a-z_]{3,20}$",onerror:"模块名称有误"});
            $("#menu_c").formValidator({empty:true}).regexValidator({regexp:"^[0-9a-z_]{3,20}$",onerror:"控制器名称有误"});
            $("#menu_a").formValidator({empty:true}).regexValidator({regexp:"^[0-9a-z-_]{3,20}$",onerror:"方法名称有误"});
            $("#menu_url").formValidator({empty:true}).regexValidator({regExp: "url", dataType: "enum",onerror:"Url格式有误"});
        });
        $("#dosubmit").click(function(){
            var menu_m = $.trim($("#menu_m").val());
            var menu_c  = $.trim($("#menu_c").val());
            var menu_a = $.trim($("#menu_a").val());
            if(menu_m != '' && (menu_c == '' || menu_a == '')){
                artAlert('控制器和方法都不能为空');
                return false;
            }
            if(menu_c != '' && menu_a == ''){
                artAlert('方法都不能为空');
                return false;
            }
            if(menu_a != '' && menu_a == ''){
                artAlert('控制器不能为空');
                return false;
            }
            //获取当前要添加菜单的等级
            var level = parseInt($("#menulist").find("option:selected").attr('level')) + 1;
            $("#menuForm").append('<input type="hidden" name="Menu[level]" value='+level+'>');
            $("#menuForm").submit();
        });
    </script>
