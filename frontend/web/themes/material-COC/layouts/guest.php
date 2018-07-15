<?php

use yii\helpers\Html;
use yii\widgets\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/**
 * @var $this \yii\base\View
 * @var $content string
 */
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
  <html>
    <head>
      <!--Import materialize.css-->

      <link type="text/css" rel="stylesheet" href="<?php echo $this->theme->baseUrl ?>/assets/css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="<?php echo $this->theme->baseUrl ?>/assets/css/style.css"  media="screen,projection"/>
       <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

      <!--Let browser know website is optimized for mobile-->
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    </head>

    <body>
      <?php $this->beginBody(); ?>
      <div class="navbar-fixed" style="z-index: 1000">
        <nav>
          <div class="nav-wrapper">
            
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <?php             
          
              $navItems =   
                [
                    ['label' => 'Home', 'url' => ['site/index']],
                    ['label' => 'FAQ', 'url' => ['site/faq']],
                    ['label' => 'Contact', 'url' => ['site/contact']]
                ];                  
                if (Yii::$app->user->isGuest) 
                  {
                      $navItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                      $navItems[] = ['label' => 'Login', 'url' => ['/site/login']];
                  } 
                  else 
                  {
                     
                        $navItems[] = 
                        '<li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            'Logout (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'btn-flat white-text']
                        )
                        . Html::endForm()
                        . '</li>';
                      
                  }

                echo Nav::widget([
                  'options' => ['class' => 'right hide-on-med-and-down'],
                  'items' => $navItems,
                ]);
             ?>
             <ul id="mobile-demo" class="side-nav">
                  <li>
                    <div class="background">
                      <img src="<?php echo $this->theme->baseUrl ?>/assets/images/CCMTbw.jpg">
                    </div>
                  </li>
                  <li><div class="divider"></div></li> 
                  <li><a href="<?php echo Url::toRoute('site/index'); ?>">Home</a></li>
                  <li><div class="divider"></div></li> 
                  <li><a href="<?php echo Url::toRoute('site/about'); ?>">About</a></li>
                  <li><div class="divider"></div></li>                  
                  <li><a href="<?php echo Url::toRoute('site/contact'); ?>">Contact</a></li>
                  <li><div class="divider"></div></li> 
                  <li><a href="<?php echo Url::toRoute('site/login'); ?>">Login</a></li>

                </ul>                
            <?php   
                
              ?> 
          </div>
        </nav>
      </div>
       <div class="parallax">
         <div id="group2" class="parallax__group">
            <div id="group2div" class="parallax__layer parallax__layer--back">
            </div>
          </div>     
         <div id="group1" class="parallax__group" >
            <div id="group1div" class="parallax__layer parallax__layer--base">
              <div id="group1subdiv1">
                <div class="container main-content gray-text">
                  <div class="row"></div>
                  <div class="row">
                      <div class="col s1">
                         &nbsp;
                      </div>   
                      <div class="col s10">
                          <?php echo $content; ?>
                      </div>                         
                      <div class="col s1">
                         &nbsp;
                      </div>
                    </div>
                  <div class="row">              
                </div>
              </div>
            </div>
            </div> 
          </div>
          <div id="group4" class="parallax__group">
            <div id="group4div" class="parallax__layer parallax__layer--back">
              <div id="group4subdiv1">
                  <h1>CHINMAYA MISSION WORLDWIDE</h1>
              </div>
            </div>           
          </div>
        </div>
      <footer class="footer">
        <div class="container">
          <div class="row center-align grey-text">
            <p class="pull-left white-text">&copy; Central Chinmaya Mission Trust <?= date('Y')."-".(date('Y')+1) ?></p>
            </div>
          </div>
        </div>
      </footer>
      <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="<?php echo $this->theme->baseUrl ?>/assets/js/jquery2.2.4.min.js"></script>
      <script type="text/javascript" src="<?php echo $this->theme->baseUrl ?>/assets/js/materialize.min.js"></script>
      <script type="text/javascript" src="<?php echo $this->theme->baseUrl ?>/assets/js/app.js"></script>
      <script>
        (function($)
        {
          $(function(){
            $('.button-collapse').sideNav({
             menuWidth: 200, // Default is 300
            edge: 'left', // Choose the horizontal origin
            closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
            draggable: true,
            });
          }); // end of document ready
        })(jQuery);
         $(document).ready(function(){
            $('.carousel').carousel();
        });
      </script>
      <?php $this->endBody() ?>
    </body>
  </html>
<?php $this->endPage(); ?>