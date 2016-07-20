<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <img src="<?=  $model->getImageUrl() ?>">
    </div>

    <div>
        <strong><?= $model->getAttributeLabel('id') ?></strong> <?= $model->id ?>
    </div>
    <div>
        <strong><?= $model->getAttributeLabel('name') ?></strong> <?= $model->name ?>
    </div>
    <div>
        <strong><?= $model->getAttributeLabel('email') ?></strong> <?= $model->email ?>
    </div>
    <div>
        <strong><?= $model->getAttributeLabel('date_create') ?></strong> <?= $model->date_create ?>
    </div>
    <div>
        <strong><?= $model->getAttributeLabel('date_update') ?></strong> <?= $model->date_update ?>
    </div>


</div>
