<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Font */

$this->title = 'Добавление шрифты';
$this->params['breadcrumbs'][] = ['label' => 'Шрифты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="font-create">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="font-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'file')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
