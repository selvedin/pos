<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Suppliers;

/**
 * SuppliersSearch represents the model behind the search form about `app\models\Suppliers`.
 */
class SuppliersSearch extends Suppliers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_supplier', 'contactPerson', 'status'], 'integer'],
            [['name', 'address', 'phone', 'fax', 'web', 'email', 'bankAccounts', 'description'], 'safe'],
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
        $query = Suppliers::find();

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
            'id_supplier' => $this->id_supplier,
            'contactPerson' => $this->contactPerson,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'web', $this->web])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'bankAccounts', $this->bankAccounts])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
