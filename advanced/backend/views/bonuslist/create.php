<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Bonuslist */

$this->title = 'Create Bonuslist';
$this->params['breadcrumbs'][] = ['label' => 'Bonuslists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonuslist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
