<?php

namespace app\controllers;


use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\models\UserLogs;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /** Sign Up
     *
     * @return string|\yii\web\Response
     */
    public function actionSignUp()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        $data = Yii::$app->request->post();

        if ($model->load($data) && $model->validate()) {
            if ($user = $model->signup()) {

                $result = SignupForm::sendEmail(Yii::$app->params['feedbackEmail'], $model->email,
                    " Yii2 registration: welcome & let’s get started", $user);

                if (!empty($result)) {
                    UserLogs::setLog('User successful registered', $user);
                    \Yii::$app->getSession()->setFlash('success',
                        'You have successfully registered. Please check your e-mail');
                } else {
                    UserLogs::setLog('User successful registered.  But an error sending ', $user);
                    \Yii::$app->getSession()->setFlash('error',
                        'You have successfully registered. But an error sending. Please contact the site administrator');
                }

            } else {
                \Yii::$app->getSession()->setFlash('error',
                    'There was an error with registration. Please contact the site administrator');
            }
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            UserLogs::setLog('User login ');
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        UserLogs::setLog('User Logout');
        Yii::$app->user->logout();
        return $this->goHome();
    }


}
