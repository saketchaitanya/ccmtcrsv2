<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueStructure */

$this->title = 'Update: ' . $model->group;
$this->params['breadcrumbs'][] = ['label' => 'Questionnaire Structures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->group, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="que-structure-update">

   <!--  <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
