<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Confirm email.
     *
     * @param $token
     *
     * @return mixed
     */
    public function actionConfirm($token)
    {
        $user = User::findByToken($token);

        /* @var $user User */

        if(!empty($user)){
            $user->generateToken();
            $user->setActive();
            if($user->save()){
                \Yii::$app->getSession()->setFlash('success',
                    'You have successfully confirmed. Now you can login.');
            }else{
                \Yii::$app->getSession()->setFlash('error',
                    'There was an error with confirm. Please contact the site administrator');
            }
        }else{
            \Yii::$app->getSession()->setFlash('error',
                'Incorrect confirmation link. Please contact the site administrator');
        }
        return $this->goHome();
    }



    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionHistory($id)
    {

        return $this->render('history', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = $this->findModel(\Yii::$app->user->getId());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
