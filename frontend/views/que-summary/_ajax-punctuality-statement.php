<?php
	use \common\components\CommonHelpers;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;

  		CcmtCrsAsset::register($this);

		$data = $response->data;
		$model = $data['punctuality'];
		$centreData = $data['centreData']; 
		$reminders = $data['reminders'];
		$year = $data['forYear'];
		$keys = $data['keys'];
		
	?>
	<?php	if($model): ?>
	<div class='table-responsive'>
		<h4 class='text-center'>Central Chinmaya Mission Trust - Mumbai<br/>Punctuality Statement for Year: <?php echo $year ?></h4>
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
					<th>Reminders Sent</th>
				</tr>
			</thead>
			<tbody>	
				<?php /*foreach($model as $key=>$value):*/ ?>
				<?php foreach ($keys as $key): ?>
				<tr>
					<td> <?= $centreData[$key]['fileNo'] ?> </td>
					<td> <?= $centreData[$key]['CMCNo'] ?> </td>
					<td> <?= ucwords(strtolower($centreData[$key]['centreName']))  ?> </td>
					<td> <?= $centreData[$key]['stateCode']  ?> </td>
					<td> <?= isset($model[$key]['Apr'])? ($model[$key]['Apr']=($model[$key]['Apr'] == 'Late')? "<span style='color:red'>".$model[$key]['Apr']."</span>":$model[$key]['Apr']):'ns'?> </td>
					<td> <?= isset($model[$key]['May'])? ($model[$key]['May']=($model[$key]['May'] == 'Late')? "<span style='color:red'>".$model[$key]['May']."</span>":$model[$key]['May']):'ns'?> </td>
					<td> <?= isset($model[$key]['Jun'])? ($model[$key]['Jun']=($model[$key]['Jun'] == 'Late')? "<span style='color:red'>".$model[$key]['Jun']."</span>":$model[$key]['Jun']):'ns'?> </td>
					<td> <?= isset($model[$key]['Jul'])? ($model[$key]['Jul']=($model[$key]['Jul'] == 'Late')? "<span style='color:red'>".$model[$key]['Jul']."</span>":$model[$key]['Jul']):'ns'?> </td>
					<td> <?= isset($model[$key]['Aug'])? ($model[$key]['Aug']=($model[$key]['Aug'] == 'Late')? "<span style='color:red'>".$model[$key]['Aug']."</span>":$model[$key]['Aug']):'ns'?> </td>
					<td> <?= isset($model[$key]['Sep'])? ($model[$key]['Sep']=($model[$key]['Sep'] == 'Late')? "<span style='color:red'>".$model[$key]['Sep']."</span>":$model[$key]['Sep']):'ns'?> </td>
					<td> <?= isset($model[$key]['Oct'])? ($model[$key]['Oct']=($model[$key]['Oct'] == 'Late')? "<span style='color:red'>".$model[$key]['Oct']."</span>":$model[$key]['Oct']):'ns'?> </td>
					<td> <?= isset($model[$key]['Nov'])? ($model[$key]['Nov']=($model[$key]['Nov'] == 'Late')? "<span style='color:red'>".$model[$key]['Nov']."</span>":$model[$key]['Nov']):'ns'?> </td>
					<td> <?= isset($model[$key]['Dec'])? ($model[$key]['Dec']=($model[$key]['Dec'] == 'Late')? "<span style='color:red'>".$model[$key]['Dec']."</span>":$model[$key]['Dec']):'ns'?> </td>
					<td> <?= isset($model[$key]['Jan'])? ($model[$key]['Jan']=($model[$key]['Jan'] == 'Late')? "<span style='color:red'>".$model[$key]['Jan']."</span>":$model[$key]['Jan']):'ns'?> </td>
					<td> <?= isset($model[$key]['Feb'])? ($model[$key]['Feb']=($model[$key]['Feb'] == 'Late')? "<span style='color:red'>".$model[$key]['Feb']."</span>":$model[$key]['Feb']):'ns'?> </td>
					<td> <?= isset($model[$key]['Mar'])? ($model[$key]['Mar']=($model[$key]['Mar'] == 'Late')? "<span style='color:red'>".$model[$key]['Mar']."</span>":$model[$key]['Mar']):'ns'?> </td>
					<td> <?= $reminders[$key] ?> </td>

				</tr>
				<?php endforeach; ?>
			</tbody>	
		</table>
	Explanations: ns = not sent, <span style="color:red">Late</span> = submitted later than one month, Intime = punctual(sent within one month of the month for which it is filled)
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

