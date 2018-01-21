<?php

use app\models\Template;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Template */

$this->title = 'Список сертификатов по шаблону';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="font-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать сертификат', ['/certificate/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'email',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {delete}',
                'controller' => 'certificate',
            ]
        ],
    ]);
    ?>
</div>
