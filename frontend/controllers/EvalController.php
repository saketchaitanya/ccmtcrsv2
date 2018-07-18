<?php

namespace frontend\controllers;

use Yii;

use frontend\models\QueSections;
use frontend\models\QueSectionsSearch;
use frontend\models\QueStructure;
use common\models\Centres;
use frontend\models\CentreReminderLinker;
use common\models\RegionMaster;
use common\models\UserProfile;
use common\models\WpAcharya;
use common\models\AllocationMaster;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\mongodb\Query;



class EvalController extends \yii\web\Controller
{

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

    public function actionCentreInfoListing()
    {
        $centres = Centres::find()->asArray()->all();
        $linker = CentreReminderLinker::find()->asArray()->all();
        $linkerMap= \yii\helpers\ArrayHelper::map($linker,'centreId','remUserArray');
        $usersArr = array();
        foreach($linkerMap as $key=>$value)
        {
            $users ='';
                 for($i=0;$i<sizeof($value);$i++):
                    $users=$users.$value[$i]['username'].'['.$value[$i]['email'].']';
                    if($i<(sizeof($value)-1))
                        $users = $users.', ';
                 endfor;
             $usersArr[$key]=$users;
        }
        $model=['centres'=>$centres, 'users'=>$usersArr];
        return $this->render('centre-info-listing',['model'=>$model]);
    }
  
   /**
     * Displays a single Centres model.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewCentre($code)
    {
       $model=  Centres::findOne(['wpLocCode'=>(int)$code]);

        return $this->renderAjax('view-centres', [
            'model' => $model
        ]);
    }


    public function actionViewAllocationMaster()
    {

        $model = AllocationMaster::findOne(['status'=>AllocationMaster::STATUS_ACTIVE]);
        if($model)
        {
            return $this->render('view-allocation-master',['model'=>$model]);
        }
        else
        {
            throw new \yii\web\ServerErrorHttpException('No active Allocation Information found');
        }

    }

    public function actionPdfAllocationMaster()
    {

        $model = AllocationMaster::findOne(['status'=>AllocationMaster::STATUS_ACTIVE]);
        if($model)
        {
            $content = $this->renderPartial('pdf-allocation-master',['model'=>$model]);
              return $pdf = Yii::$app->pdf->generatePdf($content);
        }
        else
        {
            throw new \yii\web\ServerErrorHttpException('No active Allocation Information found');
        }

    }
    
}
