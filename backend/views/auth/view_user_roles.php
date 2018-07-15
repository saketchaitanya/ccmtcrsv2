<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model UserAccessHelper->getAllUserRoles() :array */

$this->title = 'View User Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="auth-item-view">
    <div class="row">
        <div class="col-xs-0 col-md-8">
            
        </div>
        <div class="col-xs-10 col-md-3">
            <input class="form-control" placeholder="Search user.." type="text" id="txtSearch" name="txtSearch" />&nbsp; 
        </div>
        <div class="col-xs-2 col-md-1">
            <!-- <img id="imgSearch" src="/images/cancel.gif" alt="Clear" title="Cancel Search" style="width:150px;width:14px;height:14px;" /> -->
            <a id="imgSearch" alt="Clear" class-title="Cancel Search" class='btn btn-default' />Clear</a>
        </div>
    </div>
</div>
 


    
   <?php
   /*
   <div class='panel panel-default'>
        <div class='panel-body'>
           <?php 
            foreach($model as $key=>$value)
            {
             ?>
             <div style="clear:both" class='well'>
                 <div class="row">
                    <div class="col-xs-4 col-md-4">
                    <?php echo 'Username: '.$key; ?>
                    </div>
                    <div class="col-xs-4 col-md-6">
                        <?php 
                            for ($i=0; $i<sizeof($value['roles']); $i++)
                            {
                                ?>
                                <?php echo 'Role/s: '.$value['roles'][$i];?>
                                <?php
                            }
                         ?>
                     </div>
                     <div class='col-xs-4 col-md-2'>
                        <a  class="btn btn-default"  href='/auth/edit-user-role?&username=<?php echo $key ?>' role='button'>Change Role</a>
                    </div>
                  </div> 
                  </div> 
             
             <?php 
            }
            ?>
        </div> 
    </div>
     */?>
    
     <div class='panel panel-default'>
        <div class='panel-body'>
            <div class='table-responsive'>
            <table class='table table-striped' id="tblSearch" >
             <thead>
                 <tr>
                 <th> Username</th>
                 <th> Email </th>
                 <th>Roles</th>
                 <th><div align='center'>Modify</div></th>
                 </tr>
             </thead>   
             <?php 
                for ($i=0; $i<sizeof($model); $i++)
                {
                 ?>
                 <tr>
                    <td>
                       <?= $model[$i]['username']; ?>
                    </td>
                    <td>
                        <?= $model[$i]['email']; ?>
                    <td>
                     <?= implode(",",$model[$i]['roles']); ?>
                 </td>
                 <td>
                    <div align="center">
                    <a  class="btn btn-default"  href='/auth/edit-user-role?&username=<?php echo $model[$i]["username"] ?>' role='button'>Change Role</a>
                    </div>
                </td>
                </tr>
            <?php
            }

             ?>
      </table>
             </div>
        </div> 
    </div>
</div>    
  

