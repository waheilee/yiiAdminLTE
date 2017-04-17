<?php
namespace backend\controllers;

use backend\models\Admin;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class AuthController extends Controller
{
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'width' => 176,
                'height' => 35,
                'maxLength' => 4,
                'minLength' => 4,
                'offset' => 30,
                'transparent' => true,
                'testLimit' => 0,
            ],
        ];
    }

    /**
     * 登录操作
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Admin();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $model->username = $post['username'];
            $model->password_hash = $post['password_hash'];
            $model->rememberMe = isset($post['rememberMe']) ? (bool)$post['rememberMe'] : 0;
            if ($model->login()) {
                $this->ajaxReturn([
                    'state' => 1,
                    'message' => '恭喜你，登录成功！'
                ]);
            }
            $this->ajaxReturn([
                'state' => 0,
                'message' => implode('',array_flatten($model->errors))
            ]);
        }
        return $this->renderPartial('login', [
            'model' => $model,
        ]);
    }

    /**
     * ajax提示登录结果
     * @param $array
     */
    private function ajaxReturn($array)
    {
        echo json_encode($array);
        die;
    }

    /**
     * 退出登录操作
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(Url::toRoute('/auth/login'));
    }
}
