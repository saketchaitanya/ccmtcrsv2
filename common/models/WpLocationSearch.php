<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WpLocation;

/**
 * WpLocationSearch represents the model behind the search form of `common\models\WpLocation`.
 */
class WpLocationSearch extends WpLocation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'chinmaya_id'], 'integer'],
            [['location_type', 'name', 'description', 'url', 'address1', 'address2', 'address3', 'city', 'state', 'country', 'zip', 'continent', 'deity', 'consecrated', 'activities', 'added_on', 'updated_on', 'contact', 'phone', 'email', 'fax', 'acharya', 'president', 'secretary', 'treasurer', 'location_incharge', 'image', 'trust', 'location', 'centre_type'], 'safe'],
            [['latitude', 'longitude'], 'number'],
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
        $query = WpLocation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>15,
            ],
            'sort' => [
            'defaultOrder' => [
                'location_type' => SORT_ASC,
                'name' => SORT_ASC, 
        ],
    ],
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
            'chinmaya_id' => $this->chinmaya_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'added_on' => $this->added_on,
            'updated_on' => $this->updated_on,
        ]);

        $query->andFilterWhere(['like', 'location_type', $this->location_type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'address1', $this->address1])
            ->andFilterWhere(['like', 'address2', $this->address2])
            ->andFilterWhere(['like', 'address3', $this->address3])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'zip', $this->zip])
            ->andFilterWhere(['like', 'continent', $this->continent])
            ->andFilterWhere(['like', 'deity', $this->deity])
            ->andFilterWhere(['like', 'consecrated', $this->consecrated])
            ->andFilterWhere(['like', 'activities', $this->activities])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'acharya', $this->acharya])
            ->andFilterWhere(['like', 'president', $this->president])
            ->andFilterWhere(['like', 'secretary', $this->secretary])
            ->andFilterWhere(['like', 'treasurer', $this->treasurer])
            ->andFilterWhere(['like', 'location_incharge', $this->location_incharge])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'trust', $this->trust])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'centre_type', $this->centre_type]);

        return $dataProvider;
    }
}
