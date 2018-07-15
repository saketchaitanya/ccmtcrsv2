<?php

 /**
   * Used for login of user and evaluator
   * Please donot upgrade to Bootstrap 4.0+ without testing as somethings like navbar may not work.
   * Please donot add materialize classes as there is a chance of overlap and malfuction.
   * @author: Br. Saket Chaitanya;
   * @Ver: 1.0;
   * @Update Date: 24-12-2017;
  */

  use yii\helpers\Html;
  use common\models\User;
  use yii\widgets\Menu;
  use yii\bootstrap\Nav;
  use yii\bootstrap\NavBar;
  use yii\widgets\Breadcrumbs;
  use yii\helpers\Url;
  use frontend\assets\CcmtCrsAsset;
  use common\widgets\Alert;

  CcmtCrsAsset::register($this);
  /**
   * @var $this \yii\base\View
   * @var $content string
   */

  ?>
<?php $this->beginPage(); ?>
  <!DOCTYPE html>
    <html lang="en">
      <?php $this->head()?>
        <!--Let browser know website is optimized for mobile-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <!-- Material Design for Bootstrap fonts and icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons"> 
        <?php
        $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/themes/material-COC/assets/images/favicon.ico']);
        ?>
        <?php
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        ?>

        <?php $this->beginBody(); ?>

        <!-- if the user is not logged in redirect to login page -->
        <?php 
          if (Yii::$app->user->isGuest)
            {
              echo "You are not logged in. Redirecting..";

              header("Location:".Yii::$app->user->loginUrl[0]); /* Redirect browser */
              exit();
            }

          /* Set the menu based on the user type */
          $usertype = Yii::$app->user->identity->usertype; 
         if(\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a')
          {
            ?>
              <!--- show navbar for evaluator -->
                  <?php             
                     include ('evalmenu_partial.php');
                     $homelink = '/questionnaire/index';
                   ?>
              <?php
            }   
            elseif(\Yii::$app->user->can('createQuestionnaire')||$usertype=='p')
            {
              ?>
              <!--- show navbar for user -->
              
              <?php 
              		include ('usermenu_partial.php'); 
                  $homelink = '/user/questionnaire';
              ?>
              <?php
            } 

            else
            {
               include ('unappusermenu_partial.php'); 
               $homelink = Yii::$app->homeUrl;
            }   
            ?>

        <!---body text -->
         <div class="bodywrapper">

           <button onclick="topFunction()" class="shape59" id="myBtn" title="Go to top">Top</button>


            <div class="contentwrapper"> 
             <?php 
                $session=\Yii::$app->session;
                if ($session->hasFlash('querySent')):       
                  echo \yii\bootstrap\Alert::widget([
                  'options' => ['class' => 'alert-success text-center'],
                  'body' => $session->getFlash('querySent'),
                  ]);
                endif; 
              ?>

              <div class="row">
                    <div class="col-sm-1">
                        
                    </div>  
              </div>   
              <div class="row" >  
                   <div class="col-sm-12" style='top:5px'> 

                      <div class="container main-content gray-text">
                        <div class="row">
                          &nbsp;
                        </div>
                        <div class="row">
                          <div class="col-sm-1">
                               
                              </div>   
                          <div class="col-sm-10">
                            <div class='card'>

                             <?= Breadcrumbs::widget([

                              'homeLink' => false,
                              'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                          ]) ?>
                          <?= Alert::widget() ?>
                            </div>
                          <br/>
                          </div>
                          <div class="col-sm-1">
                                 &nbsp;
                              </div>   
                        </div>
                          <div class="row">
                              <div class="col-sm-1">
                                 &nbsp;
                              </div>   
                              <div class="col-sm-10 ">
                                <div class='panel panel-default card'>
                                <div class='card-container'>
                                  <div class='panel-body'>
                                  
                                    <?php echo $content; ?>
                                  </div>
                                </div>
                              </div>
                              </div>                         
                              <div class="col-sm-1">
                                 &nbsp;
                              </div>
                            </div>              
                      </div>
                    </div>              
              </div>
              <div class="row">
                <div class="col-sm-1">
                   &nbsp;
                </div>  
              </div>
            </div>
            <?php require('feedback.php');?>    
          </div> 

        <!--footer --> 
        
        <span class=" fTab active ">Close Footer</span> 
        <footer class="footer">     
          <div class="container">
            <div class="row">
              <div class="col-sm-9">
                <p class="text-center white-text">&copy; Central Chinmaya Mission Trust <?= date('Y')."-".(date('Y')+1) ?></p>
              </div>            
          </div>
        </footer>
        

        <?php $this->endBody() ?> 
        <!-- <script>
          jQuery(function($)
            {
              $( "#hidefooterbtn" ).click(function(event)
               {
                  event.preventDefault();
                $( '.footer' ).css("height:0px");
              }
            );
          });

        </script> -->
        <script>
          // When the user scrolls down 20px from the top of the document, show the button
          
        </script>
    </html>
  <?php $this->endPage(); ?>
