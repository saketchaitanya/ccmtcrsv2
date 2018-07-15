<?php

/* @var $this yii\web\View */
//use kartik\popover\PopoverX;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = 'CCMT Centre Reporting System';
?>
<div class="site-index">
    <div align='centre' style='color:red'>
        <?php  
            if (Yii::$app->session->hasFlash('resetsuccess')) 
                {
                    echo Yii::$app->session->getFlash('resetsuccess');
                }
         ?>
    </div>
    <div class="jumbotron">
        <h3 class="center-align">Central Chinmaya Mission Trust <br/> Centre Reporting System</h3>        
    </div>

    <div class="body-content">
        
        <div class="row">
            <div class="col s6">
	                <!--Carousel Wrapper-->
            <div class="carousel">
			    <a class="carousel-item" href="#one!"><img src="<?php echo $this->theme->baseUrl ?>/assets/images/chinmayananda_banner200.jpg"></a>
			    <a class="carousel-item" href="#two!"><img src="<?php echo $this->theme->baseUrl ?>/assets/images/tejomayananda_banner200.jpg"></a>
			    <a class="carousel-item" href="#three!"><img src="<?php echo $this->theme->baseUrl ?>/assets/images/swaroopananda_banner200.jpg""></a>			   
		  	</div>
            </div>
            <div class="col s6">
                <!-- <h4>Overview</h4> -->
                <p  align="justify"> This system allows centres to send their centre's monthly report online. Once you register on behalf of your chinmaya mission centre, you shall be authorized by the evaluator at CCMT. You will be able to fill in the questionnaires for the months prior to current month of the current financial year (<?php echo ((date('m') < 3) ? date('Y-1') : date('Y')).'-'. ((date('m') > 3) ? date('Y') +1 : date('Y')) ?>) Only one login can be used at a time by a centre to enter the details. For more information please visit our <a href="<?php echo '/site/faq'?>">FAQ </a>section.
               
              <p> Click here to <a href="<?php echo '/site/login' ?>">Login</a> now...</p>
                 <?php
                      /*  PopoverX::begin([
                        'placement' => PopoverX::ALIGN_TOP,
                        'toggleButton' => ['label'=>'Login', 'class'=>'btn btn-default'],
                        'header' => '<i class="glyphicon glyphicon-lock"></i> Enter credentials',
                        'footer' => Html::button('Submit', [
                                'class' => 'btn btn-sm btn-primary', 
                                'onclick' => '$("#kv-login-form").trigger("submit")'
                            ]) . Html::button('Reset', [
                                'class' => 'btn btn-sm btn-default', 
                                'onclick' => '$("#kv-login-form").trigger("reset")'
                            ])
                    ]);
                    // form with an id used for action buttons in footer
                    $form = ActiveForm::begin(['fieldConfig'=>['showLabels'=>false], 'options' => ['id'=>'kv-login-form']]);
                    echo $form->field($model, 'username')->textInput(['placeholder'=>'Enter user...']);
                    echo $form->field($model, 'password')->passwordInput(['placeholder'=>'Enter password...']);
                    ActiveForm::end();
                    PopoverX::end();*/
                    ?>
                </p>
            </div>
    	</div>
	</div>
    
</div>

