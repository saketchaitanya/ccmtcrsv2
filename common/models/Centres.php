<?php

namespace common\models;

use Yii;

/**
 * This is the model class for collection "centresIndia".
 *
 * @property \MongoId|string $_id
 * @property mixed $name
 * @property mixed $desc
 * @property mixed $wpLocCode
 * @property mixed $code
 * @property mixed $fileNo
 * @property mixed $phone
 * @property mixed $fax
 * @property mixed $email
 * @property mixed $mobile
 * @property mixed $centreAcharyas
 * @property mixed $regNo
 * @property mixed $regDate
 * @property mixed $president
 * @property mixed $treasurer
 * @property mixed $secretary
 * @property mixed $country
 */
class Centres extends \yii\mongodb\ActiveRecord
{
    const STATUS_ACTIVE ='active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'centres';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'desc',
            'wpLocCode',
            'region',
            'stateCode',
            'code',
            'CMCNo',
            'fileNo',
            'phone',
            'fax',
            'email',
            'mobile',
            'centreAcharyas',
            'isCentreRegistered',
            'centreOwnsPlace',
            'regNo',
            'regDate',
            'president',
            'treasurer',
            'secretary',
            'country',
            'status'
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                    [
                        ['_id', 'name', 'desc','region','code','CMCNo', 'phone','fax','email','url','mobile','centreAcharyas','isCentreRegistered','centreOwnsPlace', 'regNo', 'regDate','president','treasurer','secretary'], 
                        'safe'
                    ],
                    [['region','stateCode', 'centreOwnsPlace','isCentreRegistered'],'required'],
                    ['country','default', 'value'=>'India'],
                    ['wpLocCode', 'unique'],
                    ['status', 'in' ,'range'=>[self::STATUS_ACTIVE,self::STATUS_INACTIVE] ],
                    ['status','default', 'value'=>self::STATUS_ACTIVE],

            ];
       }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return 
        [
            '_id' => 'ID',
            'name' => 'Name',
            'desc'=> 'Description',
            'code' => 'Code', 
            'CMCNo'=>'CMC No',
            'fileNo' => 'File No',
            'region'=> 'Region',
            'stateCode' => 'State Code',           
            'centreAcharyas' => 'Centre Acharyas',
            'wpLocCode' => 'Global Centre Code',
            'phone'=> 'Phone',
            'fax'=> 'Fax',
            'email'=> 'email',
            'mobile'=> 'Mobile',
            'regNo' => 'Registration No',
            'regDate' => 'Registration Date',
            'isCentreRegistered'=>'Is Centre Registered',
            'centreOwnsPlace'=>'Is the place owned by centre?',
            'president'=>'President',
            'treasurer' => 'Treasurer',
            'secretary' => 'Secretary',
            'status' => 'Status'
        ];
    }

    /* WpLoc Code will be used as a centre ID instead of object Id 
     * and will be returned as centre Id. The reason for using this
     * is that if the collection gets corrupted, and the data is 
     * ported, the new object ids may differ from existing records
     * salvaged or in backup and then the whole information may get 
     * affected. Since wpLocCode is actually from wordpress central
     * database, the continuity can be maintained simply by importing
     * the new records.
     */
    public static function getCentreId($centrename)
    {
        $model= static::findOne(['name' => $centrename]);
        if($model):
        return $model->wpLocCode;
        else:
            return false;
        endif;
    }

    /* WpLoc Code will be used as a centre ID instead of object Id 
     * and will be used for returning the centre Name The reason for using this
     * is that if the collection gets corrupted, and the data is 
     * ported, the new object ids may differ from existing records
     * salvaged or in backup and then the whole information may get 
     * affected. Since wpLocCode is actually from wordpress central
     * database, the continuity can be maintained simply by importing
     * the new records.
     */
    public static function getCentreName($id)
    {
        $model= static::findOne(['wpLocCode' => $id]);
        if($model):
        return $model->name;
        else:
            return false;
        endif;
    }
}
