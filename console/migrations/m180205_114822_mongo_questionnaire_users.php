<?php

class m180205_114822_mongo_questionnaire_users extends \yii\mongodb\Migration
{
    public function up()
    {
    	$auth = Yii::$app->authManager;
        $createQuestionnaire=$auth->createPermission('createQuestionnaire');
        $createQuestionnaire->description = 'Create Questionnaire';
        $auth->add($createQuestionnaire);
        
        $evalQuestionnaire=$auth->createPermission('evaluateQuestionniare');
        $evalQuestionnaire->description = 'Evaluate Questionnaire';
        $auth->add($evalQuestionnaire);

        $unappuser = $auth->createRole('unappuser'); 
        $auth->add($unappuser);
        $appuser   = $auth->createRole('appuser');
        $auth->add($appuser);

        $evaluator= $auth->getRole('evaluator');
        $auth->addChild($evaluator,$evalQuestionnaire);
        $auth->addChild($appuser, $createQuestionnaire);
    }

    public function down()
    {
        echo "m180205_114822_mongo_questionnaire_users cannot be reverted.\n";
         /*$auth = Yii::$app->authManager;
        $auth->removeAll();*/
        return false;
    }
}
