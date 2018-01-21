<?php

/* @var $this yii\web\View */

/* @var $model app\models\Certificate*/

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = 'Сертификат №' . $model->id;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="certificate-view">
    <a href="<?=Url::to(['/certificate/download', 'id' => $model->id]);?>" class="btn btn-success" target="_blank">
        Скачать
    </a>
    <hr>
    <img src="<?=$model->getPath();?>" alt="">
</div>

