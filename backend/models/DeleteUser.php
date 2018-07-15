<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class DeleteUser extends Model
{
    // this 'virtual attribute' defines a form field
    public $username;
    public $roles;
    public $permissions;

    // validation rules
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['roles','permissions', 'safe']
        ];
    }


}