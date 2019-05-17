<?php

/**
 * This is the model class for table "tbl_schedule".
 *
 * The followings are the available columns in table 'tbl_schedule':
 * @property integer $id_schedule
 * @property string $date_schedule
 * @property string $time_schedule
 * @property integer $policies
 * @property integer $id_user
 * @property integer $id_business
 */
class Schedule extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Schedule the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_schedule';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_user', 'required'),
            array('policies, id_user, id_business', 'numerical', 'integerOnly' => true),
            array('date_schedule, time_schedule', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_schedule, date_schedule, time_schedule, policies, id_user, id_business', 'safe', 'on' => 'search'),
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
            'id_schedule' => 'Id Schedule',
            'date_schedule' => 'Date Schedule',
            'time_schedule' => 'Time Schedule',
            'policies' => 'Policies',
            'id_user' => 'Id User',
            'id_business' => 'Id Business',
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

        $criteria->compare('id_schedule', $this->id_schedule);
        $criteria->compare('date_schedule', $this->date_schedule, true);
        $criteria->compare('time_schedule', $this->time_schedule, true);
        $criteria->compare('policies', $this->policies);
        $criteria->compare('id_user', $this->id_user);
        $criteria->compare('id_business', $this->id_business);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
