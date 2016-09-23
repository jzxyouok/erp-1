<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-19
 * Time: 下午1:33
 */

namespace app\controllers;


use app\components\Acl;
use app\components\Tools;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\sys\Menu;
use app\models\sys\UserPanel;
use Yii;
use yii\web\MethodNotAllowedHttpException;

class BaseController extends Controller
{
    public $menu;
    public $panel;
    public $roleId;
    public $userId;
    public $cityId;
    public $realName;
    public $roleName;
    function init()
    {
        parent::init();
        $loginUrl = Url::toRoute('/login/index');
        $this->userId = Yii::$app->session->get('userId');
        $this->roleId = Yii::$app->session->get('roleId');
        $this->realName = Yii::$app->session->get('realName');
        $this->roleName = Yii::$app->session->get('roleName');

        if(!$this->userId && !$this->roleId){
            return $this->redirect($loginUrl);
        }
        $this->menu = $this->_getMenuTree($this->roleId);
        $this->panel = UserPanel::getMenuGroup($this->userId);
    }
    /**
     * 在每个action进行权限验证
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\base\ExitException
     */
    public function beforeAction($action)
    {
        $m = $this->module->id;
        $c = Yii::$app->controller->id;
        $a = Yii::$app->controller->action->id;

        if($m == APP_ID){
            $m = '';
        }
        if(!$this->userId){
            if($c != 'index' || $a != 'index'){
                header("Location: /index/index");
                die;
            }
        }

        if($c != 'index' || $a != 'index'){
            if (false === Acl::isAllow($this->roleId, $m, $c, $a)) {
                $url = Tools::createUrl($m, $c, $a);
                throw new MethodNotAllowedHttpException('抱歉,您没有访问' . $url . '页面的权限', 405);
            }
       }
        return true;
    }

    public function isAdmin()
    {
        return Acl::isAdmin($this->roleId);
    }
    private function _getMenuTree($roleId)
    {
        return Menu::getMenuTree($roleId,self::isAdmin());
    }
    
    
}