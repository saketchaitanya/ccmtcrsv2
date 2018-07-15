<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Centres */

$this->title = 'Create Centres';
$this->params['breadcrumbs'][] = ['label' => 'Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centres-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
