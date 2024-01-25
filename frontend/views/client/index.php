<?php

use app\models\Client;
use app\models\ClientFilterForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var ClientFilterForm $filterForm */

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить клиента', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?php $form = ActiveForm::begin([
        'method' => 'POST',
        'options' => ['data' => ['pjax' => 1]],
        'action' => ['index'],
    ]); ?>

    <?= $form->field($filterForm, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($filterForm, 'isMale')->radioList([1 => 'Мужчина', 0 => 'Женщина']) ?>

    <?= $form
        ->field($filterForm, 'dateRange', [
            'options' => ['class' => 'drp-container mb-2']
        ])->widget(DateRangePicker::class, [
            'convertFormat' => true,
            'pluginOptions' => [
                'opens'=>'right',
                'locale' => [
                    'cancelLabel' => 'Clear',
                    'format' => 'Y-m-d',
                ]
            ]
        ]);
    ?>

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
            'datetimeFormat' => 'dd.mm.YYYY HH:mm:ss',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'is_male',
                'value' => fn(Client $client) => $client->getSexAlias(),
            ],
            'date_of_birth:date',
            'created_at:datetime',
            [
                'attribute' => 'Клубы',
                'value' => fn(Client $client) => implode(
                        ', ',
                        ArrayHelper::getColumn($client->clubs, 'name')
                )
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Client $model) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
