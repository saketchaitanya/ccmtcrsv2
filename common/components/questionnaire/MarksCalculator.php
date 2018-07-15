<?php

namespace common\components\questionnaire;

use frontend\models\Questionnaire;
use frontend\models\QueTracker;
use frontend\models\GrpGeneral;



class MarksCalculator {

	public static function getTotalMarks($queId)
	{
		$tracker = QueTracker::findModelByQue($queId);
		$que = Questionnaire::findModel($queId);
		$array  = $tracker->trackerArray;
		//remove the first and last elements
		array_shift($array);
		array_pop($array);

		$marks = 0;
		for ($i=0; $i<sizeof($array); $i++)
		{
			if ($array[$i]['isGroupEval']==='yes')
			{
				if (isset($array[$i]['evalMarks']))
					$marks = $marks+(int)$array[$i]['evalMarks'];
			}
		}
		$gen = GrpGeneral::findModelByQue($queId);
		if (isset($gen->punctuality))
		{
			if($gen->punctuality=='yes')
				$marks = $marks + 5;
			

		}
		
		$que->totalMarks = $marks;
		$que->save();
		return $marks;
	}
}