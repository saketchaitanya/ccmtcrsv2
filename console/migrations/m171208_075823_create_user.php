<?php

class m171208_075823_create_user extends \yii\mongodb\Migration
{
    public function up()
    {
		$collection= Yii::$app->mongodb->getCollection("user");
    	$collection->insert(
	    		[
					"username"=>"admin",
					"password"=>Yii::$app->getSecurity()->generatePasswordHash("admin"),
					"email"=>"admin@chinmayamission.in",
					"auth_key"=>Yii::$app->security->generateRandomString(),
					"status"=>10,
	    		]
    	); 
    }

    public function down()
    {
        echo "m171208_075823_create_user_collection cannot be reverted.\n";

        return false;
    }
}
