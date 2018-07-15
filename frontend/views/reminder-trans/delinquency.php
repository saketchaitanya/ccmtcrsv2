<?php

use yii\bootstrap\Html;
use backend\models\ReminderMaster;
use common\models\UserProfile;

$reminder = ReminderMaster::getActiveReminder();
            $remDate  = $reminder->getLastRemDate();

$this->title = 'Delinquent Centres till date: '.$remDate;
$this->params['breadcrumbs'][] = 'Delinquent Centres';
?>


    
     <div class='panel panel-info'>
         <div class='panel-heading'>
            <div class="auth-item-view">
                <h2><?= Html::encode($this->title) ?></h2>
                
            </div>
        </div>
        <div class='panel-body'>
            <div class="row">
                    <div class="col-xs-0 col-md-8">
                    </div>
                    <div class="col-xs-10 col-md-3">
                        <input class="form-control" placeholder="Search centre.." type="text" id="txtSearch" name="txtSearch" />&nbsp; 
                    </div>
                    <div class="col-xs-2 col-md-1">
                        <a href="#" id="imgSearch" alt="Clear" class-title="Cancel Search" class='btn btn-default'>
                            Clear
                        </a>
                    </div>
                </div>
            <div class='table-responsive'>
                <div class='panel panel-default'>
                    <div class='panel-body'>
                        <table class='table table-striped' id="tblSearch" >
                             <thead>
                                 <tr>
                                    <th> Centre Id </th>
                                    <th> CentreName</th>
                                    <th> Non Compliant Months </th>
                                    <th> Users </th>
                                    <th> Reminders sent (curr Year) </th>
                                    <th> Last Reminder Date </th>
                                 </tr>
                             </thead>   
                             <?php 

                                foreach ($model as $key=>$value)
                                {
                                    ?>
                                     <tr>
                                        <td>
                                           <?= $model[$key]['centreId']; ?>
                                        </td>
                                        <td>
                                           <?= $model[$key]['name']; ?>
                                        </td>
                                        <td>
                                            <?= $model[$key]['months']; 
                                           
                                             ?>  
                                        </td>
                                        <td>
                                            <?php 
                                                $emails= explode(', ',$model[$key]['emails']);

                                                
                                                foreach($emails as $email)
                                                {
                                                 $user= UserProfile::findByEmail($email);
                                                 $name = $user->fullName;
                                                 $mobile = $user->mobile;
                                                 echo $name.' ['.$email.', +91-'.$mobile.'] ';
                                                 echo '<br/>';  
                                                }
                                               
                                                
                                                ?>
                                        
                                        </td>
                                        <td>
                                            <?php
                                                if (!is_array($model[$key]['reminderArray'])):
                                                    echo $model[$key]['reminderArray'];
                                                else:
                                                    $dates = implode(', ',$model[$key]['reminderDate']);
                                                endif;
                                                echo $dates;
                                            ?>
                                        </td>
                                        <td>
                                            <?= $model[$key]['lastReminderDate']; ?>
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
    </div>
</div>  
                <?php
                    $this->registerCss("
                        td {
                            max-width:250px;
                            
                            white-space: normal !important;
                    }                   
                    ");
                ?>  
  

