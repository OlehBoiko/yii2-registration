<?php
namespace app\models;


use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $avatar
 * @property string $password
 * @property string $auth_key
 * @property string $token
 * @property string $email
 * @property string $about_me
 * @property integer $status
 * @property string $date_create
 * @property string $date_update
 *
 * @property UserLogs[] $userLogs
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const DEFAULT_AVATAR = '/default_images/default_avatar.png';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'password', 'auth_key', 'token', 'email'], 'required'],
            [['status'], 'integer'],
            [['date_create', 'date_update'], 'safe'],
            [['name', 'avatar', 'password', 'auth_key', 'token', 'email', 'about_me'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'avatar' => Yii::t('app', 'Avatar'),
            'password' => Yii::t('app', 'Password'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'token' => Yii::t('app', 'Token'),
            'email' => Yii::t('app', 'Email'),
            'about_me' => Yii::t('app', 'About Me'),
            'status' => Yii::t('app', 'Status'),
            'date_create' => Yii::t('app', 'Date Create'),
            'date_update' => Yii::t('app', 'Date Update'),
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
     * @inheritdoc
     */
    public static function findByToken($token)
    {
        return static::findOne(['token' => $token]);
    }


    /**
     * @inheritdoc
     */
    public static function findByAuthKey($authKey)
    {
        return static::findOne(['auth_key' => $authKey]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
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
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
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
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }


    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates "remember me" token key
     */
    public function generateToken()
    {
        $this->token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Set active user
     */
    public function setActive()
    {
        $this->status = self::STATUS_ACTIVE;
    }


    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        if (isset($this->avatar) && !empty($this->avatar)) {
            return Url::to("{$this->avatar}", true);
        }
        return Url::to(self::DEFAULT_AVATAR, true);
    }

    /**
     * @return User|null
     */
    public function getCurrent(){
        return self::findOne(\Yii::$app->user->getId());
    }

}
