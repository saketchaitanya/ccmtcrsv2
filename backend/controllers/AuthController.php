<?php

namespace backend\controllers;

use Yii;
use common\models\AuthItem;
use common\models\AuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\mongodb\Query;
use common\models\User;
use backend\models\DeleteUser;
use yii\mongodb\rbac\MongoDbManager;
use common\components\UserAccessHelper;
use common\components\Questionnaire\ReminderManager;
use yii\validators\Validator;
use yii\web\Response;

/**
 * AuthController implements the CRUD actions for AuthItem model.
 */
class AuthController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Displays entire list of permission for all users.
     * 
     */
	public function actionViewUserPermissions()
	    {
    		$permissions=UserAccessHelper::getAllUserPermissions();
    		return $this->render('view_user_permissions', [
            'model' => $permissions,
       		 ]);	    		
    		
	    }

	 public function actionEditUserRole($username)
    {
        $roles=array();
        $roles['username'] = $username;

        $roles['roles'] =UserAccessHelper::getUserRoles($username);

       return $this->render('edit_user_role', [
            'roles' => $roles,
        ]);
    }

    public function actionUpdateUserRole()
    {
       
        $username=Yii::$app->request->post('username');
        $currentRole=Yii::$app->request->post('currentRole');        
        $selectedRole= Yii::$app->request->post('selectedrole');
        
        if($currentRole)
        {
            UserAccessHelper::changeUserRole($username,$currentRole,$selectedRole);
        }
        else
        {
            UserAccessHelper::addUserRole($username,$selectedRole);
        }
        //now reload the roles page with updated role
    	$roles=UserAccessHelper::getAllUserRoles();
		  return $this->render('view_user_roles', [
            'model' => $roles,
       		 ]);
    }

	/**
     * Displays entire list of roles for all users.
     * 
     */
	    public function actionViewUserRoles()
	    {
	        $roles=UserAccessHelper::getAllUserRoles();
            
    		return $this->render('view_user_roles', [
            'model' => $roles,
       		 ]);	    		
    		
	    }

       

        public function actionViewUsersToDelete()
        {
            $status=USER::STATUS_ACTIVE;
            $users= User::find()->where(['status' => $status])->all();
            return $this->render('view_users_to_delete', [
            'model' => $users,
             ]);                
            
        }

        public function actionDeleteUser($username)
        {
            $model = new DeleteUser;
            $model->username=$username;
            $model->roles=UserAccessHelper::getUserRoles($model->username);
            $model->permissions=UserAccessHelper::getUserPermissions($model->username);
           if (Yii::$app->request->post('username')) 
            {
                
                //$validator=new Validator;
                $user=User::findByUsername($username);

                if (!$user || $user->status== User::STATUS_DELETED)
                { 
                   $session = Yii::$app->session;
                   $session->setFlash('noUserExist', true); 
                     return $this->render('delete_user',
                     [
                         'model' => $model,
                    ]);
                }
                else
                {   
                    UserAccessHelper::revokeAllUserRoles($username);
                    $status= User::STATUS_DELETED;
                    $user->status=$status;
                    $user->save();

                    //remove record from Model storing users for sending reminders.
                   // CentreReminderLinker::deleteAll(['remUsername'=>$username]);
                    ReminderManager::deleteUserFromReminderList($username);
                    return $this->redirect('view-users-to-delete');
                  
                }
            }
            

            return $this->render('delete_user',
                 [
                     'model' => $model,
                ]);
        }
    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
