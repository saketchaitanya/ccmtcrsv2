<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\QueStructure */

$this->title = 'Add Items';
$this->params['breadcrumbs'][] = ['label' => 'Questionnaire Structure', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-structure-create">

 <!--    <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
