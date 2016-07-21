<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

/**
 * @param  $varible
 */
function echoDbg($varible){
    echo '<pre>';
    print_r($varible);
    echo '</pre>';
    exit;
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');



(new yii\web\Application($config))->run();
