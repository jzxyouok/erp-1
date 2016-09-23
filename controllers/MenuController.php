<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-20
 * Time: 下午7:31
 */

namespace app\controllers;

use app\components\BaseCache;
use app\components\library\ShowMessage;
use app\models\sys\Menu;
use Yii;
use app\components\Acl;
use yii\helpers\Json;
use yii\helpers\Url;
use app\components\Tools;
use app\components\TreeMenu;
class MenuController extends BaseController
{
    public function actionIndex()
    {
        
        $params = Yii::$app->request->queryParams;
        //获取redis缓存
        $data_json = BaseCache::get('menu_redis33');
        if(empty($data_json) || !empty($params)) {
            $dataProvider = Menu::search($params);
            $data = array();
            if ($list = $dataProvider->getModels()) {
                $arr = $list;
                $isAclAdd = Acl::isAclAuth('', 'menu', 'create');
                $isAclUpdate = Acl::isAclAuth('', 'menu', 'update');
                $isAclDel = Acl::isAclAuth('', 'menu', 'delete');
                foreach ($list as $val) {
                    $r['id'] = $val['id'];
                    $r['listorder'] = $val['sort'];
                    $res = self::_checkParent($arr, $r['id']);
                    $r['name'] = $res == 2 ? '&nbsp;&nbsp;&nbsp;&nbsp;' . $val['name'] : $val['name'];
                    $r['description'] = $val['description'];
                    $r['parentid'] = $val['parentId'];
                    $r['display'] = $val['display'] ? '是' : '否';
                    $r['parentid_node'] = ($val['parentId']) ? ' class="child-of-node-' . $val['parentId'] . '"' : '';
                    $delurl = Url::toRoute(['/menu/delete', 'id' => Tools::sysAuth($val['id'])]);
                    $r['str_manage'] = ' <a style="color: green;" href="javascript:createsonmenu(\'' . Tools::sysAuth($val['id']) . '\');" class="isAclAuth" isAcl="' . $isAclAdd . '">[添加子菜单]</a> <a style="color: blue;" href="javascript:edit(\'' . Tools::sysAuth($val['id']) . '\',\'' . $val['name'] . '\');" class="isAclAuth" isAcl="' . $isAclUpdate . '">[修改]</a> <a style="color: red;" href="javascript:confirmurl(\'' . $delurl . '\',\'确定要删除' . $val['name'] . '吗？\');" class="isAclAuth" isAcl="' . $isAclDel . '">[删除]</a>';
                    $data[] = $r;
                }
            }
            if(empty($data_json) && empty($params)){
                BaseCache::delete('menu_redis');
                BaseCache::set('menu_redis', Json::encode($data),3600);
            }
        }else{
            $data = Json::decode($data_json);
        }

        $str = "<tr id='node-\$id' \$parentid_node>
                <td align='left'>\$name</td>
                <td align='left'><input type='text' value='\$listorder' name='listorder[]' class='listorder' data-menuid='\$id' data-name='\$name' data-listorder='\$listorder' onblur='changeorder(this)' style='width:40px;'></td>
                <td align='left'>\$description</td>
                <td align='left'>\$display</td>
                <td align='left'>\$str_manage</td>
            </tr>";

        $categorys = TreeMenu::getTree($data, 0, 0, $adds = '', $str_group = '', $str);

        $showWay = 1;//1为折叠显示。2为缩进显示
        return $this->render('index', [
            'data' => $data,
            'categorys'=>$categorys,
            'showWay' => $showWay,
        ]);
    }
    public function actionCreate()
    {
        $model = new Menu();
        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->save()) {
                BaseCache::delete('menu_redis');
                ShowMessage::info('添加成功','',Url::toRoute(['/menu/index']),'edit');
            }
        }else {
            $data = [];
            $Nodes = $model->find()->select('id,name,parentId,level')->all();
            foreach($Nodes as $i => $item){
                $data[$i + 1]=['id'=>$item->id,'parentid'=>$item->parentId,'name'=>$item->name];
                $data[$i + 1]['level'] = $item->level + 1; //Tools::get_level($item->id, $Nodes) + 1;
            }
            $str=TreeMenu::getTree($data,0,0,$adds='',$str_group ='',$str = "<option value=\$id level=\$level \$selected>\$spacer\$name</option>");
            return $this->render('create', [
                'model' => $model,
                'str'=>$str,
            ]);
        }
    }
    public function actionCreateChild($id)
    {
        $id = Tools::sysAuth($id,'DECODE');
        
        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model = new Menu();
            if ($model->load( $post ) && $model->save()) {
                ShowMessage::info('添加成功','',Url::toRoute(['/menu/index']),'edit');
            }
        }else {
            $model = Menu::findOne($id);
            return $this->render('createChild', [
                'model' => $model
            ]);
        }
    }
    public function actionUpdate($id)
    {
        $id = Tools::sysAuth($id,'DECODE');
        $model = Menu::findOne($id);
        if(empty($model)){
            ShowMessage::info('修改失败，找不到该菜单！','',Url::toRoute(['/menu/index']),'edit');
        }

        if (Yii::$app->request->isPost) {
            if($model->load(Yii::$app->request->post()) && $model->save()){
                BaseCache::delete('menu_redis');
                ShowMessage::info('修改成功','',Url::toRoute(['/menu/index']),'edit');
            }
        } else {
            $data = [];
            $Nodes = $model->find()->select('id,name,parentId,level')->all();
            foreach($Nodes as $i => $item){
                $data[$i+1]=['id'=>$item->id,'parentid'=>$item->parentId,'name'=>$item->name];
                $data[$i+1]['level'] = $item->level + 1;//Tools::get_level($item->id, $Nodes) + 1;
            }
            $str=TreeMenu::getTree($data,0,$model->parentId,$adds='',$str_group ='',$str = "<option value=\$id level=\$level \$selected>\$spacer\$name</option>");

            return $this->render('update', [
                'model' => $model,
                'str'=>$str,
            ]);
        }
    }
    public function actionDelete($id)
    {
        $id = Tools::sysAuth($id,'DECODE');
        if(Menu::findOne($id)->delete())
            ShowMessage::info('删除成功','',Url::toRoute(['/setting/menu/index']));
    }

    private static function _checkParent($arr,$id){
        foreach($arr as $v){
            if($v['parentId'] == $id){
                return 1;
            }
        }
        return 2;

    }
    
}