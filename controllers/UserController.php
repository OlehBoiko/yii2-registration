<?php

namespace app\controllers;


use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\models\forms\UserEdit;
use app\models\UserLogs;


class UserController extends Controller
{

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (\Yii::$app->user->isGuest) {
                throw new ForbiddenHttpException('The requested page does not exist.');
            }
            return parent::beforeAction($action);
        }
        return true;

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

        if (!empty($user)) {
            $user->generateToken();
            $user->setActive();
            if ($user->save()) {
                \Yii::$app->getSession()->setFlash('success',
                    'You have successfully confirmed. Now you can login.');
                UserLogs::setLog('User successful confirmed.', $user);
            } else {
                \Yii::$app->getSession()->setFlash('error',
                    'There was an error with confirm. Please contact the site administrator');
                UserLogs::setLog('User error confirmed.', $user);
            }
        } else {
            \Yii::$app->getSession()->setFlash('error',
                'Incorrect confirmation link. Please contact the site administrator');
        }
        return $this->goHome();
    }


    /**
     * Displays a single User model.
     * @return mixed
     */
    public function actionView()
    {
        return $this->render('view', [
            'model' => $this->findModel(\Yii::$app->user->getId()),
        ]);
    }

    /**
     * All histories
     * @return string
     */
    public function actionHistory()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => UserLogs::find()->where(['id_user' => \Yii::$app->user->getId()]),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_create' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('history', [
            'dataProvider' => $dataProvider,
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

        /* @var $model UserEdit */

        $model->old_password = $model->password;
        $model->password = '';

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadAvatar(Yii::$app->request->post(), $model);

            if ($model->validate()) {
                if ($model->save()) {
                    UserLogs::setLog('Update profile');
                    return $this->redirect(['view']);
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
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
        if (($model = UserEdit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
