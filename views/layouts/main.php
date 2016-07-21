<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $user \app\models\User */


use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

$user = (new \Yii::$app->user->identityClass);

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php if (Yii::$app->getSession()->getFlash('error')): ?>
    <div class="bb-alert alert alert-danger mr-top-margin-alert" style="display: block">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php if (is_array(Yii::$app->getSession()->getFlash('error'))): ?>
            <ul>
                <?php foreach (Yii::$app->getSession()->getFlash('error') as $k => $items): ?>
                    <?php foreach ($items as $msg): ?>
                        <li><span><?= $msg ?></span></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <span><?= Yii::$app->getSession()->getFlash('error') ?></span>
        <?php endif ?>
    </div>
<?php endif ?>

<?php if (Yii::$app->getSession()->getFlash('success')): ?>
    <div class="bb-alert alert alert-success mr-top-margin-alert" style="display: block">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <span><?= Yii::$app->getSession()->getFlash('success') ?></span>
    </div>
<?php endif ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->user->isGuest ? 'Yii2 registration' : "<img src='" . $user->getCurrent()->getImageUrl() . "' class='img-responsive' height='60px' >",
        'brandUrl' => Yii::$app->user->isGuest ? Yii::$app->homeUrl : Url::to('user/view', true),
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);


    if (Yii::$app->user->isGuest) {
        $items_menu = [
            ['label' => 'Sign Up', 'url' => ['/site/sign-up']],
            ['label' => 'Login', 'url' => ['/site/login']]
        ];
    } else {
        $items_menu = [
            ['label' => 'Edit', 'url' => ['/user/update']],
            ['label' => 'History', 'url' => ['/user/history']],
            ['label' => 'Logout (' . $user->getCurrent()->name. ')', 'url' => ['/site/logout']]
        ];
    }


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items_menu
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; MackRais <?= date('Y') ?></p>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
