<?php
namespace common\models;

/**
 * @property \MongoId|string $_id
 * @property-read string $id
 */
class ActiveRecord extends \yii\mongodb\ActiveRecord
{
    /**
     * Resurns model id as string
     * @return string
     */
    public function getId()
    {
        return (string) $this->getAttribute('_id');
    }
	
	public function attributes()
	{
		return ['_id', 'name', 'email', 'status'];
	}
}
