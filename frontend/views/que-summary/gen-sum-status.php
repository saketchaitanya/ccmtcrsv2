<?php
	use \common\components\CommonHelpers;
	use frontend\assets\CcmtCrsAsset;
  	use yii\bootstrap\Alert;

  	CcmtCrsAsset::register($this);
	$message = $response->data;
	if($message): 
		$string = "<div class='alert alert-warning' role='alert'>
					Summary data has been successfully inserted.
		   		</div>";
	else:
		$string = "<div class='alert alert-danger' role='alert'>
					Summary data has failed to update successfully
			   </div>";
	endif;

	$string = CommonHelpers::removeNonPrintingChars($string);
	
	echo $string;
	?>
