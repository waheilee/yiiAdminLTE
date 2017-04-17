<?php

use yii\helpers\Url;

$this->title = '分配角色权限';
$request = Yii::$app->request->post();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="m-t-0"><b>当前角色：<?=$item->description?></b></h4>
            <form class="form-horizontal group-border-dashed" action="" method="post">
                <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>">
            <div class="form-group" id="permissions">
                <?php foreach($permissions as $permission):?>
                <div class="col-sm-3">
                    <div class="checkbox checkbox-pink">
                        <input type="checkbox" <?=in_array($permission->name,$current) ? 'checked' : ''?> name="permissions[]" value="<?=$permission->name?>">
                        <label> <?=$permission->description?> </label>
                    </div>
                </div>
                <?php endforeach;?>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <button id="check-all" type="button" class="btn btn-success waves-effect w-md waves-light m-b-5">全部</button>
                    <button id="check-null" type="button" class="btn btn-success waves-effect w-md waves-light m-b-5">取消</button>
                    <button id="check-back" type="button" class="btn btn-success waves-effect w-md waves-light m-b-5">反选</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        保存信息
                    </button>
                    <button type="button" onclick="location.href='<?=Url::toRoute('/role')?>'" class="btn btn-default waves-effect waves-light m-l-5">
                        返回列表
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<?php
$js = <<<JS
jQuery(document).ready(function($) {
    $('#check-all').click(function(){
        $.each($('#permissions').find("input[type='checkbox']"),function(){
            $(this).prop('checked',true);
        });
    });
    $('#check-null').click(function(){
        $.each($('#permissions').find("input[type='checkbox']"),function(){
            $(this).prop('checked',false);
        });
    });
    $('#check-back').click(function(){
        $.each($('#permissions').find("input[type='checkbox']"),function(){
            if($(this).is(":checked")){
                $(this).prop('checked',false);
            }else{
                $(this).prop('checked',true);
            }
        });
    });
});
JS;
$this->registerJs($js);
?>



