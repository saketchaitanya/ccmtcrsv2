<?php
namespace common\components\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 *  By default, UnameBlameableBehavior will fill the `created_by` and `updated_by` attributes with the current user ID
 *  and `created_uname` and `updated_uname` when the associated AR object is being inserted; 
 *  it will fill the `updated_by` attribute
 *  with the current user ID & username when the AR object is being updated.
 *
 * @author Luciano Baraglia <luciano.baraglia@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @author Saket Chaitanya (few changes)
 * @since 2.0
 *
 */

class UnameBlameableBehavior extends AttributeBehavior
{
	  /**
     * @var string the attribute that will receive current user username value
     * Set this property to false if you do not want to record the creator username.
     */
    public $createdByAttribute = 'created_uname';
    /**username
     * @var string the attribute that will receive current user username value
     * Set this property to false if you do not want to record the updater username.
     */
    public $updatedByAttribute = 'updated_uname';
    /**
     * {@inheritdoc}
     *
     * In case, when the property is `null`, the value of `Yii::$app->user->id` will be used as the value.
     */
    public $value;
    /**
     * @var mixed Default value for cases when the user is guest
     * @since 2.0.14
     */
    public $defaultValue;
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdByAttribute, $this->updatedByAttribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedByAttribute,
            ];
        }
    }
    /**
     * {@inheritdoc}
     *
     * In case, when the [[value]] property is `null`, the value of [[defaultValue]] will be used as the value.
     */
    protected function getValue($event)
    {
        if ($this->value === null && Yii::$app->has('user')) {
            $userName= Yii::$app->get('user')->identity->username;
            $userId = Yii::$app->get('user')->Id;
            if ($userId === null) {
                return $this->getDefaultValue($event);
            }
            return $userName;
        }
        return parent::getValue($event);
    }
    /**
     * Get default value
     * @param \yii\base\Event $event
     * @return array|mixed
     * @since 2.0.14
     */
    protected function getDefaultValue($event)
    {
        if ($this->defaultValue instanceof \Closure || (is_array($this->defaultValue) && is_callable($this->defaultValue))) {
            return call_user_func($this->defaultValue, $event);
        }
        return $this->defaultValue;
    }

}



