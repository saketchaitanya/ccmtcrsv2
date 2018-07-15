<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\QueSections */

$this->title = 'Create Questionnaire Section';
$this->params['breadcrumbs'][] = ['label' => 'Questionnaire Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-sections-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
