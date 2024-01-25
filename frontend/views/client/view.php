<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Client $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => [
            'class' => '\yii\i18n\Formatter',
            'datetimeFormat' => 'dd.mm.YYYY HH:mm:ss',
        ],
        'attributes' => [
            'id',
            'name',
            [
                'label' => 'Пол',
                'value' => $model->is_male ? 'Муж.' : 'Жен.',
            ],
            'date_of_birth:date',
            'creator_id',
            'created_at:datetime',
            'updater_id',
            'updated_at:datetime',
            'deleter_id',
            'deleted_at:datetime',
        ],
    ]) ?>

</div>
