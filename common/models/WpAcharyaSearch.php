<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WpAcharya;

/**
 * WpAcharyaSearch represents the model behind the search form of `common\models\WpAcharya`.
 */
class WpAcharyaSearch extends WpAcharya
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'zip'], 'integer'],
            [['profile_id', 'salutation', 'aname', 'last_name', 'dob', 'centre', 'address1', 'address2', 'address3', 'pincode', 'country', 'state', 'city', 'continent', 'phone', 'email', 'image', 'admin_note', 'biodata', 'area_of_intrest', 'joined_date', 'br_diksha_date', 'trained_under_name', 'itinerary_url', 'chinmaya_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = WpAcharya::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>15,
            ],
            'sort' => [
                'defaultOrder' => [
                'aname' => SORT_ASC,
                'last_name' => SORT_ASC, 
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
        $query->andFilterWhere([
            'id' => $this->id,
            'dob' => $this->dob,
            'zip' => $this->zip,
            'joined_date' => $this->joined_date,
            'br_diksha_date' => $this->br_diksha_date,
        ]);

        $query->andFilterWhere(['like', 'profile_id', $this->profile_id])
            ->andFilterWhere(['like', 'salutation', $this->salutation])
            ->andFilterWhere(['like', 'aname', $this->aname])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'centre', $this->centre])
            ->andFilterWhere(['like', 'address1', $this->address1])
            ->andFilterWhere(['like', 'address2', $this->address2])
            ->andFilterWhere(['like', 'address3', $this->address3])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'continent', $this->continent])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'admin_note', $this->admin_note])
            ->andFilterWhere(['like', 'biodata', $this->biodata])
            ->andFilterWhere(['like', 'area_of_intrest', $this->area_of_intrest])
            ->andFilterWhere(['like', 'trained_under_name', $this->trained_under_name])
            ->andFilterWhere(['like', 'itinerary_url', $this->itinerary_url])
            ->andFilterWhere(['like', 'chinmaya_id', $this->chinmaya_id]);

        return $dataProvider;
    }
}
