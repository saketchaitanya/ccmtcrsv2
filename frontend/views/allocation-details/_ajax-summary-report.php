<?php
	use \common\components\CommonHelpers;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;

  		CcmtCrsAsset::register($this);

		$data = $response->data;
		$model=$data['marksData'];
		$noOfCentres = $data['noOfCentres'];
		$statelist = $data['stateList']; 
		$amount = $data['amount'];
		$keys = $data['keys'];
	?>
	<?php if($model): ?>
	<div class='table-responsive'>
		<h4 class='text-center'>Central Chinmaya Mission Trust - Mumbai<br/>Details of Questionnaire Evaluation for Year: <?php echo $year ?></h4>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th> No of Centres</th>
					<th> State </th>
					<th> Amount </th>					
				</tr>
			</thead>
			<tbody>	
				<?php foreach($keys as $key): ?>
				<tr>
					<td> <?= $noOfCentres[$key]['fileNo'] ?> </td>
					<td> <?= $stateList[$key]['state'] ?> </td>
					<td> <?= $amount[$key]['amount']  ?> </td>
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

