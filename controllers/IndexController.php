<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-19
 * Time: 下午1:32
 */

namespace app\controllers;


class IndexController extends BaseController
{
    public function actionIndex()
    {
        $this->layout = FALSE;
        return $this->render('index', [
            'menuList' => $this->menu,
            'panelList' => $this->panel
        ]);
    }
    public function actionMain()
    {
        return $this->render('main');
    }
}