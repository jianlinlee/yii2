<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BonuslistSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bonuslist-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'pic') ?>

    <?php // echo $form->field($model, 'bindtime') ?>

    <?php // echo $form->field($model, 'deadline') ?>

    <?php // echo $form->field($model, 'usetime') ?>

    <?php // echo $form->field($model, 'lasttime') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
