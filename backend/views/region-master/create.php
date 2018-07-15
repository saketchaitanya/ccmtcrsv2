<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\regionMaster */

$this->title = 'Create Region Master';
$this->params['breadcrumbs'][] = ['label' => 'Region Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
