<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'id' => 'myform',
    'options' => ['enctype' => 'multipart/form-data'],
    'enableClientScript' => false
]); ?>
<table width="100%" class="table_form contentWrap">
    <tr>
        <th width="100">用户名：</th>
        <td><input type="text" name="User[username]" id='username' class="input-text" value="<?= $model->username ?>">
        </td>
    </tr>
    <tr>
        <th width="100">真实姓名：</th>
        <td><input type="text" name="User[realName]" id='realname' class="input-text" value="<?= $model->realName ?>">
        </td>
    </tr>
    <tr>
        <th>密码：</th>
        <td><input type="password" name="User[password]" id='password' class="input-text"></td>
    </tr>
    <tr>
        <th>确认密码：</th>
        <td><input type="password" name="User[repassword]" id='repassword' class="input-text"></td>
    </tr>
    <tr>
        <th>性别：</th>
        <td>
            <input type="radio" name="User[sex]"
                   value="<?= USER_SEX_MALE ?>" <?php echo ($model->sex == USER_SEX_MALE) ? ' checked' : '' ?>>男&nbsp;&nbsp;
            <input type="radio" name="User[sex]"
                   value="<?= USER_SEX_FEMALE ?>" <?php echo ($model->sex == USER_SEX_FEMALE) ? ' checked' : '' ?>>女
        </td>
    </tr>
    <tr>
        <th>手机号：</th>
        <td><input type="text" name="User[mobile]" id='mobile' class="input-text" value="<?= $model->mobile ?>"></td>
    </tr>
    <tr>
        <th>邮箱：</th>
        <td><input type="text" name="User[email]" id='email' class="input-text" value="<?= $model->email ?>"></td>
    </tr>
    <tr>
        <th>出生日期：</th>
        <td><input type="text" name="User[birthday]" id='birthday' class="date input-text"
                   value="<?= $model->birthday ?>" readonly></td>
    </tr>
    <?php if ($model->id != USER_ADMIN_ID): ?>
        <tr>
            <th>状态：</th>
            <td><input type="radio" name="User[status]"
                       value="1" <?php echo ($model->status == USER_STATUS_YES) ? ' checked' : '' ?>> 正常
                <input type="radio" name="User[status]"
                       value="0" <?php echo ($model->status == USER_STATUS_NO) ? ' checked' : '' ?>>禁用
            </td>
        </tr>
    <?php endif; ?>
</table>

<div class="bk15"></div>
<div class="btn">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>"/>
    <input type="submit" id="dosubmit" class="dialog" value="提交"/>
</div>

<?php ActiveForm::end(); ?>
<script>
    $(function () {
        $.formValidator.initConfig({
            formid: "myform", autotip: true, onerror: function (msg, obj) {
                window.top.art.dialog({content: msg, lock: true, width: '200', height: '50'}, function () {
                    this.close();
                    $(obj).focus();
                })
            }
        });
        $("#username").formValidator({onshow: "", onfocus: ""}).inputValidator({
            min: 1,
            empty: {leftEmpty: true, rightEmpty: true, emptyError: ""},
            onerror: "用户名不能为空"
        });
        $("#mobile").formValidator({onshow: "", onfocus: "", oncorrect: ""}).regexValidator({
            regexp: "^1[358][0-9]{9}$",
            onerror: "手机号格式错误"
        });
        $("#email").formValidator({
            onshow: "",
            onfocus: "",
            oncorrect: ""
        }).regexValidator({regexp: "^$|" + regexEnum.email, onerror: "手机号格式错误"});
        $("#password").formValidator({onshow: "请输入密码", onfocus: "请输入密码"}).inputValidator({
            min: 6,
            max: 20,
            onerror: "密码长度为6-20位"
        });
        $("#repassword").formValidator({
            onshow: "请确认密码",
            onfocus: "请输入密码",
            oncorrect: "输入正确"
        }).compareValidator({desid: "password", operateor: "=", onerror: "两次输入的密码不一致"}).inputValidator({
            min: 6,
            max: 20,
            onerror: "密码长度为6-20位"
        });

    });

    Calendar.setup({
        weekNumbers: true,
        inputField : "birthday",
        trigger    : "birthday",
        dateFormat: "%Y-%m-%d",
        showTime: false,
        minuteStep: 1,
        onSelect   : function() {this.hide();}
    });
</script>