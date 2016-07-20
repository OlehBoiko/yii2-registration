<?php

namespace app\controllers;

use app\models\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;

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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
     * @return string|\yii\web\Response
     */
    public function actionSignUp()
    {
        $model = new SignupForm();
        $data = Yii::$app->request->post();

        if ($model->load($data) && $model->validate()) {
            if ($user = $model->signup()) {

                if (!empty($user)) {
                    $result = SignupForm::sendEmail(Yii::$app->params['feedbackEmail'], $model->email,
                        " Yii2 registration: welcome & let’s get started", $user);

                    if (!empty($result)) {
                        \Yii::$app->getSession()->setFlash('success',
                            'You have successfully registered. Please check your e-mail');
                    } else {
                        \Yii::$app->getSession()->setFlash('error',
                            'You have successfully registered. But an error sending. Please contact the site administrator');
                    }
                }
                return $this->goHome();
            }else{
                \Yii::$app->getSession()->setFlash('error',
                    'There was an error with registration. Please contact the site administrator');
                return $this->goHome();
            }
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
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
