<?php
namespace common\components\reports;

use yii\mongodb\Query;
use frontend\models\Questionnaire;
use yii\helpers\ArrayHelper;
use frontend\models\QueSummary;
use common\models\CurrentYear;
use common\models\Centres;
use frontend\models\GrpLiterary;
use frontend\models\GrpVidyalaya;
use common\components\CentresIndiaHelper;
use common\components\reports\ReportQueryManager;


class AllocationManager {


	public static function updateCurrentAllocations(){

		$ques = ReportQueryManager::validQuesData();
		/*foreach {

		}*/

			/*if($model->save()):
				$res = true;
			else:
				$res = false;
				return $res;
			endif;*/
		return false;
	}

}