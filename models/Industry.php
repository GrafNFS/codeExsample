<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_industry".
 *
 * @property int $id_industry
 * @property string $name
 */
class Industry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_industry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_industry' => 'Id Industry',
            'name' => 'Name',
        ];
    }
}
