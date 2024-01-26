<?php

use app\models\Club;
use app\models\ClubFilterForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var ClubFilterForm $filterForm */

$this->title = 'Клубы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="club-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить клуб', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?php $form = ActiveForm::begin([
            'method' => 'GET',
            'options' => ['data' => ['pjax' => 1]],
            'action' => ['index'],
    ]); ?>

    <?= $form->field($filterForm, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($filterForm, 'isShowDeleted')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Фильтровать', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <br />

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => [
            'class' => '\yii\i18n\Formatter',
            'datetimeFormat' => 'dd.MM.YYYY HH:mm:ss',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'address',
                'contentOptions' => ['style' => 'max-width: 20%; white-space: normal;'],
            ],
            'created_at:datetime',
            [
                'class' => ActionColumn::class,
                'visibleButtons' => [
                    'delete' => function (Club $model) {
                        return is_null($model->deleted_at);
                     }
                ],
                'urlCreator' => function ($action, Club $model) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
