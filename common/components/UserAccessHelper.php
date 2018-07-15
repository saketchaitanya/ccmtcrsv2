<?php
namespace common\components;
use Yii;
use common\models\AuthItem;
use common\models\AuthItemSearch;
use yii\web\NotFoundHttpException;
use yii\mongodb\Query;
use common\models\User;
use common\models\UserProfile;
use  yii\mongodb\rbac\MongoDbManager;

class UserAccessHelper
{
	public static function getAllUserRoles()
	{
		$rows = new Query();
    	$rows->select(['id','username','email'])
    		 ->from('user')
    		 ->where(['status'=>USER::STATUS_ACTIVE]);
    	$users=$rows->all();
			$roles=array();
		$manager = new MongoDbManager;
		foreach ($users as $user)
		{
			$user_id=$user['_id'];
			$username=$user['username'];
			$email=$user['email'];
			$role=$manager->getRolesByUser($user_id);
			/*$roles[$username]=
								[	'username'=>$username,
									'roles'=>array_keys($role),
								];*/
			$roles[]=[	
									'username'=>$username,
									'roles'=>array_keys($role),
									'email'=>$email
								];
		}
		return $roles;
    }

    public static function getUnapprovedUsers()
	{
		$profilerows = new Query();
		$profilerows->select(['username'])
					->from ('userProfile');
		$profilequery = array_column($profilerows->all(),'username');
		
	 	$rows = new Query();
    	$rows->select(['id','username','email'])
    		 ->from('user')
    		 ->where(['status'=>USER::STATUS_ACTIVE])
    		 ->andwhere(['in','username', $profilequery]);
    	$users=$rows->all();
			$roles=array();
		$manager = new MongoDbManager;
		foreach ($users as $user)
		{
			$user_id=(string)$user['_id'];

			$username=$user['username'];
			$email=$user['email'];
			$profile= UserProfile::findbyUserid($user_id);
			if($profile):
				$profileid = $profile->_id;
			else:
				$profileid='';
			endif;
			$role=$manager->getRolesByUser($user_id);
			
			$roles[]=	[	'userid'=>(string)$user_id,
							'username'=>$username,
							'profileid'=>(string)$profileid,
							'roles'=>implode(array_keys($role),','),
							'email'=>$email
						];
		}
		$unappuserlist=array();
		foreach ($roles as $role)
		{
			if ($role['roles']=='unappuser')
			{
				$unappuserlist[]=$role;
			}

		}
	
		
		return $unappuserlist;
    }


    public static function getAllUserPermissions()
	{
		$rows = new Query();
    	$rows->select(['id','username'])
    		 ->from('user')
    		 ->where(['status'=>USER::STATUS_ACTIVE]);
    	$users=$rows->all();
		$permissions=array();
		$manager = new MongoDbManager;
		foreach ($users as $user)
		{
			$user_id=$user['_id'];
			$username=$user['username'];
			$permission=$manager->getPermissionsByUser($user_id);
			$permissions[]=
						[	'username'=>$username,
							'permissions'=>array_keys($permission),
						];
		}
		return $permissions;
    }

	public static function getAllUserRolesPermissions()
    {		
		$rows = new Query();
		$rows->select(['id','username','email'])
		 ->from('user')
		 ->where(['status'=>USER::STATUS_ACTIVE]);
		$users=$rows->all();

		$roles=array();
		$permissions=array();
		$manager = new MongoDbManager;

		
		foreach ($users as $user)
		{

			$user_id=$user['_id'];
			$username=$user['username'];
			$email=$user['email'];
			$role=$manager->getRolesByUser($user_id);
			$permission=$manager->getPermissionsByUser($user_id);
			$rolespermissions[]=
						[	'username'=>$username,
							'roles'=>array_keys($role),
							'permissions'=>array_keys($permission),
							'email'=>$email
						];
	    }
	    return $rolespermissions;
	}

	public static function getUserRoles($username)
    {
    	$auth = Yii::$app->authManager;
    	$user= User::findByUsername($username);
    	$roles= $auth->getRolesByUser($user['_id']);
    	return array_keys($roles);
    }

    public static function getUserPermissions($username)
    {
    	$auth = Yii::$app->authManager;
    	$user= User::findByUsername($username);
    	$permissions= $auth->getPermissionsByUser($user['_id']);
    	return array_keys($permissions);
    }

    public static function addUserRole($username, $newRole)
    {
    	 $auth = Yii::$app->authManager;   
    	 $item = $auth->getRole($newRole);   
    	 $user= User::findByUsername($username); 
         $auth->assign($item, $user['_id']);
    }

    public static function changeUserRole($username, $oldRole, $newRole)
    {
    	$auth = Yii::$app->authManager;
    	$item = $auth->getRole($oldRole);
    	$user= User::findByUsername($username);
		//$item = $item ? : $auth->getPermission($oldRole);
		$auth->revoke($item, $user['_id']);

		$newitem = $auth->getRole($newRole);
		$auth->assign($newitem,$user['_id']);
    }

    public static function revokeAllUserRoles($username)
    {
    	$auth = Yii::$app->authManager;
    	$user= User::findByUsername($username);
    	$auth->revokeAll($user['_id']);
    }
    
    public static function checkUserHasRole($username, $role)
    {

    	$user= User::findByUsername($username);
    	$userid= (string)$user['_id'];
    	$auth = Yii::$app->authManager;
    	$assmts= $auth->getAssignment($role, $userid);
    	$result=isset($assmts) ? true:false;
    	return $result;
    }

    public static function getUsersCreated($fromDate, $toDate)
    {
    	$from = $fromDate;
    	$to = $toDate;
    	$users = User::find()
    			->where(['status'=>User::STATUS_ACTIVE])
    			->andWhere(['between','created_at',$from, $to])
    			->asArray()
    			->all();

    	return $users;
    }

    public static function getCurrentUserType()
    {
    	$username= \Yii::$app->user->identity->username;
    	$user = User::findByUsername($username);
        $usertype = $user->usertype;

        return $usertype;
    }

   }
  ?>