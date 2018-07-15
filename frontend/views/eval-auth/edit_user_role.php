<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use  yii\mongodb\rbac\MongoDbManager;
use common\components\UserAccessHelper;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Button;
use yii\bootstrap\Dropdown;

$this->title = 'Edit User Role';
$this->params['breadcrumbs'][] =['label' => 'View User Roles', 'url' => ['auth/view-user-roles']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.dropdown-content {
  
   border-radius: 5px;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 1px 1px 1px 2px rgba(0,0,0,0.1);
    padding: 12px 12px; 
  
}
.center-block {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
</style>
<div class="auth-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

 <div class='panel panel-default'>
    <div class= 'panel-heading'>
        <?php echo 'User '.$roles['username']."'s Role/s are:" ?>        
    </div>
    <div class='panel-body'>
        <?php 
            $currentRole=null;
            if (!empty($roles['roles']))
            {
                echo $roles['roles'][0];
                $currentRole=$roles['roles'][0];
            } 
        ?>
    </div>
 </div>  
<?php  
    $manager = new MongoDbManager;
    $all_roles=array_keys($manager->getRoles());

?> <?= Html::beginForm(['auth/update-user-role'], 'post') ?>
<div class='panel panel-default'>
    <?= Html::hiddenInput('username',$roles['username']) ?>
    <?= Html::hiddenInput('currentRole',$currentRole) ?>
    <?php
    for  ($i=0;$i<sizeof($all_roles); $i++)
    {
        $roles_a[$all_roles[$i]]= $all_roles[$i] ;

    }
    ?>
    <div class='panel-body'>
        <div align ='center'>
            
            <?= Html::dropDownList('selectedrole', null , $roles_a, ['class'=>'dropdown-content']) ?>
            
            <?= Button::widget([
            'label' => 'Change',
            'options' => ['class' => 'btn btn-info btn-lg'],
            ]);?>
            <a class="btn btn-warning btn-lg" role="button" href='/auth/view-user-roles'>Cancel</a>
        </div>
    </div>
    <?= Html::endForm() ?>
</div> 
</div>   
  

