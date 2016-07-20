<?php
namespace app\models\forms;


use app\models\User;
use Swift_SwiftException;
use yii\base\Model;
use Yii;
use yii\helpers\Url;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $name;
    public $email;
    public $password_confirm;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            [
                'email',
                'unique',
                'targetClass' => '\app\models\User',
                'message' => 'This username has already been taken.'
            ],
            ['name', 'string', 'min' => 2, 'max' => 255],

            [['name', 'password', 'email'], 'required'],


            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_confirm', 'required'],

            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
        ];
    }


    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateToken();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Send Email
     *
     * @param null $from
     * @param      $to
     * @param      $subject
     * @param      $model
     *
     * @return bool|string
     */
    public static function sendEmail($from = null, $to, $subject, $model)
    {

        $html = \Yii::$app->view->render('@app/mail/confirm_email', [
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
}
