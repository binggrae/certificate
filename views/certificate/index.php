<?php

use app\models\Template;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model Template[] */

$this->title = 'Список сертификатов';
$this->params['breadcrumbs'][] = $this->title;
?>


<table class="table">

    <?php foreach ($model as $item) : ?>
        <tr>
            <td><?= $item->title; ?></td>
            <td>
                <a href="<?= Url::to(['/certificate/create', 'id' => $item->id]); ?>" class="btn btn-success">
                    Создать
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
