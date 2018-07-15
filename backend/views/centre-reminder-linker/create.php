<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\CentreReminderLinker */

$this->title = 'Create Centre Reminder Linker';
$this->params['breadcrumbs'][] = ['label' => 'Centre Reminder Linkers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centre-reminder-linker-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
