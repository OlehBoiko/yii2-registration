<?php

/* @var $this yii\web\View */
/* @var $user \app\models\User */

$this->title = 'Yii2 Registration';
$user = (new \Yii::$app->user->identityClass);

?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h3>
                    Hi,
                    <?php if(\Yii::$app->user->isGuest){
                        echo  'Guest';
                    }else{
                        echo $user->getCurrent()->name;
                    } ?>
                </h3>
            </div>
        </div>

    </div>
</div>
