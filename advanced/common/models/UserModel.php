<?php

namespace common\models;

use common\models\base\BaseModel;
use Yii;
use \yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email_validate_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property string $avatar
 * @property integer $vip_lv
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserModel extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['role', 'status', 'vip_lv', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email_validate_token', 'email', 'avatar'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email_validate_token' => 'Email Validate Token',
            'email' => '邮箱',

            'role' => '角色',
            'status' => '状态',
            'avatar' => 'Avatar',
            'vip_lv' => 'Vip 等级',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
