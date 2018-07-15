<?php

class m180205_114245_init_mongo_rbac extends \yii\mongodb\Migration
{
    public function up()
    {
    	$auth = Yii::$app->authManager;

        // add "createUser" permission
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create a User';
        $auth->add($createUser);

        // add "updateUser" permission
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update User';
        $auth->add($updateUser);

         // add "deleteUser" permission
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete User';
        $auth->add($deleteUser);

        // add "authorizeUser" permission
        $authUser = $auth->createPermission('authUser');
        $authUser->description = 'Authorize User';
        $auth->add($authUser);

         // add "suspendUser" permission
        $unauthUser = $auth->createPermission('unauthUser');
        $unauthUser->description = 'Unauthorize User';
        $auth->add($unauthUser);

        // add "evaluator" role and give this role the "updateUser" permission
        $evaluator = $auth->createRole('evaluator');
        $auth->add($evaluator);
        $auth->addChild($evaluator, $authUser);
        $auth->addChild($evaluator, $unauthUser);


        // add "admin" role and give this roles of add,update,delete permission
        // as well as the permissions of the "authentication & deauthenticate" role
        // like SQLDbs roles cannot become child of another role (e.g. evaluator cannot be added as
        // child of admin to inherit evaluators role)
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);

    }

    public function down()
    {
        echo "m180205_114245_init_mongo_rbac cannot be reverted.\n";
        /*$auth = Yii::$app->authManager;
        $auth->removeAll();*/
        return false;
    }
}
