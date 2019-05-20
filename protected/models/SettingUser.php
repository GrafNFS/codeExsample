<?php

/**
 * This is the model class for table "tbl_setting_user".
 *
 * The followings are the available columns in table 'tbl_setting_user':
 * @property integer $id_user
 * @property string $default_industry
 * @property string $default_revenue_slider
 * @property string $connection_cost_slider
 * @property string $distance_slider
 */
class SettingUser extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SettingUser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_setting_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_user', 'required'),
            array('id_user', 'numerical', 'integerOnly' => true),
            array('default_industry', 'length', 'max' => 50),
            array('default_revenue_slider, connection_cost_slider, distance_slider', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_user, default_industry, default_revenue_slider, connection_cost_slider, distance_slider', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id_user' => 'Id User',
            'default_industry' => 'Default Industry',
            'default_revenue_slider' => 'Default Revenue Slider',
            'connection_cost_slider' => 'Connection Cost Slider',
            'distance_slider' => 'Distance Slider',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id_user', $this->id_user);
        $criteria->compare('default_industry', $this->default_industry, true);
        $criteria->compare('default_revenue_slider', $this->default_revenue_slider, true);
        $criteria->compare('connection_cost_slider', $this->connection_cost_slider, true);
        $criteria->compare('distance_slider', $this->distance_slider, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
