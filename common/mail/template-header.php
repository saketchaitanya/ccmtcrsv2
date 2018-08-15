<?php
/**
 * These header file is to be included in all custom html mail templates
 * @param $title is to be provided in Mailer's Compose function's Parameter Array
 */
 ?>
<div class='container'>
	    <div class="header row">
	    	<div class='headercell'>
	    		<h2><?= Yii::$app->name ?> </h2>
		    	<?php 

		    		if(isset($sender))
		    		{
		    			$curruser=$sender;
		    		}
		    		elseif (\Yii::$app->user->isGuest){
		    			//if user is not logged in
		    			$curruser = 'Guest';
		    		}
		    		else
		    		{ 
		    			//if user is logged in
		    			$curruser = \Yii::$app->user->username;
		    		}
		    	 ?>
		    
		    	 <?php if(!isset($title))
		    	 {
		    	 	?>
		    	 	<h3>This message is sent by User: <?= $curruser ?></h3>
		    	 <?php	
			 	 }
			     else {
			     ?>
			     		<h3><?= $title ?></h3>
			     <?php
			     	}	
			    	 
		    	 ?>
	    	</div>
    	</div>
    	<div class='main row'>
    		<div class='cell'>
    			<div class='cell-content'>