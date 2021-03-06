<?php
	use \common\components\CommonHelpers;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;

  	CcmtCrsAsset::register($this);

	$data = $response->data;
	$model=$data['summary'];
	$lit = $data['literature'];
	$vidya = $data['vidyalaya'];
	$centreInfo = $data['centreInfo'];


	if($model): 
	?>

	<div style=' border:1px solid #dcdcdc; min-height:100px;padding:10px'> 
		<div class='row'>
			<div class='col-xs-12 col-md-8'>
				<div><b>Centre Name:</b> <?php echo $centreInfo['centreName'] ?>
				</div>
				<div><b>Centre Address:</b> <br/><?php echo $centreInfo['centreAdd'] ?>
				</div>
			</div>
			<div class='col-xs-12 col-md-4'>
				<div><b>Year: </b> <?php echo $data['year'] ?>
				</div>
				<div><b>FileNo:</b> <?php echo $centreInfo['fileNo'] ?>
				</div>
				<div><b>CMCNo:</b> <?php echo $centreInfo['CMCNo'] ?>
				</div>
				<div><b>CompNo:</b> <?php echo $centreInfo['compNo'] ?>
				</div>
			</div>
		</div>
	</div>
	<div class='table-responsive'>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th> For Month</th>
					<th> Recieved On</th>
					<th> Mission members<br/>(Nos)</th>
					<th> Balvihar<br/>(Nos)</th>
					<th> Yuvakendra<br/>(Nos)</th>
					<th> Study Groups<br/>(Nos)</th>
					<th> Devi Groups<br/>(Nos)</th>
					<th> Other Classes<br/>(Nos)</th>
					<th> Chinmaya Vanaprasthas<br/>(Nos)</th>
					<th> Acharya Classes <br/>(Nos)</th>
					<th> Gyana Yagnas & camps<br/>(Nos)</th>
					<th> Festivals and Pujas<br/>(Nos)</th>
					<th> Other Prachar works<br/>(Nos)</th>
					<th> Seva Works</th><th> Publication Sale ( ₹ )</th>
					<th> Any Other Info</th>
				</tr>
			</thead>
	<?php 
			foreach($model as $m): 
				$act = $m['dataArray'];
				?>

				<tr>
					<td> <?= date('M-Y',$m['forDateTS']) ?> </td>
					<td> <?= $m['sent_at'] ?> </td>
					<td> <?= $act['totalMembers'] ?> </td>
					<td> <?= $act['Balvihars'] ?> </td>
					<td> <?= $act['CHYKs'] ?> </td>
					<td> <?= $act['StudyGroups'] ?> </td>
					<td> <?= $act['DeviGroups'] ?> </td>
					<td> <?= $act['OtherGroups'] ?> </td>
					<td> <?= $act['ChinmayaVanprasthas'] ?> </td>
					<td> <?= $act['AcharyaClasses'] ?> </td>
					<td> <?= $act['GyanYagnasAndCamps'] ?> </td>
					<td> <?= $act['FestivalsAndPujas'] ?> </td>
					<td> <?= $act['OtherPracharWorks'] ?> </td>
					<td> <?= $act['SevaWorks'] ?> </td>
					<td> <?= $act['PublicationSale'] ?> </td>
					<td> <?= $act['AnyOtherInfo'] ?> </td>
				</tr>
			<?php endforeach; ?>	
		</table>
			
	</div><!-- last div -->
	<hr>
		<div>
			<div class='col-xs-12 col-md-6'>
				<div style='margin:2px; border:1px solid #dcdcdc; min-height:150px;padding:5px'>
					<div align='center'>
						<strong>LITERARY ACTIVITIES</strong>
					</div>
					<div>Newsletter/Magazine: <?php echo $lit['haveNewsletter']; ?></div>
					<div>Periodicity: <?php echo $lit['periodicity']; ?></div>
					<div>Name: <?php echo $lit['name']; ?></div>
					<div>Last Update Date:<?php echo $lit['updatedate']?></div>
				</div>
			</div>
			<div class='col-xs-12 col-md-6'>
				<div style='margin:2px; border:1px solid #dcdcdc; min-height:150px; padding:5px'>
					<div align='center'>
						<strong>CHINMAYA VIDYALAYA</strong>
					</div>
					<div>
						<div>Balvihars in schools: <?php echo $vidya['balviharStatus'] ?></div>
						<div>Number of classes: <?php echo $vidya['noOfClasses'] ?></div>
						<div>Frequency of balvihars: <?php echo  $vidya['balviharFrequency'] ?></div>
						<div>Is CVP implemented: <?php echo  $vidya['cvpImplemented'] ?></div>
						<div>CVP Coverage: <?php echo $vidya['cvpCoverage'] ?></div>
					</div>
				</div>
			</div>
			
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
