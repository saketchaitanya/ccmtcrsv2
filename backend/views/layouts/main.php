<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php  

$url = Yii::$app->request->url;
/*echo $url;
exit();*/
//$urlparam = array_reverse(explode('/',$url));
//$pagename = $urlparam[0];

    if ($url=='/site/index')
    {
        ?>
        <div class='intro-wrap'>
        <?php 
    }
    else
    {
        ?>
        <div class="wrap">
        <?php
    }
    ?>
    <?php
        NavBar::begin([
        'brandLabel' => 'CCMT',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [ ['label' => 'Home', 'url' => ['/site/index']],
        ];

        if (Yii::$app->user->isGuest) 
        {
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login'],];
        } 
        else 
        {
           
             $menuItems[] =  ['label'=> 'Access Control', 'items'=>
                                    [
                                      [ 'label'=>'User Roles','url' => ['/auth/view-user-roles']],
                                      '<li class="divider"></li>',
                                      ['label'=> 'User Permissions', 'url'=> ['/auth/view-user-permissions']],
                                      '<li class="divider"></li>',
                                      ['label'=> 'Roles and Permissions', 'url'=> ['/auth/index']],
                                      '<li class="divider"></li>',
                                      ['label'=> 'Delete User', 'url'=> ['/auth/view-users-to-delete']]
                                    ] 
                            ];
             $menuItems[] = ['label'=> 'Centres', 'url'=> ['/centres-india/index']];
             $menuItems[] =  ['label'=> 'Questionnaire', 'items'=>
                                    [
                                      [ 'label'=>'Ques Assignment','url' => ['/que-groups/index']],
                                      [ 'label'=>'Ques Delete','url' => ['/questionnaire/index']],
                                    ] 
                            ];                
             $menuItems[] =  ['label'=> 'Masters', 'items'=>
                                    [
                                      [ 'label'=>'Que Assignment','url' => ['/que-groups/index']],
                                      [ 'label'=>'Region','url' => ['/region-master/index']],
                                      [ 'label'=>'Centre Roles','url' => ['/centre-role-master/index']],
                                      [ 'label'=>'Current Year','url' => ['/current-year/index']],
                                      [ 'label'=>'Rates Allocation','url' => ['/allocation-master/index']],
                                      [ 'label'=>'Reminder Setup','url' => ['/reminder-master/index']],
                                      /*  ('<li class="divider"></li>',
                                      ['label'=> 'Compose Reminder', 'url'=> ['/eval/remindercompose']]*/
                                    ] 
                            ];
             $menuItems[] =  ['label'=> 'Utilities', 'items'=>
                                    [
                                      [ 'label'=>'Acharyas','url' => ['/wp-acharya/index']],
                                      [ 'label'=>'Locations','url' => ['/wp-location/index']],
                                     /* [ 'label'=>'Reminder Users','url' => ['/centre-reminder-linker/index']],*/
                                    ] 
                            ];
            $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
        }
        echo NavX::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div style="text-align:center">&copy; CCMT <?= date('Y')-1 .'-' . date('Y') ?></div>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
