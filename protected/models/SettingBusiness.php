<?php

/**
 * This is the model class for table "tbl_setting_business".
 *
 * The followings are the available columns in table 'tbl_setting_business':
 * @property integer $id_user
 * @property string $profile_complite
 * @property string $distance_slider
 * @property string $cost_of_connection
 * @property string $receipt_payment
 */
class SettingBusiness extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SettingBusiness the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_setting_business';
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
            array('profile_complite, distance_slider, cost_of_connection, receipt_payment', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_user, profile_complite, distance_slider, cost_of_connection, receipt_payment', 'safe', 'on' => 'search'),
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
            'profile_complite' => 'Profile Complite',
            'distance_slider' => 'Distance Slider',
            'cost_of_connection' => 'Cost Of Connection',
            'receipt_payment' => 'Receipt Payment',
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
        $criteria->compare('profile_complite', $this->profile_complite, true);
        $criteria->compare('distance_slider', $this->distance_slider, true);
        $criteria->compare('cost_of_connection', $this->cost_of_connection, true);
        $criteria->compare('receipt_payment', $this->receipt_payment, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
