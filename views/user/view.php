<?php
use app\models\sys\UserRole;
use app\models\sys\Role;
?>
<style type="text/css">
    html{_overflow-y:scroll}
</style>
<div class="pad-lr-10" style="margin-top:10px;">
    <div class="table-list">
        <div class="common-form">
            <fieldset>
                <legend>基本信息</legend>
                <table width="100%" class="table_form">
                    <tbody><tr>
                        <td width="120">用户名</td>
                        <td><?=$model->username?></td>
                    </tr>
                    <tr>
                        <td>真实姓名</td>
                        <td><?=$model->realName?></td>
                    </tr>
                    <tr>
                        <td>性别</td>
                        <td><?=$model->sex == USER_SEX_FEMALE ? '男' : '女'?></td>
                    </tr>
                    <tr>
                        <td>邮箱</td>
                        <td><?=$model->email?></td>
                    </tr>
                    <tr>
                        <td>手机号码</td>
                        <td><?=$model->mobile?></td>
                    </tr>
                    <tr>
                        <td>出生日期</td>
                        <td><?=$model->birthday?></td>
                    </tr>
                    <tr>
                        <td>所属角色</td>
                        <td>
                            <?php if($rlist = UserRole::getUserRole($model->id)):?>
                                <?php foreach($rlist as $rid):?>
                                    <?php echo Role::getRoleName($rid).'<br />';?>
                                <?php endforeach;?>
                            <?php endif;?>
                        </td>
                    </tr>
                    <tr>
                        <td>状态</td>
                        <td><?php echo $model->status == 1 ? '正常' : '禁用'?></td>
                    </tr>

                    </tbody></table>
            </fieldset>
            <div class="bk15"></div>
            <fieldset>
                <legend>详细信息</legend>
                <table width="100%" class="table_form">
                    <tbody>
                    <tr>
                        <td width="120">注册时间</td>
                        <td><?=date('Y-m-d H:i:s', $model->addTime)?></td>
                    </tr>
                    <tr>
                        <td width="120">最后登陆IP</td>
                        <td><?=$model->lastIp?></td>
                    </tr>
                    <tr>
                        <td width="120">最后登陆时间</td>
                        <td><?php echo $model->lastTime ? date('Y-m-d H:i:s', $model->lastTime) : ''?></td>
                    </tr>
                    </tbody></table>
            </fieldset>
        </div>
        <div class="bk15"></div>
        <input type="button" class="dialog" name="dosubmit" id="dosubmit" onclick="window.top.art.dialog({id:'edit'}).close();">
    </div>
</div>