<?php

namespace backend\controllers;

use backend\models\Admin;
use Yii;
use yii\helpers\Url;

class AdminController extends DashboardController
{
    /**
     * 管理员列表
     * @return string
     */
    public function actionIndex()
    {
        $model = new Admin();
        $data = $model->getAdmins(Yii::$app->request->get());
        return $this->render('index',compact('data'));
    }

    /**
     * 管理员创建
     * @return string
     */
    public function actionCreate()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $model = new Admin();
            $model->username = $post['username'];
            $model->email = $post['email'];
            $model->password_hash = $post['password_hash'];
            if ($model->validate()) {
                $model->setPassword($post['password_hash']);
                $model->generateAuthKey();
                if($model->save()){
                    $this->redirect(Url::toRoute('/admin'));
                }
            }
            $this->addFlash('error',implode('',array_flatten($model->getErrors())));
        }
        return $this->render('create');
    }

    /**
     * 管理员编辑
     * @return string
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');
        $model = new Admin();
        if($item = $model->find()->where(['id' => $id])->one()){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                $item->username = $post['username'];
                $item->email = $post['email'];
                if ($item->validate()) {
                    if($item->save()){
                        $this->redirect(Url::toRoute('/admin'));
                    }
                }
                $this->addFlash('error',implode('',array_flatten($item->getErrors())));
            }
            return $this->render('edit',compact('item'));
        }else{
            $this->redirect(Url::toRoute('/admin'));
        }
    }

    /**
     * 管理员删除
     * @throws \Exception
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');
        $model = new Admin();
        if($item = $model->find()->where(['id' => $id])->one()){
            $transaction = Yii::$app->db->beginTransaction();
            $item->removeAssignments();
            if($item->delete()){
                $transaction->commit();
                $this->ajaxReturn([
                    'state' => 1,
                    'message' => '删除成功！',
                ]);
            }
            $transaction->rollBack();
            $this->ajaxReturn([
                'state' => 0,
                'message' => '删除失败！'.implode('',array_flatten($item->getErrors())),
            ]);
        }else{
            $this->ajaxReturn([
                'state' => 0,
                'message' => '数据不存在！',
            ]);
        }
    }

    /**
     * 切换管理员状态
     */
    public function actionChange()
    {
        $id = Yii::$app->request->get('id');
        $model = new Admin();
        if($item = $model->find()->where(['id' => $id])->one()){
            $item->status = $item->status == 1 ? 0 : 1;
            if($item->save()){
                $this->ajaxReturn([
                    'state' => 1,
                    'message' => '切换成功！',
                ]);
            }
            $this->ajaxReturn([
                'state' => 0,
                'message' => '切换失败！'.implode('',array_flatten($item->getErrors())),
            ]);
        }else{
            $this->ajaxReturn([
                'state' => 0,
                'message' => '数据不存在！',
            ]);
        }
    }

    /**
     * 修改管理员密码
     * @return string
     */
    public function actionUpdatePassword()
    {
        $id = Yii::$app->request->get('id');
        $model = new Admin();
        if($item = $model->find()->where(['id' => $id])->one()){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                $item->password_hash = $post['password_hash'];
                if ($item->validate()) {
                    $item->setPassword($post['password_hash']);
                    if($item->save()){
                        $this->redirect(Url::toRoute('/admin'));
                    }
                }
                $this->addFlash('error',implode('',array_flatten($item->getErrors())));
            }
            return $this->render('_passport',compact('item'));
        }else{
            $this->redirect(Url::toRoute('/admin'));
        }
    }

    /**
     * 给管理员分配角色
     * @return string
     */
    public function actionAllot()
    {
        $userId = Yii::$app->request->get('id');
        $auth = Yii::$app->authManager;
        $item = (new Admin())->find()->where(['id' => $userId])->one();
        $roles = $auth->getRoles();
        if(!is_null($item)){
            if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                $item->removeAssignments();
                if(isset($post['roles'])){
                    foreach($post['roles'] as $role){
                        $role = $auth->createRole($role);
                        $auth->assign($role,$userId);
                    }
                }
                $this->addFlash('success',"成功给`{$item->username}`管理员分配角色！");
            }
            $current = array_keys($auth->getAssignments($userId));
            return $this->render('allot',compact('item','roles','current'));
        }else{
            $this->redirect(Url::toRoute('/admin'));
        }
    }
}
