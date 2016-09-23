<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-20
 * Time: 下午7:19
 */

namespace app\controllers;


use app\components\library\ShowMessage;
use app\components\Tools;
use app\models\sys\Role;
use app\models\sys\User;
use app\models\sys\UserRole;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;

class UserController extends BaseController
{
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $dataProvider = User::search($params);
        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }
    public function actionView($id)
    {
        $id = Tools::sysAuth($id,'DECODE');
        return $this->render('view', [
            'model' => User::findOne($id),
        ]);
    }
    public function actionCreate()
    {
        $model =  new User();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->load($post) && $model->save()){
                ShowMessage::info('添加成功', '', Url::toRoute(['/user/index']), 'edit');
            }
        }
        return $this->render('create',[
            'model' => $model,
        ]);
    }
    
    public function actionUpdate($id)
    {
        $id = Tools::sysAuth($id,'DECODE');
        if(!is_numeric($id)){
            ShowMessage::info('找不到该用户', '', Url::toRoute(['/user/index']), 'edit');
        }
        $model = User::findOne($id);
        if(empty($model)){
            ShowMessage::info('找不到该用户', '', Url::toRoute(['/user/index']), 'edit');
        }
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->load($post) && $model->save()){
                ShowMessage::info('修改成功', '',Url::toRoute(['/user/index']),'edit');
            }
        }
        return $this->render('update',[
            'model'=>$model,
        ]);
    }

    /**
     * @param $id
     * 为用户分配角色
     */
    public function actionAllotRole($id)
    {
        $userId = Tools::sysAuth($id,'DECODE');
        if(!is_numeric($userId)){
            ShowMessage::info('找不到该用户', '', Url::toRoute(['/user/index']), 'edit');
        }
        if(Yii::$app->request->isPost){
            $postList = Yii::$app->request->post('roleId');
            $roleList = UserRole::getUserRole($userId);

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if(empty($postList)){
                    UserRole::deleteAll('userId='.$userId);
                }else{
                    $addRoles = array_diff($postList,$roleList);
                    if(!empty($addRoles)) {
                        $insertData = [];
                        foreach ($addRoles as $key => $value) {
                            $insertData[$key][] = $userId;
                            $insertData[$key][] = $value;
                        }
                        Yii::$app->db->createCommand()->batchInsert(UserRole::tableName(), ['userId', 'roleId'], $insertData)->execute();
                    }
                    $delRoles = array_diff($roleList,$postList );
                    if(!empty($delRoles)){
                        UserRole::deleteAll('userId='.$userId.' AND roleId in('.join(",", $delRoles).')');
                    }
                }
                $transaction->commit();
                ShowMessage::info('修改成功', '', Url::toRoute(['/user/index']), 'edit');
                return true;
            }catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }



        }
        $dataProvider = Role::search();
        return $this->render('allotRole',[
            'dataProvider' => $dataProvider,
            'userId' => $userId,
        ]);
        
    }
    public function actionDelete($id)
    {
        $id = Tools::sysAuth($id,'DECODE');
        if(!is_numeric($id)){
            ShowMessage::info('找不到该用户', '', Url::toRoute(['/user/index']), 'edit');
        }
        User::findOne($id)->delete();
        ShowMessage::info('删除成功', '', Url::toRoute(['/user/index']), 'edit');
    }
}