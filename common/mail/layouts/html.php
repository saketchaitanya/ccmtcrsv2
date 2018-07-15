<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />

    <style>
		table{
			border-collapse: collapse;
			border:1px solid gray;
			width:80%;
		}
		th{
			text-align: center;
			background-color: #e7eff3;
		}
		tr{
			text-align:left;
		}
		tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		.header{
			background-color:rgb(2, 121, 201);
			color:white;
			font-weight: bold;
			font-size:24px;
			min-height:100px;
			position:relative;
			text-align:center;
					
						
		}

		.footer{
			background-color:rgb(2, 121, 201);
			color:white;
			font-weight: bold;
			font-size:12px;
			min-height:100px;
			padding:5px;
			
		}
		.container{
			margin-left:15%;
			margin-right:15%;
			border:solid bold 1px grey;
			display:table;

		}
		.row{
			display:table-row;
			vertical-align: middle;
		}

		.cell
		{
			display:table-cell;
			vertical-align: middle;
			overflow:auto;
			

		}
		.headercell
		{
			display:table-cell;
			vertical-align: middle;
  			position: absolute;
  			top: 50%;
		    left: 30%;
		    transform: translate(-50%, -30%);
		  			
		}

	</style>	
      <?php $this->head() ?>
</head>

<body>

    <?php $this->beginBody() ?>
	
    		<?= $content ?>
    		
   <?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
