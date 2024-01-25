<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Client $model */
/** @var app\models\Club $clubs */

$this->title = 'Обновить клиента: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="client-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'clubs' => $clubs,
    ]) ?>

</div>
