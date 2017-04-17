<?php
namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class DashboardController extends Controller
{
    /**
     * 禁止未登录的用户访问
     * @param \yii\base\Action $action
     * @return bool|\yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
//    public function beforeAction($action)
//    {
//        parent::beforeAction($action);
//
//        if(Yii::$app->user->isGuest){
//            return $this->redirect(Url::toRoute('/auth/login'));
//        }
//        if(!YII_DEBUG){
//            $controller = Yii::$app->controller->id;
//            $action = Yii::$app->controller->action->id;
//            if(!Yii::$app->user->can($controller.'/'.$action)){
//                if(Yii::$app->request->isAjax){
//                    $this->ajaxReturn([
//                        'state' => 0,
//                        'message' => '对不起，您现在还没获此操作的权限！',
//                    ]);
//                }else{
//                    throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限！');
//                }
//            }
//        }
//        return true;
//    }

    /**
     * 控制台
     * @return string
     */

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 加入提示信息
     * @param $type
     * @param $message
     */
    protected function addFlash($type,$message)
    {
        Yii::$app->session->setFlash($type, $message);
    }

    /**
     * ajax返回数据
     * @param $array
     */
    protected function ajaxReturn($array)
    {
        echo json_encode($array);
        die;
    }
}
