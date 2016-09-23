<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-19
 * Time: 下午1:41
 */

namespace app\controllers;


use app\models\sys\Role;
use app\models\sys\UserRole;
use app\components\Filter;
use yii\web\Controller;
use app\components\checkcode\Checkcode;
use Yii;
use yii\helpers\Url;
use app\components\library\ShowMessage;
use app\models\sys\User;
class LoginController extends Controller
{
    
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

    function actionCheckcode()
    {
        $checkcode = new Checkcode();
        $session = Yii::$app->session;
        $session->set('code', $checkcode->get_code());
        $checkcode->doimage();
    }

    public function actionLogin()
    {
        if(Yii::$app->request->isPost){
            $username = Yii::$app->request->post('username');
            $password = Yii::$app->request->post('password');
            $verifyCode = Yii::$app->request->post('verifyCode');
            $code = Yii::$app->session->get('code');
            $loginUrl = Url::toRoute('/login');
            if(empty($verifyCode) || strtolower($verifyCode) != $code){
                ShowMessage::info("验证码为空或不正确", $loginUrl);
            }
            if(empty($username) || empty($password)){
                ShowMessage::info("用户名或密码不能为空", $loginUrl);
            }
            $userModel = User::findOneByUsername(Filter::filterVal($username));
            if(empty($userModel)){
                ShowMessage::info("用户名不存在", $loginUrl);
            }
            if($password != $userModel->password){
//                var_dump($password);
//                var_dump($userModel->password);die;
                ShowMessage::info("密码错误", $loginUrl);
            }
            if($userModel->status == USER_STATUS_NO){
                ShowMessage::info("用户被禁用",$loginUrl);
            }
            Yii::$app->session->set('userId',$userModel->id);
            Yii::$app->session->set('username',$userModel->username);
            $userModel->lastTime = time();
            $userModel->lastIp = Yii::$app->request->getUserIP();
            if($userModel->save()){
                return $this->redirect('/login/role-select');
            }else{
                ShowMessage::info("登陆失败",$loginUrl);
            }
        }
    }
    public function actionRoleSelect()
    {
        if($id = Yii::$app->request->get('id')){
            Yii::$app->session->set('roleId',$id);
            $roleModel = Role::findOne($id);
            Yii::$app->session->set('roleName',$roleModel->name);
            return $this->redirect('/');
        }

        $userId = Yii::$app->session->get('userId');
        $username =  Yii::$app->session->get('username');
        $loginUrl = Url::toRoute('/login');
        if(empty($userId)){
            ShowMessage::info("登陆失败,当前用户还没有分配角色",$loginUrl);
        }
        $roleIdList = UserRole::getUserRole($userId);
        if(empty($roleIdList)){
            ShowMessage::info("登陆失败,当前用户还没有分配角色",$loginUrl);
        }
        $roleList = Role::find()->where(['in','id',$roleIdList])->all();
        return $this->renderPartial('roleSelect',[
            'roleList' => $roleList,
            'username' => $username,
        ]); 
    }
    public function actionLogout()
    {
        Yii::$app->session->removeAll();
        return $this->redirect(Url::toRoute('/login'));
    }

}