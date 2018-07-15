<?php

class m180205_125258_user_authunauth_for_admin extends \yii\mongodb\Migration
{
    public function up()
    {
    	$auth = Yii::$app->authManager;
    	$authUser= $auth->getPermission('authUser');
    	$unauthUser = $auth->getPermission('unauthUser');
    	$admin = $auth->getRole('admin');

    	$auth->addChild($admin, $authUser);
    	$auth->addChild($admin, $unauthUser);

    }

    public function down()
    {
        echo "m180205_125258_user_authunauth_for_admin cannot be reverted.\n";

        return false;
    }
}
