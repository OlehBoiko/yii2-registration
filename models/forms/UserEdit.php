<?php

namespace app\models\forms;

use app\helpers\Image;
use app\models\UserLogs;
use Yii;
use app\models\User;
use yii\web\UploadedFile;

/**
 * UserEdit is the model behind the login form.
 *
 * * @property integer $id
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
 * @property User|null $user This property is read-only.
 *
 */
class UserEdit extends User
{
    var $confirm_password;
    var $new_password;
    var $old_password;


    /**
     * @return array the validation rules.
     */
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'auth_key', 'email'], 'required'],
            [
                [
                    'name',
                    'avatar',
                    'password',
                    'auth_key',
                    'email',
                    'about_me',
                    'new_password',
                    'confirm_password',
                    'old_password'
                ],
                'string',
                'max' => 255
            ],
            [
                ['email'],
                'unique',
                'when' => function ($model) {
                    return $model->email !== $model->oldAttributes['email'];
                }
            ],
            ['password', 'validatePassword'],
            ['new_password', 'compare', 'compareAttribute' => 'confirm_password', 'message' => "Passwords don't match"],
        ];
    }

    /**
     * @param $data
     * @param $model_avatar
     * @return bool
     */
    public function uploadAvatar($data, $model_avatar)
    {
        $fileInstanseAvatar = UploadedFile::getInstance($this, 'avatar');
        $images = [];
        $model = null;
        $error_image = [];

        if (isset($fileInstanseAvatar)) {
            if ($this->validate(['avatar'])) {
                $images['section1'] = Image::upload($fileInstanseAvatar, 'avatar');
                Image::cropImageSection(Yii::getAlias('@webroot') . $images['section1'],
                    Yii::getAlias('@webroot') . $images['section1'], [
                        'width' => $data['section1_w'],
                        'height' => $data['section1_h'],
                        'y' => $data['section1_y'],
                        'x' => $data['section1_x'],
                        'scale' => $data['section1_scale'],
                    ]);
            } else {
                $error_image['section1'] = $this->getErrors();
            }
        }

        if (!empty($images)) {
            foreach ($images as $key => $item) {
                $model = self::find()->where(['id' => $model_avatar->id, 'avatar' => $this->avatar])->one();
                if (isset($model->avatar) && !empty($model->avatar)) {
                    @unlink(Yii::getAlias('@webroot') . $model->avatar);
                }
                UserLogs::setLog('Changed Avatar');
                $this->avatar = $item;
                return true;
            }
        } else {
            $model = self::find()->where(['id' => $model_avatar->id])->one();
            if (isset($model->avatar) && !empty($model->avatar)) {
                $this->avatar = $model->avatar;
            }
        }
        return false;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function validatePassword($attribute)
    {

        if (!\Yii::$app->security->validatePassword($this->password, $this->old_password)) {
            $this->addError($attribute, 'Incorrect old password.');
        }
        return true;
    }

    /**
     * Deleting old image
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
        if (parent::beforeSave($insert)) {
            if (!$insert && $this->avatar != $this->oldAttributes['avatar'] && $this->oldAttributes['avatar']) {
                @unlink(Yii::getAlias('@webroot') . $this->oldAttributes['avatar']);
            }
            if (!empty($this->new_password)) {
                UserLogs::setLog('Changed password');
                $this->password = Yii::$app->security->generatePasswordHash($this->new_password);
            } else {
                $this->password = $this->old_password;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deleting image
     */
    public function afterDelete()
    {
        /* @var $model UserEdit */
        parent::afterDelete();
        $model = self::find()->where(['id' => $this->id])->one();
        if (isset($model)) {
            @unlink(Yii::getAlias('@webroot') . $model->avatar);
        }
        return true;
    }

}
