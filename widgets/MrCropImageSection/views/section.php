<?php
/**
 * Created by PhpStorm.
 * User: mackrais
 * Date: 03.11.15
 * Time: 14:12
 */

use yii\helpers\Html;
use yii\helpers\Json;
use app\widgets\MrCropImageSection\MrSectionWidgetAsset;
MrSectionWidgetAsset::register($this);

?>
<div  class="mr-section-base <?= $class_block ?>" style="width: <?= $plugin_options['width']+4 ?>px; ">
    <div class="mr-section" id="<?= $plugin_options['section'] ?>" data-role="upload_image" style="width: <?= $plugin_options['width'] + 4 ?>px; height: <?= $plugin_options['height']+3  ?>px;">
        <?php if($template_image): ?>
           <?= $template_image ?>
          <?php else: ?>
            <i class="glyphicon glyphicon-picture" ></i>
            <h2>Аватар </h2>
        <?php endif; ?>

    </div>
    <div  class="mr-control-panel">
        <div class="btn-group">
        <button class='btn btn-primary mr-zoom-out'     type='button' title='Збільшити'> <span class="glyphicon glyphicon-zoom-out"></span> </button>
        <button class='btn btn-primary mr-fit'          type='button' title='Центрувати'> <span class="glyphicon glyphicon-resize-small"></span> </button>
        <button class='btn btn-primary mr-zoom-in'      type='button' title='Зменшити'> <span class="glyphicon glyphicon-zoom-in"></span> </button>
       </div>
        <button class='btn btn-success mr-upload-btn-section pull-right' type='button' title='Загрузити зображення'> <span class="glyphicon glyphicon-upload"></span> </button>
    </div>
    <?= Html::activeFileInput($model, $attribute, $options); ?>
    <div class="mr-data-inputs">
        <?= Html::textInput($attribute_x,'',$options_x)?>
        <?= Html::textInput($attribute_y,'',$options_y)?>
        <?= Html::textInput($attribute_height,'',$options_height)?>
        <?= Html::textInput($attribute_width,'',$options_width)?>
        <?= Html::textInput($attribute_origin_width,'',$options_origin_width)?>
        <?= Html::textInput($attribute_origin_height,'',$options_origin_height)?>
        <?= Html::textInput($attribute_scale,'',$options_scale)?>
        <?= Html::textInput($attribute_angle,'',$options_angle)?>
    </div>
</div>

<?php
$plugin_options['section'] = '#'.$plugin_options['section'] ;
$plugin_options['id_input_file'] = '#'.$plugin_options['id_input_file'] ;
$options = Json::encode($plugin_options,true);

$this->registerJs("
mr_section_init($options);
");
?>