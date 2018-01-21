<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $template app\models\Template */
/* @var $certificate app\models\Certificate */
/* @var $blocks app\models\Block[] */
/* @var $certValue app\models\CertValue */

$this->title = 'Создание сертификата';
$this->params['breadcrumbs'][] = ['label' => 'Сертификаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="font-create">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="font-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($certificate, 'email')->textInput(['maxlength' => true]) ?>
        <?= $form->field($certificate, 'template_id')->label(false)->hiddenInput(['value' => $template->id]) ?>

        <?php foreach ($blocks as $block) : ?>
            <?= $form->field($certValue, '['.$block->id.']value')->label($block->type->title)->textInput(['maxlength' => true]) ?>

        <?php endforeach; ?>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
