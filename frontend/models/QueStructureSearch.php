<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\QueStructure;

/**
 * QueStructureSearch represents the model behind the search form of `frontend\models\QueStructure`.
 */
class QueStructureSearch extends QueStructure
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id', 'section', 'group', 'groupId', 'groupMarks','isSecEvaluated', 'sectionSeq', 'created_at', 'updated_at'], 'safe'],
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
        $query = QueStructure::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>15,
            ],
            'sort' => [
                'defaultOrder' => [
                'section' => SORT_ASC,
                'group' => SORT_ASC, 
                
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
            ->andFilterWhere(['like', 'section', $this->section])
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'groupId', $this->groupId])
            ->andFilterWhere(['like', 'groupMarks', $this->groupMarks])
            ->andFilterWhere(['like', 'isSecEvaluated', $this->isSecEvaluated])
            ->andFilterWhere(['like', 'sectionSeq', $this->sectionSeq])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
