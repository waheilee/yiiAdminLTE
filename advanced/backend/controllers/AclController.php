<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;

class AclController extends DashboardController
{

    /**
     * 权限信息
     * @return string
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $items = $auth->getPermissions();
        return $this->render('index',compact('items'));
    }

    /**
     * 创建权限
     * @return string
     */
    public function actionCreate()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $auth = Yii::$app->authManager;
            if(in_array($post['name'],array_keys($auth->getPermissions()))){
                $this->addFlash('error','该权限重复添加了！');
            }else{
                $acl = $auth->createPermission($post['name']);
                $acl->description = $post['description'];
                $auth->add($acl);
                $this->addFlash('success',"成功添加`{$acl->description}`权限！");
            }
        }
        return $this->render('create');
    }

    /**
     * 修改权限
     * @return string
     */
    public function actionUpdate()
    {
        $name = Yii::$app->request->get('name');
        $auth = Yii::$app->authManager;
        $item = $auth->getPermission($name);
        if(!is_null($item)){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                $item->description = $post['description'];
                $auth->remove($item);
                $auth->add($item);
                $this->addFlash('success',"成功修改`{$item->description}`权限！");
            }
            return $this->render('edit',compact('item'));
        }else{
            $this->redirect(Url::toRoute('/acl'));
        }
    }

    /**
     * 删除权限
     */
    public function actionDelete()
    {
        $name = Yii::$app->request->get('name');
        $auth = Yii::$app->authManager;
        $item = $auth->getPermission($name);
        $auth->remove($item);
        $this->ajaxReturn([
            'state' => 1,
            'message' => '删除成功！',
        ]);
    }
}
