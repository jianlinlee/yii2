<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Bonuslist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bonuslist-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bindtime')->textInput() ?>

    <?= $form->field($model, 'deadline')->textInput() ?>

    <?= $form->field($model, 'usetime')->textInput() ?>

    <?= $form->field($model, 'lasttime')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
