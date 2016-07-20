<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\MrCropImageSection\ImageCropSection;


/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(
        [
            'enableClientValidation' => true,
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form mr-user-edit']
        ]
    ); ?>


    <?=
    $form->field($model, "avatar")->widget(ImageCropSection::className(), [
        'options' => [
            'id' => 'mr_file_input1',
            'class' => 'hidden',
        ],
        'attribute_x' => 'section1_x',
        'attribute_y' => 'section1_y',
        'attribute_width' => 'section1_w',
        'attribute_height' => 'section1_h',
        'attribute_scale' => 'section1_scale',
        'plugin_options' => [
            'width' => 200,
            'height' => 200,
            'id_input_file' => 'mr_file_input1',
            'section' => 'section_1'
        ],
        'template_image' => isset($model->id) && $model->getImageUrl($model->id) ? Html::img($model->getImageUrl($model->id),
            ['class' => 'circle-img']) : null
    ])->label(false);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'about_me')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
