<?php

/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-19
 * Time: 下午2:50
 */
namespace app\models\sys;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Query;
use app\components\Tools;
class Menu extends ActiveRecord
{
    public static function tableName()
    {
        return 'menu';
    }
    public static function tableDesc()
    {
        return '菜单表';
    }
    public function rules()
    {
        return [
            [['id','name','parentId','display','description','sort','url','m','c','a'],'safe'],
        ];
    }

    public static function getMenuTree($roleId,$admin = false)
    {
        if($admin){
            $list = self::find()
                ->where(['display'=>MENU_DISPLAY_YES])
                ->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC])
                ->asArray()
                ->all();
        }else{
            $query = new Query();
            $list = $query->from(['m'=>self::tableName()])
                ->leftJoin(['rm'=>'role_menu'],'m.id=rm.menuId')
                ->where(['m.display'=>MENU_DISPLAY_YES,'rm.roleId'=>$roleId])
                ->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC])
                ->all();
        }
        return Tools::getMenuTree($list);
            
    }

    /**
     * 菜单搜索
     * @param $params
     * @return ActiveDataProvider
     */
    public static function search($params)
    {

        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000
            ],
        ]);
        $query->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC]);
        if (empty($params)) {
            return $dataProvider;
        }

        if(isset($params['name'])) $query->andFilterWhere(['like', 'name', $params['name']]);
        return $dataProvider;
    }
    
    public static function getMenuArray()
    {
        return self::find()
            ->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC])
            ->asArray()
            ->all();
    }
}