<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-19
 * Time: 下午3:11
 */

namespace app\models\sys;


use yii\db\ActiveRecord;

class UserPanel extends ActiveRecord
{
    public static function tableName()
    {
       return 'user_panel';
    }
    public static function tableDesc()
    {
        return '用户常用属性';
    }
    public function rules()
    {
        return [
            [['userId','menuId','name','url'],'safe'],
        ];
    }
    public static function getMenuGroup($userId)
    {
        
    }
}