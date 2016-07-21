<?php

namespace app\models\forms;

use Swift_SwiftException;
use Yii;
use yii\base\Model;
use app\models\User;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ForgotPassword extends Model
{
    public $email;
    public $captcha;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'captcha'], 'required'],
            ['captcha', 'captcha'],
            ['email', 'email'],
            [
                ['email'],
                'notExist',
                'params' => [
                    'targetClass' => User::className()
                ]
            ],
        ];
    }

    /** Validation Not Exist
     *
     * @param $attribute
     * @param $params
     *
     * @return bool
     */
    public function notExist($attribute, $params)
    {

        /* @var $targetClass ActiveRecord */
        if (isset($this->$attribute) && !empty($this->$attribute)) {
            if (isset($params['targetClass']) && !empty($params['targetClass'])) {
                $targetClass = $params['targetClass'];
            } else {
                $targetClass = self::className();
            }
            $model = $targetClass::find()->where([$attribute => $this->$attribute])->count();

            if (empty($model)) {
                $this->addError($attribute, "User {$this->$attribute} not exist! ");
                return false;
            }
        }
        return true;
    }


    /**
     * Send email
     *
     * @param null $from
     * @param      $to
     * @param      $subject
     *
     * @return bool|string
     */
    public function sendEmail($from = null, $to, $subject)
    {
        $model = User::findByEmail($this->email);


        if (empty($model)) {
            return false;
        }

        $html = \Yii::$app->view->render('@app/mail/forgot_password', [
            'model' => $model,
            'feedbackEmail' => Yii::$app->params['feedbackEmail'],
            'url' => Url::base(true)
        ]);


        if (!$from) {
            $from = Yii::$app->params['feedbackEmail'];
        }
        try {
            return \Yii::$app->mailer->compose()
                ->setFrom([$from => 'Yii2 registration'])
                ->setTo($to)
                ->setSubject($subject)
                ->setTextBody(strip_tags($html))
                ->setHtmlBody($html)
                ->send();
        } catch (Swift_SwiftException $exception) {
            return 'Can sent mail due to the following exception' . print_r($exception);
        }

    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
