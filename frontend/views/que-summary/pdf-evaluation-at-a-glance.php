<?php
	use \common\components\CommonHelpers;
	use yii\Helpers\ArrayHelper;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;

  	CcmtCrsAsset::register($this);

	$data = unserialize($response->data);
	$model=$data['summary'];
	$years = $model['years'];
	$queSummary = $model['queSummary'];
	$allocations = $model['allocations'];
	$centreInfo = $data['centreInfo'];

	if (class_exists('yii\debug\Module')) {
    	$this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
	} 	

	if($model): 
	?>

	<div> 
		<table>
			<tr>
				<td width="70%" colspan="3">
					<div>
						<b>Centre Name:</b> <?php echo $centreInfo['centreName'] ?>
					</div>
					<div>
						<b>Centre Address:</b> <br/><?php echo $centreInfo['centreAdd'] ?>
					</div>
				</td>
				<td> &nbsp;
				</td>
				<td>
					<div>
						<b>FileNo:</b> <?php echo $centreInfo['fileNo'] ?>
					</div>
					<div>
						<b>CMCNo:</b> <?php echo $centreInfo['CMCNo'] ?>
					</div>
					<div>
						<b>CompNo:</b> <?php echo $centreInfo['compNo'] ?>
					</div>
				</td>
			</tr>
		</table>	
	</div>
		
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th> Marks--><br/>For Year</th>
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
					<th align='right'> Total</th>
					<th align='right'> Amount Alloted <br/>(in Rupees)</th>
					<th align='right'> Date of Payment</th>
				</tr>
			</thead>
			<?php foreach($years as $year): ?>
				<tr align='right'>
					<td> 
						<?= $year[0]['yearString'] ?> 
					</td>
						<?php 
							$queSum = $queSummary[$year[0]['yearId']]; 
					  		$alloc = $allocations[$year[0]['yearId']][0];
						?>
						<?php 
							if(isset($queSum)):
							$sum =ArrayHelper::index($queSum,'month');
						?>
							<td align='right'> <?php if(array_key_exists('Apr',$sum)) echo $sum['Apr']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('May',$sum)) echo $sum['May']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Jun',$sum)) echo $sum['Jun']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Jul',$sum)) echo $sum['Jul']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Aug',$sum)) echo $sum['Aug']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Sep',$sum)) echo $sum['Sep']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Oct',$sum)) echo $sum['Oct']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Nov',$sum)) echo $sum['Nov']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Dec',$sum)) echo $sum['Dec']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Jan',$sum)) echo $sum['Jan']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Feb',$sum)) echo $sum['Feb']['marks'] ?></td>
							<td align='right'> <?php if(array_key_exists('Mar',$sum)) echo $sum['Mar']['marks'] ?></td>
							<td align='right'><?php echo $alloc['marks'] ?></td>
							<td align='right'><?php  if(isset($alloc['allocation'])) echo (int)$alloc['allocation'] ?></td>
							<td><?php  if(isset($alloc['paymentDate'])) echo $alloc['paymentDate'] ?></td>
						<?php endif; ?>
				</tr>
			<?php endforeach; ?>	
		</table>
			
	
	<?php
	else:
		$string = "<div class='alert alert-danger' role='alert'>
					Data cannot be fetched or there is no data for the centre.
			   </div>";
			   echo $string;
	endif;

	
	?>
