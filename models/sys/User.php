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

class User extends ActiveRecord
{
    public static function tableName()
    {
        return 'user';
    }
    
    public static function tableDesc()
    {
        return '用户表';
    }

    public function rules()
    {
        return [
            [['id','username','password','mobile','email','realName','sex','lastIp','lastTime','status','birthday','card','addTime'],'safe'],
        ];
    }

    /**
     * @param $string
     * @return string
     * 字符串的哈希加密，用于转换成数据库存储的密码格式，对比密码是否正确。
     */
    public static function hashPwd($string)
    {
        return md5(md5(strtolower($string)));
    }

    /**
     * @param bool $insert
     * @return bool
     * 存储前把密码加密。
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->isNewRecord){
                $this->addTime = time();
            }
            //$this->password && $this->password = self::hashPwd($this->password);
            return true;
        }
        return false;
    }

    /**
     * @param $username
     * @return array|null|ActiveRecord
     * 登陆时验证用户名是否存在，并获取用户model。
     */
    public static function findOneByUsername($username)
    {
        return self::find()->where(['username'=>$username])->one();
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
        if(!empty($params['username'])){
            $query->andWhere(['like','username',$params['username']]);
        }
        if(!empty($params['mobile'])){
            $query->andWhere(['like','mobile',$params['mobile']]);
        }
        return $dataProvider;
    }
    
}