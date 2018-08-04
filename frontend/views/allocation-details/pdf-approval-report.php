<?php
	use \common\components\CommonHelpers;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;
  	use yii\helpers\ArrayHelper;
  	use common\models\RegionMaster;
  		CcmtCrsAsset::register($this);

		$data = $response->data;

		$allCentreCodes = $data['allCentreCodes'];
		$region = $data['region'];
		$allocations1 = $data['allocations1']; 
		$statestotal1 = $data['statestotal1'];
		$regtotal1 = $data['regtotal1'];
		$yearId1 = $data['yearId1'];
		$year1 = $data['year1'];
		$states1 = array_keys($statestotal1);

		if(isset($data['yearId2'])):
			$allocations2 = $data['allocations2']; 
			$statestotal2 = $data['statestotal2'];
			$regtotal2 = $data['regtotal2'];
			$yearId2 = $data['yearId2'];
			$year2 = $data['year2'];
		endif;
		//\yii::$app->yiidump->dump($statestotal1);
		/*	\yii::$app->yiidump->dump($allocations1);
		\yii::$app->yiidump->dump($allCentreCodes);
		exit();*/
	?>
	<?php if($data): ?>
	<div class='table-responsive'>
		<h4 class='text-center'> EVALUATION OF QUESTIONNAIRE FOR <?= $year1 ?> ( <?= strtoupper($region[1]) ?>  REGION )</h4>
		<table class='table table-bordered'>
			<thead>

				<tr>
					<th>Sr No </th>
					<th> Computer Code</th>
					<th> Centre </th>
					<th> City/Town</th>
					<th><?= $year1 ?> </th>
					<?php if(!empty($year2)):?>
					<th><?php echo $year2 ?> </th>
					<?php endif; ?>					
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					
					<td><strong> Total Amount for <?= $region[1] ?> Region</strong></td>
					<td></td>
					<td align='right'><strong><?= $regtotal1 ?></strong></td> 
					<?php if(isset($regtotal2)): ?>
						<td align='right'><strong>
							<?php echo $regtotal2 ?></strong>
						</td> 
					<?php endif; ?>
				</tr>
			</tfoot>
			<tbody>	
				<?php $count = 1;
				    foreach ($states1 as $stkey=>$stValue): ?>
				    	<?php ?>
				    	<tr><td></td>
						 	<td></td>
							<td><strong>
								<?= strtoupper($statestotal1[$stValue]['stateName']) ?>
								</strong>
							 </td>
							<td></td>
							<td></td>
							<?php if (isset($yearId2)): ?>
							<td></td>
							<?php endif; ?>
						</tr>
					<?php foreach($allCentreCodes as $ctkey=>$ctValue):?>
						<tr>
						<?php if ($ctValue['stateCode']==$stValue): ?>
							<td><?= $count ?></td>	
							<td><?php if(isset($ctValue['CMCNo'])) echo $ctValue['CMCNo']; ?> 
							</td>
							<td><?= $ctValue['name']?> </td>
							<td></td>
							<td align='right'><?php 
									$arr = $allocations1[$stValue];
									for ($i=0; $i<sizeof($arr); $i++):
										if($arr[$i]['wpLocCode']==$ctValue['wpLocCode']):
											echo $arr[$i]['allocation'];
										endif;
									endfor; ?>		
							</td>
							<?php if(isset($yearId2)): ?>
								<td align='right'>	
								<?php	$arr1 = $allocations2[$stValue];
									for ($i=0; $i<sizeof($arr); $i++):
										if($arr1[$i]['wpLocCode']==$ctValue['wpLocCode']):
											echo $arr1[$i]['allocation'];
										endif;
									endfor;
								?>
								</td>
							<?php endif;
								$count++; ?>
						<?php endif; ?>
						</tr>
					<?php endforeach; ?>
					<tr>	
						<td></td>
					 	<td></td>	
						<td><strong>Total Amount for <?= $statestotal1[$stValue]['stateName'] ?></strong></td>
						<td></td>
						<td align='right'><strong><?= $statestotal1[$stValue]['allocation'] ?></strong></td>
						
						<?php if(isset($statestotal2[$stValue]['allocation'])): ?>
							<td align='right'><strong>		
								<?php echo $statestotal2[$stValue]['allocation'] ?>
							</strong></td>
						<?php endif; ?>
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

	<?php /*$this->registerCss(" 
							  thead, tfoot { background-color: #F5F5F5; font-weight:bold;}
							  thead {border-top:2px solid gray}
							  tfoot {border-bottom:2px solid gray; border-top:2px double gray}
							");*/	?>