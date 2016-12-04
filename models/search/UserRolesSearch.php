<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserRoles;

/**
 * UserRolesSearch represents the model behind the search form about `app\models\UserRoles`.
 */
class UserRolesSearch extends UserRoles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_role', 'id_user_role'], 'integer'],
            [['update_date'], 'safe'],
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
        $query = UserRoles::find();

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
            'id_user' => $this->id_user,
            'id_role' => $this->id_role,
            'id_user_role' => $this->id_user_role,
        ]);

        $query->andFilterWhere(['like', 'update_date', $this->update_date]);

        return $dataProvider;
    }
}
