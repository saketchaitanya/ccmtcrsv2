<?
use yii\helpers\Html;
use frontend\models\CentreReminderLinker;
use common\models\Centres;
use richardfan\widget\JSRegister;


$this->title = 'Centre Info Listing';
$this->params['breadcrumbs'][] = $this->title;

$centres=$model['centres'];
$users = $model['users'];
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
                    <input class="form-control" placeholder="Search Centre.." type="text" id="txtSearch" name="txtSearch" />&nbsp; 
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
                             <th style='width:20%'> Global Centre Code </th>
                             <th style='width:40%'> Name</th>
                             <th>User & Email</th>
                          </tr>
                        </thead> 
                        <tbody>
                        <?php 
                        for ($i=0; $i<sizeof($centres); $i++)
                        {
                            ?>
                            <tr>
                                <td> 
                                     <?= $centres[$i]['wpLocCode'] ?>
                                </td>
         	                    <td> 
                                    <a class='ls-modal' href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('eval/view-centre?code='.$centres[$i]["wpLocCode"])?> >
                             	      <?= $centres[$i]['name'] ?></a>
                             	</td>
                                <td>
                                    <?php
                                        if(isset($users[$centres[$i]['wpLocCode']]))
                                            echo $users[$centres[$i]['wpLocCode']];
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }?>
                        </tbody>  
                    </table>
                </div>
            </div> 
        </div>
    </div>
</div>  
<!---- modal --->
<div id="centremodal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg"> 
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-center">Centre Details</h2>
        </div>
      <div class="modal-body">
      <div align='center'><img src= '<?php echo Yii::$app->urlManager->createAbsoluteUrl("/themes/material-COC/assets/images/ajax-loader2.gif") ?>' height='30' width='30'/></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
</div>
</div>

<?php 

$loaderUrl= \Yii::$app->urlManager->createAbsoluteUrl("/themes/material-COC/assets/images/ajax-loader2.gif");
JSRegister::begin([
                'key' => 'modal-handler',
                'position' => \yii\web\View::POS_END
                ]); 
            ?>
<script>
$('.ls-modal').on('click', function(e){
  e.preventDefault();
  $('#centremodal').modal('show').find('.modal-body').load($(this).attr('href'));
});

$('#centremodal').on('hidden.bs.modal', function () {
    $(this).find('.modal-body').html('<div align="center"><img src= "<?php echo $loaderUrl ?>" height="30" width="30"/></div>').end();
});
</script>

<?php JSRegister::end(); ?>
