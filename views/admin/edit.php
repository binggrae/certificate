<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\Template */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = 'Настройка шаблона ' . $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['id' => 'template-edit-form']); ?>
<div id="ajaxLoader">
    ajax
</div>


<div class="template-edit" id="Editor" data-id="<?= $model->id; ?>">
    <div class="row">
        <div class="col-md-8">
            <div class="preview">
                <div class="background">
                    <img src="<?= $model->getWebPath(); ?>" alt="">
                </div>
                <div class="blocks" id="jsBlockContainer"></div>
            </div>
        </div>

        <div class="col-md-4">
            <h4>Установленные блоки </h4>
            <table class="table" id="jsPanelBlocks">
                <thead>
                <tr>
                    <td>#</td>
                    <td>Размер</td>
                    <td>Цвет</td>
                    <td>Шрифт</td>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <h4>Доступные блоки
                <a href="#" id="jsCreateFieldBlock" class="btn btn-primary btn-xs">
                    <i class="fa fa-plus"></i>
                </a>
            </h4>
            <table class="table" id="jsPanelTypes">
                <tbody>

                </tbody>
            </table>

            <hr>
            <?= Html::submitButton('Сохранить шаблон', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/html" id="tplPanelType">
    <tr data-type="{type}" class="jsAllowedBlockInfo">
        <td>{title}</td>
        <td>
            <a href="#" class="jsAddBlock btn btn-primary btn-xs">
                <i class="fa fa-plus"></i>
            </a>
        </td>
    </tr>
</script>

<script type="text/html" id="tplPanelBlock">
    <tr id="jsInstalledBlock{id}" data-id="{id}" class="jsListBlockInfo">
        <td><input type="text" name="title" class="jsInputTitle" value="{title}"></td>
        <td><input type="text" name="font-size" class="jsInputFontSize" value="{fontSize}"></td>
        <td><input type="text" name="color" class="jsInputColor" value="{color}"></td>
        <td>
            <select name="font" class="jsSelectFontId">
                <option value="0">---</option>
            </select>
        </td>
    </tr>
</script>

<script type="text/html" id="tplFont">
    <option value="{id}">{name}</option>
</script>


<script id="tplBlock" type="text/html">
    <div class="block" style="left: {posX}px; top: {posY}px; color: {color}; font-size: {fontSize}px; width: {width}px; font-family: 'font{fontId}'">
        <div class="jsTitle">
            {title}
        </div>

        <div class="remove">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
        <?= $this->render('_form'); ?>
        <div class="form">
        </div>
    </div>
</script>

<script id="tplFontFace" type="text/html">
    <style>
        @font-face {
            font-family: "font{id}";
            src: url("/fonts/{ttf}");
        }
    </style>
</script>