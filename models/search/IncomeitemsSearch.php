<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Incomeitems;

/**
 * IncomeitemsSearch represents the model behind the search form about `app\models\Incomeitems`.
 */
class IncomeitemsSearch extends Incomeitems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_item', 'id_income', 'id_product'], 'integer'],
            [['qnt', 'price', 'price2'], 'number'],
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
        $query = Incomeitems::find();

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
            'id_item' => $this->id_item,
            'id_income' => $this->id_income,
            'id_product' => $this->id_product,
            'qnt' => $this->qnt,
            'price' => $this->price,
            'price2' => $this->price2,
        ]);

        return $dataProvider;
    }
}
