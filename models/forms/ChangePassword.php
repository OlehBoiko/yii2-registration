<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;


/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ChangePassword extends Model
{
    public $new_password;
    public $confirm_password;
    public $captcha;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['captcha', 'captcha'],
            [['new_password', 'confirm_password', 'captcha'], 'required'],
            ['new_password', 'compare', 'compareAttribute' => 'confirm_password', 'message' => "Passwords don't match"],
        ];
    }


    /** re
     * @return bool
     * @throws \yii\base\Exception
     */
    public function changePassword()
    {
        $model = $this->_user;

        if(empty($model))
            return false;

        /* @var $model User*/

        $model->password = Yii::$app->security->generatePasswordHash($this->new_password);
       // $model->generateToken();
        return $model->save();
    }

    /**
     * Finds user by [[token]]
     *
     * @param $token
     *
     * @return User|null
     */
    public function setUser($token)
    {
        if ($this->_user === false) {
            $this->_user = User::findByToken($token);
        }

        return $this->_user;
    }

    /** Get user
     * @return bool
     */
    public function getUser(){
        return $this->_user;
    }
}
