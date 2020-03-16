<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_city".
 *
 * @property int $IdCity
 * @property string $City
 * @property int $IdState
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['City', 'IdState'], 'required'],
            [['IdState'], 'integer'],
            [['City'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdCity' => 'Id City',
            'City' => 'City',
            'IdState' => 'Id State',
        ];
    }
}
