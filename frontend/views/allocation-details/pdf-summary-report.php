<?php
	use \common\components\CommonHelpers;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;
  	use yii\helpers\ArrayHelper;
		$res = array();
  		$resdata=json_decode($response->data,true);
  		
  		$data = $resdata['data'];
		$model= $data['stateData'];
		$regions = $data['regions'];
		$year=$resdata['year'];
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
		<h4 class='text-center'><u>Central Chinmaya Mission Trust - Mumbai<br/>Summary of Evaluation of Questionnaires for Year: <?php echo $year ?></u></h4>
		<br/><br/>
		<table class='table'>
			<thead>
				<tr>
					<th> No of Centres</th>
					<th> State </th>
					<th align='right'> Amount </th>					
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td align='center'><b><?= $totalCentres ?></b>
					</td>
					<td><b>GRAND TOTAL( <?= $gCodeString ?> )</b>
					</td>
					<td  align='right'><b><?= $grandTotal ?></b>
					</td>
				</tr>
			</tfoot>
			<tbody>	
				<?php foreach($regions as $key=>$value):
					$reg = array_keys($value)[0]; 
					foreach($model as $m):
						if($m['region']==$reg): ?>
						 <tr>
							<td align='center'> <?= $m['centrecount'] ?> </td>
							<td> <?= $m['name'] ?> </td>
							<td align='right'> <?= $m['amount']  ?> </td>
						</tr>
						<?php endif; ?>
					<?php endforeach; ?>
					<tr>
						<td align='center'><b><?= $regions[$key]['totalCentres'] ?><b>
						</td>
						<td ><b>
							(<?= $value['groupCode'] ?>)-TOTAL CENTRES    REGIONAL (<?= strtoupper($regions[$key]['region']) ?>)</b>
						</td>
						<td align='right'><b><?= $regions[$key]['subtotal'] ?></b>
						</td>
					</tr>
				<?php endforeach; ?>
				
			</tbody>	
		</table>
	</div><!-- last div -->
	<br/><br/><br/>
	<div align='right'>
		<b>( <?= Yii::$app->params['SigningAuthority'] ?> )</b>
	</div>
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
	
