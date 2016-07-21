<?php
/**
 * Created by PhpStorm.
 * User: MackRais
 * Date: 7/21/16
 * Site: http://mackrais.com
 * Email: mackraiscms@gmail.com
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\ChangePassword */
?>

<div class="site-change-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'new_password')->passwordInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'confirm_password')->passwordInput([]) ?>


    <?= $form->field($model, 'captcha')->widget(Captcha::className()) ?>

    <div class="form-group">
        <?= Html::submitButton('Change', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
