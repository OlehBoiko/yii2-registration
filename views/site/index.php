<?php

/* @var $this yii\web\View */

$this->title = 'Yii2 Registration';
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
                        echo \Yii::$app->user->identity->name;
                    } ?>
                </h3>
            </div>
        </div>

    </div>
</div>
