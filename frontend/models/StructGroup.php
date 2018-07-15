<?php

namespace frontend\models

use yii\base\Model;

class StructGroup extends Model
{
	
	/**
	 * @var object groupId
	 */

	 public $groupId;

	 /**
	 * @var integer groupSeqNo
	 */

	 public $groupSeqNo;

	 /**
	 * @var boolean include
	 */	

	 public $include;

	 /**
	 * @var string groupDesc
	 */

	 public $groupDesc;

	 /**
	 * @var number maxMarks
	 */

	 public $maxMarks;

	 /**
	 * @var string state
	 */

	 public $state;

	 public function rules {

	 	return [

	 			[['groupId','groupSeqNo', 'include', 'groupDesc', 'state'],'required'],

	 			[['groupSeqNo','maxMarks'],'integer', 'min'=>0, 'max'=>100],

	 			['selected ',  'boolean'],
	 			['selected', 'default'=>1],

	 			['state', 'in', 'range'=>['new','active', 'inactive', 'deleted'],'strict'=>true],

	 		]

 	}




}