<?php
use common\models\User;

class m180207_163323_assign_admin_roles extends \yii\mongodb\Migration
{
    public function up()
    {
    	$auth = Yii::$app->authManager;
    	$userlist=['saketchaitanya','br_krupesh'];
    	$admin = $auth->getRole('admin');

    	for ($i=0; $i<sizeof($userlist); $i++)
    	{
    		$user= User::findByUsername($userlist[$i]);
    		$auth->assign($admin, $user['_id']);
    	}
    }

    public function down()
    {
        echo "m180207_163323_assign_admin_roles cannot be reverted.\n";

        return false;
    }
}
