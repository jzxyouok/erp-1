<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-20
 * Time: 下午7:44
 */

namespace app\components;


use yii\base\Component;
use yii\db\Query;
use Yii;
class Acl extends Component
{
    private static $userTable = 'user';

    private static $userRoleTable = 'user_role';

    private static $roleMenuTable = 'role_menu';

    private static $menuTable = 'menu';

    public static function isAdmin($roleId)
    {
        return $roleId == USER_ADMIN_ID;
    }

    /**
     * 页面中是否显示该功能
     * @param string $m
     * @param string $c
     * @param string $a
     * @return mixed
     */
    public static function isAclAuth($m = '', $c = '', $a = ''){
        $roleId = Yii::$app->session->get('roleId');
        if(self::isAdmin($roleId)){
            return true;
        }
        return self::isAllow($roleId,$m,$c,$a);
    }

    public static function isAllow($roleId,$m,$c,$a)
    {
        if(self::isAdmin($roleId)){
            return true;
        }
        $query = new Query();
        $query->from(['m'=>self::$menuTable]);
        $query->leftJoin(['rm'=> self::$roleMenuTable],'rm.menuId=m.id');
        $query->where('rm.roleId=:roleId AND m.m=:m AND m.c=:c AND m.a=:a',[':roleId'=>$roleId,':m'=>$m,':c'=>$c, ':a' => $a]);
        $data=[];
        foreach($query ->all() as $row){
            $data[] = $row;
            if(!empty($row)) break;
        }
        if($data){
            return true;
        }

        return false;
    }
}