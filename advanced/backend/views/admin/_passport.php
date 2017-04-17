<?php

use yii\helpers\Url;
$this->title = '修改管理员密码';
$request = Yii::$app->request->post();
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>修改管理员密码</b></h4>

            <form class="form-horizontal" action="" method="post">
                <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>">
                <div class="form-group">
                    <label class="col-sm-3 control-label">登录名：</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" value="<?=$item['username']?>" placeholder="" disabled required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_hash" class="col-sm-3 control-label">新密码：</label>
                    <div class="col-sm-8">
                        <input id="password_hash" type="password" class="form-control" name="password_hash" placeholder="" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            保存更改
                        </button>
                        <button type="button" onclick="location.href='<?=Url::toRoute('/admin')?>'" class="btn btn-default waves-effect waves-light m-l-5">
                            返回列表
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

