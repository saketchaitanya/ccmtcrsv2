<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CentreRoleMaster */

$this->title = 'Create Centre Role Master';
$this->params['breadcrumbs'][] = ['label' => 'Centre Role Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centre-role-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
