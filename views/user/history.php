<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = \Yii::t('app', 'History');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'event_text',
            'date_create',
        ],
    ]) ?>

</div>
