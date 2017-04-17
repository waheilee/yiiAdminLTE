<?php

namespace backend\controllers;

use Yii;

class PersonalController extends DashboardController
{
    /**
     * 修改个人密码
     * @return string
     */
    public function actionUpdatePassword()
    {
        $user = Yii::$app->user->getIdentity();

        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();

            if (!$user->validatePassword($post['old_password'])) {
                $this->addFlash('error', '原密码错误！');
                goto render;
            }

            $user->password_hash = $post['password_hash'];

            if (!$user->validate()) {
                $this->addFlash('error', implode('', array_flatten($user->getErrors())));
                goto render;
            }

            if ($post['password_hash'] <> $post['confirm_password']) {
                $this->addFlash('error', '确认密码不一致！');
                goto render;
            }

            $user->setPassword($post['password_hash']);

            if ($user->save()) {
                $this->addFlash('success', '恭喜你，密码修改成功！');
                goto render;
            }

            $this->addFlash('error', '密码修改失败！');
        }

        render:
        return $this->render('_passport');
    }

    /**
     * 修改个人头像
     * @return string
     */
    public function actionUpdateAvatar()
    {
        $user = Yii::$app->user->getIdentity();

        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();

            $user->avatar = $post['avatar'];

            if ($user->save()) {
                $this->addFlash('success', '恭喜你，头像修改成功！');
                goto render;
            }

            $this->addFlash('error', '头像修改失败！');
        }

        render:
        return $this->render('_avatar');
    }

    /**
     * 上传个人头像
     */
    public function actionUploadAvatar()
    {
        $file = base64_decode(Yii::$app->request->post('file'));
        // 类型判断
        $fileType = explode('/', getimagesizefromstring($file)['mime']);
        $allowType = ['gif', 'jpg', 'jpeg', 'png', 'bmp'];
        $allowSize = 3145728;
        if (empty($fileType) || $fileType['0'] != 'image' || !in_array($fileType[1], $allowType)) {
            $this->ajaxReturn(array(
                'state'   => 0,
                'message' => '只允许上传以下格式的图片: '.implode(', ', $allowType)
            ));
        }
        // 大小判断
        if (strlen($file) > $allowSize) {
            $this->ajaxReturn(array(
                'state'   => 0,
                'message' => '图片不能大于'.$allowSize
            ));
        }
        //设置上传目录
        $basePath = 'uploads';
        //根据日期划分子目录
        $dataPath = explode('-',date('Y-m-d'));
        //根据传过来的type决定上传目录
        $subPath  = "/avatar/".$dataPath['0']."/".$dataPath['1']."/".$dataPath['2']."/";
        //最终取得要上传图片的全路径
        $path = $basePath.$subPath;
        //判断目录是否存在，不存在则创建
        if(!is_dir($path)){
            mkdir($path , 0777 , true);
        }
        //取得文件后缀
        $extension = $fileType[1];
        //取得文件名
        $fileName = md5($file).'.'.$extension;
        //取得文件路径
        $image = $path.$fileName;
        //上传完返回图片路径供保存到数据库
        $returnPath = '/uploads'.$subPath.$fileName;
        if(file_put_contents($image,$file)){
            $this->ajaxReturn(array(
                'state' => 1,
                'path'  => $returnPath
            ));
        }
        $this->ajaxReturn(array(
            'state' => 0,
            'path'  => '上传失败'
        ));
    }
}
