<?php
/**
 * Created by PhpStorm.
 * User: MackRais
 * Date: 7/20/16
 * Site: http://mackrais.com
 * Email: mackraiscms@gmail.com
 *
 * @var $model \app\models\User
 * @var $url string
 */
?>

<h1>
    Hello <?= $model->name ?>

</h1>

<h3>To activate your account click the link below</h3>
<a href="<?= $url ?>/user/confirm?token=<?= $model->token ?>"><?= $url ?>/user/confirm?token=<?= $model->token ?></a>