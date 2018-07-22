<?php
	use \common\components\CommonHelpers;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;
  	use yii\helpers\ArrayHelper;

  		CcmtCrsAsset::register($this);

		$data = $response->data['data'];
		
		$model=$data['stateData'];
		$regions = $data['regions'];
		$year=$response->data['year'];
		$tCentres = ArrayHelper::getColumn($regions,'totalCentres');
		$ftotal = ArrayHelper::getColumn($regions,'subtotal');
		$gcodes = ArrayHelper::getColumn($regions,'groupCode');
		$gcodesstr = array_reduce($gcodes,function($v1,$v2)
						{
							return $v1.' + '.$v2;	
						}
			);
		$gCodeString = substr($gcodesstr,2);
		$totalCentres = array_sum($tCentres);
		$grandTotal = array_sum($ftotal);

	?>
	<?php if($model): ?>
	<div class='table-responsive'>
		<h4 class='text-center'>Central Chinmaya Mission Trust - Mumbai<br/>Summary of Evaluation of monthly Questionnaire for Year: <?php echo $year ?></h4>
		<table class='table table-condensed'>
			<thead>
				<tr>
					<th> No of Centres</th>
					<th> State </th>
					<th align='right'> Amount </th>					
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td><?= $totalCentres ?>
					</td>
					<td>Grand Total ( <?= $gCodeString ?> )
					</td>
					<td  align='right'><?= $grandTotal ?>
					</td>
				</tr>
			</tfoot>
			<tbody>	
				<?php foreach($regions as $key=>$value):
					$reg = array_keys($value)[0]; 
					foreach($model as $m):
						if($m['region']==$reg): ?>
						 <tr>
							<td> <?= $m['centrecount'] ?> </td>
							<td> <?= $m['name'] ?> </td>
							<td align='right'> <?= $m['amount']  ?> </td>
						</tr>
						<?php endif; ?>
					<?php endforeach; ?>
					<tr style='font-weight:bold' >
						<td> <?= $regions[$key]['totalCentres'] ?>
						</td>
						<td >
							(<?= $value['groupCode'] ?>)-TOTAL CENTRES &emsp;REGIONAL (<?= strtoupper($regions[$key]['region']) ?>)
						</td>
						<td style='text-align:right'><?= $regions[$key]['subtotal'] ?>
						</td>
					</tr>
				<?php endforeach; ?>
				
			</tbody>	
		</table>
	</div><!-- last div -->
	
	<?php
	else:
		$string = "<div class='alert alert-danger' role='alert'>
					Data cannot be fetched or there is no data for the centre.
			   </div>";
			   echo $string;
	endif;
	?>
	<?php
	if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
} ?>

