<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;

/**
 * ProductsSearch represents the model behind the search form about `app\models\Products`.
 */
class ProductsSearch extends Products
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_product', 'id_category', 'id_unit', 'status'], 'integer'],
            [['name', 'code', 'barcode', 'image', 'imageThump', 'color', 'size', 'desscription'], 'safe'],
            [['price', 'price1', 'price2', 'qnt'], 'number'],
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
        $query = Products::find();

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
            'id_product' => $this->id_product,
            'price' => $this->price,
            'price1' => $this->price1,
            'price2' => $this->price2,
            'id_category' => $this->id_category,
            'id_unit' => $this->id_unit,
            'status' => $this->status,
            'offer' => $this->offer,
            'color' => $this->color,
            'size' => $this->size,
            'qnt' => $this->qnt,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'imageThump', $this->imageThump]);

        return $dataProvider;
    }
}
