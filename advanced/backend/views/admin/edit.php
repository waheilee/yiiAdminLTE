<?php

use yii\helpers\Url;
$this->title = '编辑管理员';
$request = Yii::$app->request->post();
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>管理员编辑</b></h4>

            <form class="form-horizontal" action="" method="post">
                <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>">
                <div class="form-group">
                    <label for="username" class="col-sm-3 control-label">登录名：</label>
                    <div class="col-sm-8">
                        <input id="username" type="text" class="form-control" value="<?=isset($request['username']) ?$request['username']:$item['username']?>" name="username" placeholder="" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">邮箱：</label>
                    <div class="col-sm-8">
                        <input id="email" type="text" class="form-control" value="<?=isset($request['email']) ?$request['email']:$item['email']?>" name="email" placeholder="" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            保存信息
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

