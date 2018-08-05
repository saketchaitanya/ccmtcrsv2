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
		<table width='100%'>
			<tr>
				<td>
					<div><b>Centre Name:</b> <?php echo $centreInfo['centreName'] ?>
					</div>
					<div><b>Centre Address:</b> <br/><?php echo $centreInfo['centreAdd'] ?>
					</div>

				<td>
					
						<div><b>Year: </b> <?php echo $data['year'] ?>
						</div>
						<div><b>FileNo:</b> <?php echo $centreInfo['fileNo'] ?>
						</div>
						<div><b>CompNo:</b> <?php echo $centreInfo['compNo'] ?>
						</div>
					
				</td>
			</tr>
		</table>
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
					<th> Seva Works</th><th> Publication Sale (Rupees)</th>
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
			<table width='100%'>
			<tr>
				<td>
					<div align='center'>
						<strong>LITERARY ACTIVITIES</strong>
					</div>
					<div>
						<div>Newsletter/Magazine: <?php echo $lit['haveNewsletter']; ?></div>
						<div>Periodicity: <?php echo $lit['periodicity']; ?></div>
						<div>Name: <?php echo $lit['name']; ?></div>
					</div>
				</td>
				<td>
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
				</td>
				<td>
				 	<div align='center'>
							<strong>PROJECT/CENTRE ESTABLISHMENT</strong>
					</div>
					<div>
						<div>Centre's own place: <?php echo $centreInfo['centreOwnsPlace'] ?></b></div>
						<div>Is your centre registered?: <?php echo $centreInfo['isCentreRegistered'] ?></b></div>
						<div>If Yes, Year of reg: <?php echo $centreInfo['regNo'] ?></div>
						<div>Registration Date: <?php echo $centreInfo['regDate'] ?></b></div>
					</div>
				</td>
			</tr>
			</table>
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

