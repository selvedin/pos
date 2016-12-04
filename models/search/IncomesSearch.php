<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Incomes;

/**
 * IncomesSearch represents the model behind the search form about `app\models\Incomes`.
 */
class IncomesSearch extends Incomes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_income', 'id_stock', 'id_user', 'id_supplier'], 'integer'],
            [['num_invoice', 'create_date', 'description'], 'safe'],
            [['invoice_amount', 'wat'], 'number'],
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
        $query = Incomes::find();

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
            'id_income' => $this->id_income,
            'invoice_amount' => $this->invoice_amount,
            'wat' => $this->wat,
            'id_stock' => $this->id_stock,
            'id_user' => $this->id_user,
            'id_supplier' => $this->id_supplier,
        ]);

        $query->andFilterWhere(['like', 'num_invoice', $this->num_invoice])
            ->andFilterWhere(['like', 'create_date', $this->create_date])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
