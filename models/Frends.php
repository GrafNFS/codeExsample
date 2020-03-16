<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_frends".
 *
 * @property int $id_user
 * @property int $id_company
 * @property int $statys
 */
class Frends extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_frends';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_company'], 'required'],
            [['id_user', 'id_company', 'statys'], 'integer'],
            [['id_user', 'id_company'], 'unique', 'targetAttribute' => ['id_user', 'id_company']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'id_company' => 'Id Company',
            'statys' => 'Statys',
        ];
    }
}
