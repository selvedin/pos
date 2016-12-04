<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rates;

/**
 * RatesSearch represents the model behind the search form about `app\models\Rates`.
 */
class RatesSearch extends Rates
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_rate', 'id_product', 'id_customer'], 'integer'],
            [['created_date'], 'safe'],
            [['rate'], 'number'],
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
        $query = Rates::find();

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
            'id_rate' => $this->id_rate,
            'id_product' => $this->id_product,
            'id_customer' => $this->id_customer,
            'rate' => $this->rate,
        ]);

        $query->andFilterWhere(['like', 'created_date', $this->created_date]);

        return $dataProvider;
    }
}
