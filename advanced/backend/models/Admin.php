<?php
namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;
use yii\captcha\CaptchaValidator;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $rememberMe = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->db->tablePrefix.'admin';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'message' => '该登录名已经存在.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'message' => '该邮箱已经存在.'],

            ['password_hash', 'required'],
            ['password_hash', 'string', 'min' => 6],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * 获取管理员头像
     * @return string
     */
    public static function getAvatar($isPreview = true)
    {
        if(isset(Yii::$app->user->getIdentity()->avatar) && Yii::$app->user->getIdentity()->avatar){
            return Yii::$app->user->getIdentity()->avatar;
        }
        if($isPreview){
            return '/statics/images/users/avatar-0.jpg';
        }
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 清除管理员所分配的角色
     */
    public function removeAssignments()
    {
        (new AuthAssignment())->deleteAll(['user_id' => $this->id]);
    }

    /**
     * 管理员关联角色
     * @return $this
     */
    public function getRole()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])
            ->viaTable(AuthAssignment::tableName(), ['user_id' => 'id'])->asArray();
    }

    /**
     * 获取管理员数据
     * @param array $map
     * @return array
     */
    public function getAdmins($map = [])
    {
        $query = static::find()->with(['role'])->select(['id','username','email','status','created_at']);
        if(isset($map['username'])){
            $query->andFilterWhere(['like','username',$map['username']]);
        }
        $pageSize = isset($map['pageSize']) ?$map['pageSize']:10;
        $countQuery = clone $query;
        $pages = new Pagination([
            'pageSize' => $pageSize,
            'totalCount' => $countQuery->count(),
        ]);
        $query->orderBy("created_at desc");
        $items = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return compact('items','pages');
    }

    /**
     * 管理员登录
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $captcha = new CaptchaValidator();
        $captcha->captchaAction = 'auth/captcha';
        if(!$captcha->validate(Yii::$app->request->post('captcha'))){
            $this->addErrors(['对不起，验证码错误！']);
            return false;
        }
        if (!$admin = static::findByUsername($this->username)) {
            $this->addErrors(['对不起，该管理员不存在！']);
            return false;
        }
        if (0 == $admin->status) {
            $this->addErrors(['对不起，该管理员已被禁用！']);
            return false;
        }
        if($admin->validatePassword($this->password_hash)){
            return Yii::$app->user->login(static::findByUsername($this->username), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        $this->addErrors(['对不起，密码错误！']);
        return false;
    }
}
