<?php

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список шаблонов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="font-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить шаблон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'title',
            [
                'class' => ActionColumn::className(),
                'template' => '{plus} {view} {update} {delete}'
            ]
        ],
    ]); ?>
</div>
