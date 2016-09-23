<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-20
 * Time: 上午11:06
 */

namespace app\models\sys;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class RoleMenu extends ActiveRecord
{
    public static function tableName()
    {
        return 'role_menu';
    }
    public static function tableDesc()
    {
        return '角色菜单表';
    }
    public function rules()
    {
        return [
            [['roleId','menuId'],'safe']
        ];
    }
    public static function getRoleMenu($roleId = 0)
    {
        $menuList = self::find()->select('menuId')->where('roleId=:roleId',['roleId'=>$roleId])->asArray()->all();
        return ArrayHelper::getColumn($menuList,'menuId');
    }

    /**
     * 根据角色id和菜单id删除
     * @param $roleid
     * @param array $menuids
     * @return bool|int
     */
    public static function delRoleMenu($roleId, $menuids = []){
        if(!$roleId || !$menuids) return false;
        if(is_array($menuids)){
            return self::deleteAll([
                'and',
                'roleId = :roleId',
                ['in', 'menuId', $menuids]
            ],[':roleId' => $roleId]);
        }else{
            return self::deleteAll(['roleId=:roleId AND menuId=:menuId', [':roleId' => $roleId,':menuId' => $menuids]]);
        }
    }
}