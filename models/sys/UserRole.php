<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-20
 * Time: 上午11:03
 */

namespace app\models\sys;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class UserRole extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_role';
    }
    public static function tableDesc()
    {
        return '用户角色表';
    }
    public function rules()
    {
        return [
            [['userId','roleId'],'safe'],
        ];
    }
    
    public static function getUserRole($userId)
    {
        $roleList = self::find()->select('roleId')->where(['userId'=>$userId])->asArray()->all();
        return ArrayHelper::getColumn($roleList,'roleId');
    }
}