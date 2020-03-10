<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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
            'chieffullname' => 'Chief',
        ];
    }

    public function getChief()
    {
        return $this->hasOne(self::className(), ['id' => 'chief_id'])->alias('employee2');
    }

    public function getChiefFullName()
    {
        $chief = $this->chief;
        return ! is_null($chief) ? $this->chief->name . ' ' . $this->chief->surname : '';
    }

    public function getSexName()
    {
        return $this->sex === 1 ? 'Man' : 'Woman';
    }

    public function getBirthdayParsed()
    {
        return $this->birthday ? date('d.m.Y', strtotime($this->birthday)) : '';
    }

    public function getInferiors()
    {
        return $this->find()->where(['chief_id' => $this->id])->asArray()->all();
    }

    public function getInferiorsString()
    {
        $inferiors = $this->inferiors;
        $inferiors = ArrayHelper::getColumn($inferiors, function($inferior) {
            return $inferior['name'] . ' ' . $inferior['surname'];
        });
        return implode(', ', $inferiors);
    }
}
