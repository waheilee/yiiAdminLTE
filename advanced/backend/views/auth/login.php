<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '管理平台登录';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="/statics/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/statics/css/core.css" rel="stylesheet" type="text/css">
    <link href="/statics/css/icons.css" rel="stylesheet" type="text/css">
    <link href="/statics/css/components.css" rel="stylesheet" type="text/css">
    <link href="/statics/css/pages.css" rel="stylesheet" type="text/css">
    <link href="/statics/css/menu.css" rel="stylesheet" type="text/css">
    <link href="/statics/css/responsive.css" rel="stylesheet" type="text/css">
    <script src="/statics/js/modernizr.min.js"></script>
</head>
<body class="signwrapper">
<?php $this->beginBody() ?>
<div class="wrapper-page">
    <div class="text-center">
        <a href="" class=" logo logo-lg">
            <i class="md md-desktop-windows "></i>
            <span>YiiAdminLTE</span>
        </a>
    </div>

    <form id="login-form" class="form-horizontal m-t-20" action="" method="post">
        <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>">
        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" name="username" value="" type="text" required="" autofocus placeholder="登录名">
                <i class="md md-account-circle form-control-feedback l-h-34"></i>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" name="password_hash" value="" type="password" required="" placeholder="密码">
                <i class="md md-vpn-key form-control-feedback l-h-34"></i>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" name="captcha" value="" type="text" required="" placeholder="验证码">
                <i class="md md-blur-linear form-control-feedback l-h-34"></i>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox checkbox-primary">
                    <input id="checkbox-signup" name="rememberMe" type="checkbox">
                    <label for="checkbox-signup">
                        记住我
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group text-right m-t-20">
            <div class="col-xs-7 text-left">
                <img id="captcha" src="<?=Url::toRoute('/auth/captcha')?>"/>
            </div>
            <div class="col-xs-5 text-right pull-right">
                <button class="btn btn-success btn-custom w-md waves-effect waves-light" type="submit">
                    登录
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    var resizefunc = [];
</script>
<!-- Main  -->
<script src="/statics/js/jquery.min.js"></script>
<script src="/statics/js/jquery.core.js"></script>
<script src="/statics/plugins/notifyjs/dist/notify.min.js"></script>
<script src="/statics/plugins/notifications/notify-metro.js"></script>
<script type="text/javascript">
$(function(){
    $('#captcha').css('cursor','pointer');
    $('#captcha').click(refreshCaptcha);

    function refreshCaptcha()
    {
        var src = "<?=Url::toRoute('/auth/captcha')?>";
        $('#captcha').attr('src',src+'?v='+Math.random()*100000000000000000);
    }

    $('#login-form').submit(function(){
        $.ajax({
            type:'post',
            url:'<?=Url::toRoute('/auth/login')?>',
            data:$(this).serialize(),
            dataType:'json',
            success:function(data){
                if(data.state){
                    $.Notification.autoHideNotify('success', 'top right', '提示信息',data.message);
                    location.href = '/';
                }else{
                    $.Notification.autoHideNotify('error', 'top right', '提示信息',data.message);
                    refreshCaptcha();
                }
            },
            error:function(err){
                console.log(err);
                throw err;
            }
        });
        return false;
    });
});
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
