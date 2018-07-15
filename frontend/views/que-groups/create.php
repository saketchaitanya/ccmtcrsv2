<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\QueGroups */

$this->title = 'Create Questionnaire Groups';
$this->params['breadcrumbs'][] = ['label' => 'Questionaire Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-groups-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
