<?php
	use \common\components\CommonHelpers;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;

  		CcmtCrsAsset::register($this);

		$data = $response->data;
		$model=$data['marksData'];
		$totalMarks = $data['totalMarks'];
		$centreData = $data['centreData']; 
		$codes = $data['centreCodes'];
		$cities = $data['centreCities'];
		$year1 = $data['forYear1'];
		$allocations1 = $data['allocations1'];
        $totalStates1 = $data['totalStates1'];
		if (isset($data['forYear2'])):
			$year2 = $data['forYear2'];
	        $allocations2 = $data['allocations2'];
        	$totalStates2 = $data['totalStates2'];
        endif;
	?>
	<?php	if($model)
	{ ?>
		<h4 class='text-center'>EVALUATION OF MONTHLY QUESTIONNAIRE FOR YEAR: <?php echo $year1 ?></h4>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th> SrNo</th>
					<th> CMC No</th>
					<th> City/Town </th>
					<th> Centre Name</th>
					<th> Apr</th>
					<th> May</th>
					<th> Jun</th>
					<th> Jul</th>
					<th> Aug</th>
					<th> Sep</th>
					<th> Oct</th>
					<th> Nov</th>
					<th> Dec</th>
					<th> Jan</th>
					<th> Feb</th>
					<th> Mar</th>
					<th> Total Marks</th>
					<th><?= $year1 ?> (Rupees)</th>
					<?php if(isset($year2)): ?>
						<th><?= $year2 ?> (Rupees)</th>
					<?php endif; ?>
					<th>Remarks</th>
					
				</tr>
			</thead>
			<tbody>	
				<?php $count = 1; ?>
				<?php foreach ($totalStates1 as $stkey=>$value): ?>
					<tr class='bg-warning'>
						<td></td>
						<td></td>
						<td ><strong><?= $value['stateName'] ?></strong></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align='right'><strong><?= \Yii::$app->formatter->asDecimal($value['allocation']) ?></strong></td>
						<?if(isset($year2)): ?>
							<td align='right'><strong><?= \Yii::$app->formatter->asDecimal($totalStates2[$stkey]['allocation']) ?></strong></td>
						<?php endif; ?>
						<td></td>
					</tr>

					<?php foreach($codes as $code):?>
						<?php if($centreData[$code]['stateCode']==$stkey):?>
							<tr>
								<td> <?= $count ?> </td>
								<td> <?= $centreData[$code]['CMCNo'] ?> </td>
								<td> <?= $cities[$code] ?> </td>
								<td> <?= ucwords(strtolower($centreData[$code]['centreName']))  ?> </td>
								<!-- <td> <?= $centreData[$code]['stateCode']  ?> </td> -->
								<td> <?= isset($model[$code]['Apr'])? $model[$code]['Apr']['marks']:'' ?> </td>
								<td> <?= isset($model[$code]['May'])? $model[$code]['May']['marks']:'' ?> </td>
								<td> <?= isset($model[$code]['Jun'])? $model[$code]['Jun']['marks']:''?> </td>
								<td> <?= isset($model[$code]['Jul'])? $model[$code]['Jul']['marks']:''?> </td>
								<td> <?= isset($model[$code]['Aug'])? $model[$code]['Aug']['marks']:''?> </td>
								<td> <?= isset($model[$code]['Sep'])? $model[$code]['Sep']['marks']:''?> </td>
								<td> <?= isset($model[$code]['Oct'])? $model[$code]['Oct']['marks']:''?> </td>
								<td> <?= isset($model[$code]['Nov'])? $model[$code]['Nov']['marks']:''?> </td>
								<td> <?= isset($model[$code]['Dec'])? $model[$code]['Dec']['marks']:''?> </td>
								<td> <?= isset($model[$code]['Jan'])? $model[$code]['Jan']['marks']:''?> </td>
								<td> <?= isset($model[$code]['Feb'])? $model[$code]['Feb']['marks']:''?> </td>
								<td> <?= isset($model[$code]['Mar'])? $model[$code]['Mar']['marks']:''?> </td>
								<td> <strong> <?= isset($totalMarks[$code])?$totalMarks[$code]:'' ?> </strong></td>
								<td align='right'> <?= isset($allocations1[$code])? \Yii::$app->formatter->asDecimal($allocations1[$code]) : '' ?> </strong> </td>
								<?php if (isset($year2)): ?>
									<td align='right'> <?= isset($allocations2[$code])? \Yii::$app->formatter->asDecimal($allocations2[$code]) : '' ?> </strong> </td>
								<?php endif; ?>
								<td></td>
							</tr>
							<?php $count++ ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endforeach; ?>
				
			</tbody>	
		</table>
		
	<?php
	}	
	else
	{
		$string = "<div class='alert alert-danger' role='alert'>
					Data cannot be fetched or there is no data for the centre.
			   </div>";
			   echo $string;
	}
	?>
	<?php
	if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
} ?>

