<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use richardfan\widget\JSRegister;

//use yii\widgets\Pjax;
use frontend\models\AllocationDetails;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Allocations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div style='color:red'><?php
        $session=\Yii::$app->session;
        if ($session->hasFlash('deleteStatus')):
            $sessionFlash= $session->getFlash('deleteStatus');
        elseif($session->hasFlash('approveStatus')):
             $sessionFlash= $session->getFlash('approveStatus');
        endif;
        
?></div>
<?php 
/*Modal:: begin([
    'header'=>'<h4>Allocations</h4>',
    'id'=>'allocmodal',
    'size'=>'modal-lg',
    
]);
echo '<div id="modal-content"></div>';
Modal::end();*/
?>


<div class="allocation-details-index">
    <p align='right'>
        <?= Html::a('Create Allocation', ['create'], ['class' => 'btn btn-success','id'=>'createBtn']) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider'=> $dataProvider,
            'resizableColumns'=>true,
            'showPageSummary'=>true,
            'afterFooter'=> '',
            'pjax'=>true,
            'pjaxSettings'=>
            [
                'neverTimeout'=>true,
                'options'=>
                    [
                        'id'=>'alloc-grid',
                        
                    ],
            ],
            'panel' => 
            [
                'heading'=>'<span class="panel-title"><h4><i class="glyphicon glyphicon-thumbs-up"></i>'.$this->title.'</h4></span>',
                'type'=>'info',        
                'footer'=>false
            ],
            'columns' => 
            [
                ['class' => 'kartik\grid\SerialColumn'],
                //'_id',
                'name',
                'wpLocCode',
                'region',
                'stateCode',
                'type',
                //'code',
                'CMCNo',
                'fileNo',
                //'yearId',
                'marks',
                'allocation',
                //'paymentDate',
                //'Remarks',
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => 'Actions',
                    'template'=>'{view}{update}{delete}',
                    'buttons'=> 
                    [  
                        'update'=>
                        function($url,$model,$key)
                        {
                            return Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/allocation-details/update','id'=>(string)$model->_id],['title' => 'Update','class'=>'updateBtn']);
                        },    
                    
                        'delete'=>
                        function($url,$model,$key)
                        {
                            return $model->status === AllocationDetails::STATUS_NEW ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/allocation-details/markdelete','id'=>(string)$model->_id],['title' => 'Delete']):' ';
                        }
                    ]
                ],
            ],
        ]);    
    ?>
</div>
<!---- modal --->
<div id="allocmodal" class="modal fade" role="dialog"  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title text-center">Allocations</h2>
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
  /*$this->registerJs(
   "$(document).on('ready pjax:success', function() {
     
     $('body').on('click','#createBtn', function(e){
       e.preventDefault();      
       $('#allocmodal').modal('show')
                  .find('#modal-content')
                  .load($(this).attr('href'));  
       });

     });
   ", $this::POS_END);*/ 
   ?>

<?php 

$loaderUrl= \Yii::$app->urlManager->createAbsoluteUrl("/themes/material-COC/assets/images/ajax-loader2.gif");
JSRegister::begin([
        'key' => 'modal-handler',
        'position' => \yii\web\View::POS_END
        ]); 
?>

<script>
    $('#createBtn,.updateBtn').on('click', function(e)
    {
        e.preventDefault();
        $('#allocmodal').modal('show').find('.modal-body').load($(this).attr('href'));
    });

    $('#allocmodal').on('hidden.bs.modal', function () 
    {
        $(this).find('.modal-body').html('<div align="center"><img src= "<?php echo $loaderUrl ?>" height="30" width="30"/></div>').end();
    });
</script>

<?php JSRegister::end(); ?>

