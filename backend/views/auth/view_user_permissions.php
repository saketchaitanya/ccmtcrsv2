<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = 'View User Permissions';
/*$this->params['breadcrumbs'][] = ['label' => 'Role List', 'url' => ['index']];
*/$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-xs-0 col-md-8">
            
        </div>
        <div class="col-xs-10 col-md-3">
            <input class="form-control" placeholder="Search user.." type="text" id="txtSearch" name="txtSearch" />&nbsp; 
        </div>
        <div class="col-xs-2 col-md-1">
            <a id="imgSearch" alt="Clear" class-title="Cancel Search" class='btn btn-default' />Clear</a>
        </div>
    </div>
     <div class='panel panel-default'>
        <div class='panel-body'>

            

            <div class='table-responsive'>
            <table class='table table-striped' id="tblSearch" >
             <thead>
                 <tr>
                 <th> Username</th>
                
                 <th>Permissions</th>
                 
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
                     <?= implode("  /  ",$model[$i]['permissions']); ?>
                 </td>
                 
                </tr>
            <?php
            }

             ?>
      </table>
             </div>
        </div> 
           

            <?php 
            /*foreach($model as $key=>$value)
            {
             ?>
             <div>
                <div class="well well-lg">
                        <div class='row'>
                            <div class='col-lg-12' margin-bottom:10px>
                                <?php echo 'Username: '.$key;?>

                            </div>
                        </div>
                        <div class='row'>   
                           <div class='col-lg-12'> 
                                <div class='row'>
                                    <?php 
                                    for ( $i=0; $i<sizeof($value['permissions']); $i++ )
                                    {

                                    ?>
                                        <div class='col-xs-12 col-md-3'>
                                            <div class='panel panel-default'>
                                                <div class='panel-body'>
                                        <!-- <div style=" float:left; padding:5px; border:black solid 1px;"> -->
                                                    <?php echo $value['permissions'][$i].'&nbsp';?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                          </div>
                        </div>
                </div>
            </div>
        
        <?php 
        }*/
        ?>
        </div>
    </div>
</div>    
  

