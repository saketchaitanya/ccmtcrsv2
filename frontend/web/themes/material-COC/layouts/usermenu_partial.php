<?php

 /**
 * Menu items for user included here
 */
  use yii\helpers\Html;
  use common\models\User;
  use common\models\UserProfile;
  use yii\widgets\Menu;
  use yii\bootstrap\Nav;
  use kartik\nav\NavX;
  use yii\bootstrap\NavBar;
  use yii\mongodb\Query;
  use yii\helpers\Url;
?>
 <div class="navbar-fixed" style="z-index: 1000">
    <nav>
      <div class="nav-wrapper">
          <?php  
             $id=Yii::$app->user->identity->_id;
             $curruser = User::findIdentity($id);
             $curruserprofile = UserProfile::findbyUserid($curruser->_id);
             
            if ($curruserprofile):
              $profileid =(string)($curruserprofile->_id);
              $profilelink = '/user-profile/update?id='.$profileid;
            else:
              $profilelink = '/user-profile/create';
            endif;

            $items =   
            [
                ['label' => 'My Profile', 'url' => [$profilelink ]],
                ['label' => 'My Centre Details', 'items'=> 
                    [
                      [
                        'label'=>'Verify Details','url' => ['/user-centre/index']
                      ],
                      '<li class="divider"></li>',
                      [
                        'label'=>'Inform Changes','url'=> ['/user-centre/sendmail']
                      ]
                    ]
                ],
                [
                  'label' => 'Questionnaire', 'url' => ['/user/questionnaire']
                ],
                [
                  'label' => 'Reminder', 'url' => ['/user/reminder']
                ]
            ];  

            if (!Yii::$app->user->isGuest):
                $items[] = 
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                .'<button class="btn btn-link" type="submit"><span style="color:white; text-decoration:none">Logout('.Yii::$app->user->identity->username.')</span></button>'
                . Html::endForm()
                . '</li>';
            endif;

            NavBar::begin(['brandLabel'=>'<img src="'.\Yii::getAlias("@web").'/themes/material-COC/assets/images/om.png" style="display: inline-block;"><span style="display: inline-block;">&nbsp;&nbsp; Questionnaire Entry</span>']);
                echo NavX::widget(
                  [
                    'options' => ['class' => 'navbar-nav navbar-right card'],
                    'items' => $items,
                    'activateParents' => true,
                   'encodeLabels' => false
                  ]);
            NavBar::end();
         ?>
      </div>
   </nav>
</div>
