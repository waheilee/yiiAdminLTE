<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label'=>'控制台','icon'=>'file-code-o','url'=>['dashboard/index'],'items'=>[
                        ['label'=>'创建文章','url'=>['post/create'],'visible'=>false],
                        ['label'=>'更新文章','url'=>['post/update'],'visible'=>false],
                    ]
                    ],
                    //['label' => '管理员', 'icon' => 'file-code-o', 'url' => ['/admin']],

                    ['label'=>'管理员','icon'=>'dashboard','url'=>['/admin'],'items'=>[
                        ['label'=>'管理员列表','url'=>['/admin'],'visible'=>true],
                        ['label'=>'创建管理员','url'=>['admin/create'],'visible'=>true],
                    ]
                    ],
                    //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                            ['label'=>'权限管理','url'=>['#'],'items'=>
                                [
                                    ['label'=>'创建角色','url'=>['/role/create']],
                                    ['label'=>'角色列表','url'=>['/role']],
                                    ['label'=>'创建权限','url'=>['/acl/create']],
                                    ['label'=>'权限列表','url'=>['/acl']],
                                ]
                            ],


                ],
            ]
        ) ?>

    </section>

</aside>
