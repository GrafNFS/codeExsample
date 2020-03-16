<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_state".
 *
 * @property int $IdState
 * @property string $State
 */
class State extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['State'], 'required'],
            [['State'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdState' => 'Id State',
            'State' => 'State',
        ];
    }
}
