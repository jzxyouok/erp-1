<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\sys\UserRole;
?>
<style type="text/css">
    html{_overflow-y:scroll}
</style>
<div class="table-list pad-lr-10" style="margin-top:20px;">
    <?php $form = ActiveForm::begin([
        'id' => 'allotRoleForm'
    ]); ?>
    <table width="100%" cellspacing="0">
        <thead>
        <tr>
            <th width="10%" align="center">ID</th>
            <th width="20%" align="center">角色名称</th>
            <th width="25%" align="center">角色描述</th>
        </tr>
        </thead>
        <tbody>
        <?php if($list = $dataProvider->getModels()):?>
            <?php foreach($list as $val):?>
                <tr>
                    <td width="10%" align="center">
                        <input type="checkbox" name="roleId[]" value="<?=$val->id?>" <?php if(in_array($val->id,UserRole::getUserRole($userId))):?>checked<?php endif;?>>
                    </td>
                    <td width="20%" align="center"><?=$val->name;?></td>
                    <td width="25%" align="center"><?=$val->description;?></td>
                </tr>
            <?php endforeach;?>
        <?php endif;?>
        </tbody>
    </table>
    <div class="btn">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>"/>
        <input type="submit" id="dosubmit" class="dialog" value="提交"/>
    </div>
    <?php ActiveForm::end(); ?>
</div>
