<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\QueGroups;

/**
 * QueGroupsSearch represents the model behind the search form of `frontend\models\QueGroups`.
 */
class QueGroupsSearch extends QueGroups
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id', 'groupSeqNo', 'parentSection', 'description', 'maxMarks', 'created_at', 'updated_at', 'controllerName','modelName','accessPath', 'status', 'qGroupId'], 'safe'],
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
        $query = QueGroups::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'groupSeqNo', $this->groupSeqNo])
            ->andFilterWhere(['like', 'parentSection', $this->parentSection])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'maxMarks', $this->maxMarks])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'modelName', $this->modelName])
            ->andFilterWhere(['like', 'controllerName', $this->controllerName])
            ->andFilterWhere(['like', 'accessPath', $this->accessPath])
            ->andFilterWhere(['not', 'status', 'deleted'])
            ->andFilterWhere(['like', 'qGroupId', $this->qGroupId]);

        return $dataProvider;
    }
}
