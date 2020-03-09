<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form of `app\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    public $chieffullname;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'chief_id', 'sex'], 'integer'],
            [['name', 'surname', 'position', 'birthday', 'chieffullname'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Employee::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => array_merge(
                $dataProvider->getSort()->attributes,
                [
                    'chieffullname' =>
                    [
                        'asc' => ['employee2.name' => SORT_ASC, 'employee2.surname' => SORT_ASC],
                        'desc' => ['employee2.name' => SORT_DESC, 'employee2.surname' => SORT_DESC],
                    ]
                ]
            ),
        ]);

        if (! ($this->load($params) && $this->validate()))
        {
            $query->joinWith(['chief']);
            return $dataProvider;
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            self::tableName() . '.id' => $this->id,
            self::tableName() . '.chief_id' => $this->chief_id,
            self::tableName() . '.birthday' => $this->birthday,
            self::tableName() . '.sex' => $this->sex,
        ]);

        $query->andFilterWhere(['like', self::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', self::tableName() . '.surname', $this->surname])
            ->andFilterWhere(['like', self::tableName() . '.position', $this->position]);

        if (! empty($params['EmployeeSearch']['chieffullname']))
        {
            $query->joinWith(['chief' => function ($q) {
                $q->where('employee2.name LIKE "%' . $this->chieffullname . '%"' .
                    'OR employee2.surname LIKE "%' . $this->chieffullname . '%"');
            }]);
        }

        return $dataProvider;
    }
}
