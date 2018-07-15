<?php

namespace frontend\controllers;

use Yii;

use frontend\models\QueSections;
use frontend\models\QueSectionsSearch;
use frontend\models\QueStructure;
use common\models\RegionMaster;
use common\models\UserProfile;
use common\models\WpAcharya;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;



class EvalController extends \yii\web\Controller
{

    /*public function behaviors()
    {
        return 
        [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['dashboard'],
                'duration' => 10,
                'variations' => 
                [
                    \Yii::$app->language,
                ],
            ]
        ];
    }*/
    
        

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDashboard($userId)
    {
        return $this->render('view-dashboard');

    }

    public function actionRegionheadlisting()
    {
        $regions = RegionMaster::findAll(['status'=>[RegionMaster::STATUS_ACTIVE,RegionMaster::STATUS_LOCKED]]);
        $model=array();
        foreach ($regions as $region):
            $name = $region->name;
            if(isset($region->regionalHead)||(strlen($region->regionalHead)==0)):
                $regionHead = $region->regionalHead;
                $profile = WpAcharya::findOne(['id'=>$region->regHeadCode]);
                if(isset($region->username)):
                    $userprofile = UserProfile::findOne(['username'=>$region->username]);
                    $id = $userprofile->_id;
                else:
                    $id='';
                endif;
                if(isset($profile->email)):
                    $email = $profile->email;
                else:
                    $email = '';
                endif;
                                
                if($region->username):
                    $username = $region->username;
                else:
                    $username = '';
                endif;
            else:
                $regionHead='';
                $email='';
                $username='';
            endif; 
            $model[]=[
                        'region'=>$name,
                        'regionalHead'=>$regionHead,
                        'email'=>$email,
                        'username'=>$username,
                        'userprofileId'=>(string)$id,
                    ];
        endforeach;

        return $this->render('region-head-listing',['model'=>$model]);
    }

  
   

}
