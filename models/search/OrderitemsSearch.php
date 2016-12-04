<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orderitems;

/**
 * OrderitemsSearch represents the model behind the search form about `app\models\Orderitems`.
 */
class OrderitemsSearch extends Orderitems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_item', 'id_order', 'id_product'], 'integer'],
            [['qnt'], 'number'],
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
        $query = Orderitems::find();

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
            'id_order' => $this->id_order,
            'id_product' => $this->id_product,
            'qnt' => $this->qnt,
        ]);

        return $dataProvider;
    }
}
