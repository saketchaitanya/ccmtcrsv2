<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = 'View Users to Delete';
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
  
     <div class='panel panel-default'>
        <div class='panel-body'>
            <div class='table-responsive'>
            <table class='table table-striped' id="tblSearch" >
             <thead>
                 <tr>
                 <th> Username</th>
                 <th> Email </th>
                 <th></th>
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
                    <div align="center">
                    <a  class="btn btn-default"  href='/auth/delete-user?&username=<?php echo $model[$i]["username"] ?>' role='button'>Delete User</a>
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
  

