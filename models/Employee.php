<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property int|null $chief_id
 * @property string $name
 * @property string $surname
 * @property string $position
 * @property string|null $birthday
 * @property int $sex
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chief_id', 'sex'], 'integer'],
            [['name', 'surname', 'position', 'sex'], 'required'],
            [['birthday'], 'safe'],
            [['name', 'surname', 'position'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chief_id' => 'Chief ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'position' => 'Position',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
        ];
    }
}
