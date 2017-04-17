<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use backend\assets\AppAsset;
AppAsset::addCss($this , "/plugins/bootstrap-sweetalert/sweet-alert.css");
AppAsset::addScript($this , "/plugins/bootstrap-sweetalert/sweet-alert.min.js");
AppAsset::addScript($this , "/js/app.js");
$this->title = '权限信息';
?>
<div class="panel">
    <div class="panel-body">
        <?php Pjax::begin(['id' => 'item-data-list']);?>
        <div class="row">
            <div class="col-sm-6">
                <div class="m-b-30">
                    <a href="<?=Url::toRoute('/acl/create')?>" class="btn btn-primary waves-effect waves-light">
                        创建权限
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="">
            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th class="col-sm-3 sorting">权限</th>
                                <th class="col-sm-3 sorting">描述</th>
                                <th class="col-sm-3 sorting">创建时间</th>
                                <th class="col-sm-3 sorting">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($items as $item):?>
                            <tr class="gradeA odd">
                                <td><?=$item->name?></td>
                                <td><?=$item->description?></td>
                                <td><?=date('Y-d-m H:i:s',$item->createdAt)?></td>
                                <td class="actions">
                                    <a onclick="location.href=this.href" href="<?=Url::toRoute('/acl/update?name='.$item->name)?>" class="on-default edit-row">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a onclick="app.deleteData(this);return false;" href="<?=Url::toRoute('/acl/delete?name='.$item->name)?>" class="on-default remove-row">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_info">
                            已定义 <?=count($items)?> 种权限
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php Pjax::end();?>
    </div>
</div>
