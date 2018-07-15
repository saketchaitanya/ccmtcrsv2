<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Centres;

/**
 * CentresIndiaSearch represents the model behind the search form of `common\models\Centres`.
 */
class CentresIndiaSearch extends Centres
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id', 'name', 'desc', 'wpLocCode', 'code', 'phone', 'fax', 'email', 'mobile', 'centreAcharyas', 'regNo', 'regDate', 'president', 'treasurer', 'secretary'], 'safe'],
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
        $query = Centres::find();

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
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'wpLocCode', $this->wpLocCode])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'centreAcharyas', $this->centreAcharyas])
            ->andFilterWhere(['like', 'regNo', $this->regNo])
            ->andFilterWhere(['like', 'regDate', $this->regDate])
            ->andFilterWhere(['like', 'president', $this->president])
            ->andFilterWhere(['like', 'treasurer', $this->treasurer])
            ->andFilterWhere(['like', 'secretary', $this->secretary]);

        return $dataProvider;
    }
}
