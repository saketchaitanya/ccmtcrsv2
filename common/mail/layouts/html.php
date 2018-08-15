<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="https://fonts.googleapis.com/css?family=Fira+Sans|Roboto:500" rel="stylesheet"> 
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />

    <style>
    	body{
    		
    	}
		table{
			border-collapse: collapse;
			border:1px solid #5baa9a;
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
			color:seagreen;
		}
		.wrapper{
			/*margin:10px;*/
			padding:10px;
			font-family: 'Roboto', 'Fira Sans', sans-serif;
		}
		.header{
			/*background-color:rgb(2, 121, 201);*/
			background-color:#247ba0;
			color:white;
			font-weight: bold;			
			font-size:24px;
			min-height:100px;
			position:relative;
			text-align:center;
					
						
		}

		.footer{
			/*background-color:rgb(2, 121, 201);*/
			background-color:#247ba0;
			color:white;
			font-weight: bold;
			font-size:12px;
			min-height:100px;
			padding:5px;
			font-family: 'Courier';
			
		}
		.container{
			margin-left:15%;
			margin-right:15%;
			border:solid bold 1px #5baa9a;
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
  			/*position: absolute;
  			top: 50%;
		    left: 30%;
		    transform: translate(-50%, -30%);*/
		  			
		}
		.footercell
		{
			display:table-cell;
			vertical-align: middle;
			overflow:auto;
			text-align:center;
			padding:10px;

		}
		.cell-content
		{
			/*background-color:#D2E1F2; */
			background-color:#47ac99/*#83bfb3*/;
			font-size:16px; 
			min-height:400px;
			padding:10px;
			color:white;
		}
		hr{ 
			border: 0; 
			height: 1px;
			background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0)); 
		}

	</style>	
      <?php $this->head() ?>
</head>

<body>

    <?php $this->beginBody() ?>
			<div class='wrapper'>
    		<?= $content ?>
    		</div>
    		
   <?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
