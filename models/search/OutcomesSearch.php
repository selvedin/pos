<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Outcomes;

/**
 * OutcomesSearch represents the model behind the search form about `app\models\Outcomes`.
 */
class OutcomesSearch extends Outcomes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_outcome', 'id_user', 'executed', 'id_order', 'id_stock'], 'integer'],
            [['invoice_num', 'created_date'], 'safe'],
            [['noWatAmount', 'includedWatAmount'], 'number'],
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
        $query = Outcomes::find();

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
            'id_outcome' => $this->id_outcome,
            'id_user' => $this->id_user,
            'executed' => $this->executed,
            'noWatAmount' => $this->noWatAmount,
            'includedWatAmount' => $this->includedWatAmount,
            'id_order' => $this->id_order,
            'id_stock' => $this->id_stock,
        ]);

        $query->andFilterWhere(['like', 'invoice_num', $this->invoice_num])
            ->andFilterWhere(['like', 'created_date', $this->created_date]);

        return $dataProvider;
    }
}
