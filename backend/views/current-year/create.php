<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CurrentYear */

$this->title = 'Create Current Year';
$this->params['breadcrumbs'][] = ['label' => 'Current Years', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="current-year-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
