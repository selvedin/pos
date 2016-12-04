<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Settings;

/**
 * SettingsSearch represents the model behind the search form about `app\models\Settings`.
 */
class SettingsSearch extends Settings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'i1', 'i2', 'i3'], 'integer'],
            [['type', 'n', 'n_ar', 'a1_ar', 'a1', 'a2', 'a3', 'a4'], 'safe'],
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
        $query = Settings::find();

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
        $query->andFilterWhere([
            'id' => $this->id,
            'i1' => $this->i1,
            'i2' => $this->i2,
            'i3' => $this->i3,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'n', $this->n])
            ->andFilterWhere(['like', 'n_ar', $this->n_ar])
            ->andFilterWhere(['like', 'a1_ar', $this->a1_ar])
            ->andFilterWhere(['like', 'a1', $this->a1])
            ->andFilterWhere(['like', 'a2', $this->a2])
            ->andFilterWhere(['like', 'a3', $this->a3])
            ->andFilterWhere(['like', 'a4', $this->a4]);

        return $dataProvider;
    }
}
