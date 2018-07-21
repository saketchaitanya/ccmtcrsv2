<?php
	use \common\components\CommonHelpers;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;

  		CcmtCrsAsset::register($this);

		$data = unserialize($response->data);
		$model=$data['marksData'];
		$totalMarks = $data['totalMarks'];
		$centreData = $data['centreData']; 
		$reminders = $data['reminders'];
		$year = $data['forYear'];
		$keys = $data['keys'];
	?>
	<?php	if($model): ?>
		<h4 class='text-center'>Central Chinmaya Mission Trust - Mumbai<br/>Details of Questionnaire Evaluation for Year: <?php echo $year ?></h4>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th> File No</th>
					<th> CMC No</th>
					<th> Centre Name</th>
					<th> State Code</th>
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
					<th> Total</th>
					<th>Reminders Sent</th>
				</tr>
			</thead>
			<tbody>	
				<?php foreach($keys as $key): ?>
				<tr>
					<td> <?= $centreData[$key]['fileNo'] ?> </td>
					<td> <?= $centreData[$key]['CMCNo'] ?> </td>
					<td> <?= ucwords(strtolower($centreData[$key]['centreName']))  ?> </td>
					<td> <?= $centreData[$key]['stateCode']  ?> </td>
					<td> <?= isset($model[$key]['Apr'])? $model[$key]['Apr']['marks']:'' ?> </td>
					<td> <?= isset($model[$key]['May'])? $model[$key]['May']['marks']:'' ?> </td>
					<td> <?= isset($model[$key]['Jun'])? $model[$key]['Jun']['marks']:''?> </td>
					<td> <?= isset($model[$key]['Jul'])? $model[$key]['Jul']['marks']:''?> </td>
					<td> <?= isset($model[$key]['Aug'])? $model[$key]['Aug']['marks']:''?> </td>
					<td> <?= isset($model[$key]['Sep'])? $model[$key]['Sep']['marks']:''?> </td>
					<td> <?= isset($model[$key]['Oct'])? $model[$key]['Oct']['marks']:''?> </td>
					<td> <?= isset($model[$key]['Nov'])? $model[$key]['Nov']['marks']:''?> </td>
					<td> <?= isset($model[$key]['Dec'])? $model[$key]['Dec']['marks']:''?> </td>
					<td> <?= isset($model[$key]['Jan'])? $model[$key]['Jan']['marks']:''?> </td>
					<td> <?= isset($model[$key]['Feb'])? $model[$key]['Feb']['marks']:''?> </td>
					<td> <?= isset($model[$key]['Mar'])? $model[$key]['Mar']['marks']:''?> </td>
					<td> <?= isset($totalMarks[$key])?$totalMarks[$key]:'' ?></td>
					<td> <?= $reminders[$key] ?> </td>

				</tr>
				<?php endforeach; ?>
			</tbody>	
		</table>
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
	
