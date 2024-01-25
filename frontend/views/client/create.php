<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Client $model */
/** @var app\models\Club $clubs */

$this->title = 'Создать клиента';
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'clubs' => $clubs,
    ]) ?>

</div>
