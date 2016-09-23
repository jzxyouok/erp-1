<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-20
 * Time: 下午7:20
 */

namespace app\controllers;

use app\components\library\ShowMessage;
use app\components\Tools;
use app\components\TreeMenu;
use app\models\sys\Menu;
use app\models\sys\Role;
use app\models\sys\RoleMenu;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class RoleController extends BaseController
{
    public function actionIndex()
    {$params = Yii::$app->request->queryParams;
        $dataProvider = Role::search($params);
        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }
    public function actionCreate()
    {
        $model = new Role();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->load($post) && $model->save()){
                ShowMessage::info('添加成功', '', Url::toRoute(['/role/index']), 'edit');
            }
        }
        return $this->render('create',[
            'model' => $model,
        ]);
    }
    public function actionUpdate($id)
    {
        $id = Tools::sysAuth($id,'DECODE');
//        if(!is_numeric($id)){
//            ShowMessage::info('找不到该角色', '', Url::toRoute(['/role/index']), 'edit');
//        }
        $model = Role::findOne($id);
        if(empty($model)){
            ShowMessage::info('找不到该角色', '', Url::toRoute(['/role/index']), 'edit');
        }
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->load($post) && $model->save()){
                ShowMessage::info('修改成功', '', Url::toRoute(['/role/index']), 'edit');
            }
        }
        return $this->render('update',[
            'model'=>$model,
        ]);
    }
    public function actionDelete($id)
    {
        $id = Tools::sysAuth($id,'DECODE');
        if(!is_numeric($id)){
            ShowMessage::info('找不到该角色', '', Url::toRoute(['/role/index']), 'edit');
        }
        Role::findOne($id)->delete();
        ShowMessage::info('删除成功', '', Url::toRoute(['/role/index']), 'edit');
    }
    public function actionRoleSetting($id)
    {
        $id = Tools::sysAuth($id,'DECODE');
        return $this->render('roleSetting',[
            'id' => $id
        ]);
    }

    /**
     * 角色权限设置
     */
    public function actionRolePriv($roleId)
    {
        $roleId = $roleId ? intval($roleId) : Yii::$app->request->post('roleId');
        $menuList = RoleMenu::getRoleMenu($roleId);//已经存在的角色权限
        if(Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();//开启事物
            try {
                if($data['menuId'] && $data['mLevel']) {
                    $dataMap = array_combine($data['menuId'], $data['mLevel']);
                    $diffMenuids = array_diff($data['menuId'], $menuList);//新增的权限
                    if (!empty($diffMenuids)) {
                        $insertData = [];
                        foreach ($diffMenuids as $key => $menuid) {
                            $insertData[$key][] = (int)$data['roleId'];
                            $insertData[$key][] = (int)$menuid;

                            $insertData[$key][] = isset($dataMap[$menuid]) ? (int)$dataMap[$menuid] : 0;
                        }
                        if (! Yii::$app->db->createCommand()->batchInsert(RoleMenu::tableName(), ['roleId', 'menuId','level'], $insertData)->execute()) {
                            throw new \Exception('保存失败！');
                        }
                    }
                    $delMenuids = array_diff($menuList, $data['menuId']);//减少的权限
                    if(!empty($delMenuids)){
                        if(! RoleMenu::delRoleMenu($roleId, $delMenuids)){
                            throw new \Exception('删除失败！');
                        }
                    }

                }
                $transaction->commit();
                echo Json::encode(['status' => 200]);
            }catch (\Exception $e) {
                $transaction->rollBack();
                echo Json::encode(['status' => 201]);
            }
            Yii::$app->end();
        } else {
            $result = [];
            $list = Menu::getMenuArray();//获取权限表数据
            foreach($list as $n=>$val){
                $result[$n]['id'] = $val['id'];
                $result[$n]['name'] = $val['name'];
                $result[$n]['parentid'] = $val['parentId'];
                $result[$n]['m'] = $val['m'];
                $result[$n]['c'] = $val['c'];
                $result[$n]['a'] = $val['a'];
                $result[$n]['mlevel'] = $val['level'];
                $result[$n]['listorder'] = $val['sort'];
                $result[$n]['display'] = $val['display'];
            }
            foreach ($result as $n=>$t) {
                $result[$n]['name'] = $t['name'];
                $result[$n]['checked'] = $menuList && in_array($t['id'], $menuList) ? ' checked' : '';
                $result[$n]['level'] = Tools::get_level($t['id'],$result);
                $result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
            }
            $str  = "<tr id='node-\$id' \$parentid_node>
							<td style='padding-left:30px;'>\$spacer
							<input type='checkbox' name='menuid[]' value='\$id' level='\$level' mlevel='\$mlevel' dct-name='\$name' parent_node='\$parentid' \$checked onclick='javascript:checknode(this);'>
							<input type='hidden' name='menulevel[]' value='\$mlevel'>
							  \$name</td>
						</tr>";
            $categorys=TreeMenu::getTree($result,0,0,'','',$str);
            return $this->render('rolePriv',[
                'roleId'=>$roleId,
                'categorys'=>$categorys,
                'result' => $result,

            ]);
        }
    }

}