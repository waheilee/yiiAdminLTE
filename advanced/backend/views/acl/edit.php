<?php

use yii\helpers\Url;
$this->title = '编辑权限';
$request = Yii::$app->request->post();
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>权限编辑</b></h4>

            <form class="form-horizontal" action="" method="post">
                <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>">
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">权限名称：</label>
                    <div class="col-sm-8">
                        <input id="name" type="text" class="form-control" value="<?=isset($request['name']) ?$request['name']:$item->name?>" name="name" placeholder="" disabled required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">权限描述：</label>
                    <div class="col-sm-8">
                        <input id="description" type="text" class="form-control" value="<?=isset($request['description']) ?$request['description']:$item->description?>" name="description" placeholder="" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            保存信息
                        </button>
                        <button type="button" onclick="location.href='<?=Url::toRoute('/acl')?>'" class="btn btn-default waves-effect waves-light m-l-5">
                            返回列表
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

