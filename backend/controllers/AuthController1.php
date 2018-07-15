<?php

namespace backend\controllers;


class AuthController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionAuth_item()
	{
	    $model = new \common\models\AuthItem();

	    if ($model->load(\Yii::$app->request->post())) {
	        if ($model->validate()) {
	            // form inputs are valid, do something here
	            return;
	        }
	    }

	    return $this->render('auth_item', [
	        'model' => $model,
	    ]);
	}

}
