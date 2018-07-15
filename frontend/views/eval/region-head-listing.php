<?
use yii\helpers\Html;
use common\models\UserProfile;
use common\models\RegionMaster;

$this->title = 'Regions and Regional Heads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='panel panel-default'>
    <div class='panel-heading' align='center'>
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class='panel-body'>
        <div class="auth-item-view">
            <div class="row">
                <div class="col-xs-0 col-md-7">
                </div>
                <div class="col-xs-10 col-md-3">
                    <input class="form-control" placeholder="Search Region/Region Head.." type="text" id="txtSearch" name="txtSearch" />&nbsp; 
                </div>
                <div class="col-xs-2 col-md-2">
                    <a id="imgSearch" alt="Clear" class-title="Cancel Search" class='btn btn-default'>Clear</a>
                </div>
            </div>
        </div>
        <div class='panel panel-default'>
            <div class='panel-body'>
                <div class='table-responsive'>
                    <table class='table table-striped' id="tblSearch" >
                        <thead>
                             <tr>
                             <th> Region</th>
                             <th> Head </th>
                             <th>Username</th>
                             <th>Email</th>
                             </tr>
                        </thead>   
                        <?php 
                        for ($i=0; $i<sizeof($model); $i++)
                        {
                            ?>
                            <tr>
         	                    <td> 
                             	      <?= $model[$i]['region'] ?>
                             	</td>
                     	        <td> 
                         		     <?= $model[$i]['regionalHead'] ?>
                     	        </td>
                                <td><?php 
                                        if(strlen($model[$i]['username'])>0): ?>
                                            <a href='/user-profile/update?&id=<?php echo $model[$i]["userprofileId"] ?>'>
                           	                    <?php echo $model[$i]["username"];?> 
                                            </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $model[$i]['email']; ?>
                        	   </td>
                            </tr>
                            <?php
                        }?>
                    </table>
                </div>
            </div> 
        </div>
    </div>
</div>    