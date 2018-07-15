<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueSections */

$this->title = 'Update Questionnaire Section: ' . $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Questionnaire Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->description, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="que-sections-update">

  <!--   <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
