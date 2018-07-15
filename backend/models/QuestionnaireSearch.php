<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Questionnaire;

/**
 * QuestionnaireSearch represents the model behind the search form of `frontend\models\Questionnaire`.
 */
class QuestionnaireSearch extends Questionnaire
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id', 'queID', 'forYear', 'centreName', 'centreID', 'userFullName', 'Acharya', 'trackSeqNo', 'status', 'queSeqArray'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        if(Yii::$app->user->isGuest):
         throw new \yii\web\ServerErrorHttpException('Username not found');
        endif;

        $username= \Yii::$app->user->identity->username;
        $query = Questionnaire::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>10,
            ],
            'sort' => [
                'defaultOrder' => [
                'forYear' => SORT_DESC,
                'centreName' => SORT_ASC, 
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'queID', $this->queID])
            ->andFilterWhere(['like', 'forYear', $this->forYear])
            ->andFilterWhere(['like', 'centreName', $this->centreName])
            ->andFilterWhere(['like', 'centreID', $this->centreID])
            ->andFilterWhere(['like', 'userFullName', $this->userFullName])
            ->andFilterWhere(['like', 'Acharya', $this->Acharya])
            ->andFilterWhere(['like', 'lastQuestion', $this->lastQuestion])
            ->andFilterWhere(['status'=> Questionnaire::STATUS_NEW])
            ->andFilterWhere(['like', 'queSeqArray', $this->queSeqArray]);
        return $dataProvider;
    }
}
