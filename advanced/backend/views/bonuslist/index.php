<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BonuslistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权益绑定记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonuslist-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'cid',
            'type',
            'code',
//            'pic',
            [
                'attribute' => 'bindtime',
                'filter' => DateTimePicker::widget([
                    'model' => $searchModel,
                    'type' => DateTimePicker::TYPE_INPUT,
                    'attribute' => 'bindtime',
                    'options' => ['class' => ''],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'minView' => 'month',
                    ]
                ]),
            ],
            [
                'attribute' => 'deadline',
                'filter' => DateTimePicker::widget([
                    'model' => $searchModel,
                    'type' => DateTimePicker::TYPE_INPUT,
                    'attribute' => 'deadline',
                    'options' => ['class' => ''],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'minView' => 'month',
                    ]
                ]),
            ],
            [
                'attribute' => 'usetime',
                'filter' => DateTimePicker::widget([
                    'model' => $searchModel,
                    'type' => DateTimePicker::TYPE_INPUT,
                    'attribute' => 'usetime',
                    'options' => ['class' => ''],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'minView' => 'month',
                    ]
                ]),
            ],
            //'lasttime',
            //'status',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
