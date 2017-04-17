<?php

namespace api\modules\v2\controllers;

use yii\rest\ActiveController;

/**
 * User controller for the `v1` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

}
