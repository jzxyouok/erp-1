<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-19
 * Time: 下午3:00
 */

namespace app\models\sys;


use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
class Role extends ActiveRecord
{
    public static function tableName()
    {
        return 'role';
    }
    
    public static function tableDesc()
    {
        return '角色';
    }
    public function rules()
    {
        return [
            [['id','name','description','sort'],'safe'],
        ];
    }

    public static function getRoleName($roleId)
    {
        $role = self::findOne($roleId);
        if(isset($role->name)){
            return $role->name;
        }
        return '';
    }
    
    public static function search($params = [])
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => PAGE_SIZE,
            ],
        ]);
        if(!empty($params['name'])){
            $query->andWhere(['like','name',$params['name']]);
        }
        return $dataProvider;
    }

}