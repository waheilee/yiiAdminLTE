<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;

class RoleController extends DashboardController
{

    /**
     * 角色信息
     * @return string
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $items = $auth->getRoles();
        return $this->render('index',compact('items'));
    }

    /**
     * 创建角色
     * @return string
     */
    public function actionCreate()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $auth = Yii::$app->authManager;
            if(in_array($post['name'],array_keys($auth->getRoles()))){
                $this->addFlash('error','该角色重复添加了！');
            }else{
                $role = $auth->createRole($post['name']);
                $role->description = $post['description'];
                $auth->add($role);
                $this->addFlash('success',"成功添加`{$role->description}`角色！");
            }
        }
        return $this->render('create');
    }

    /**
     * 修改角色
     * @return string
     */
    public function actionUpdate()
    {
        $name = Yii::$app->request->get('name');
        $auth = Yii::$app->authManager;
        $item = $auth->getRole($name);
        if(!is_null($item)){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                $item->description = $post['description'];
                $auth->remove($item);
                $auth->add($item);
                $this->addFlash('success',"成功修改`{$item->description}`角色！");
            }
            return $this->render('edit',compact('item'));
        }else{
            $this->redirect(Url::toRoute('/role'));
        }
    }

    /**
     * 删除角色
     */
    public function actionDelete()
    {
        $name = Yii::$app->request->get('name');
        $auth = Yii::$app->authManager;
        $item = $auth->getRole($name);
        $auth->remove($item);
        $this->ajaxReturn([
            'state' => 1,
            'message' => '删除成功！',
        ]);
    }

    /**
     * 给角色分配权限
     * @return string
     */
    public function actionAllot()
    {
        $name = Yii::$app->request->get('name');
        $auth = Yii::$app->authManager;
        $item = $auth->getRole($name);
        $permissions = $auth->getPermissions();
        if(!is_null($item)){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                $auth->removeChildren($item);
                if(isset($post['permissions'])){
                    foreach($post['permissions'] as $permission){
                        $permission = $auth->createPermission($permission);
                        $auth->addChild($item,$permission);
                    }
                }
                $this->addFlash('success',"成功给`{$item->description}`角色分配权限！");
            }
            $current = array_keys($auth->getChildren($name));
            return $this->render('allot',compact('item','permissions','current'));
        }else{
            $this->redirect(Url::toRoute('/role'));
        }
    }
}
