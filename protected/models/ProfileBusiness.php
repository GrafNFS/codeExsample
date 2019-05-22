<?php

/**
 * This is the model class for table "tbl_profile_business".
 *
 * The followings are the available columns in table 'tbl_profile_business':
 * @property integer $id_user
 * @property string $display_name
 * @property integer $name_hide
 * @property string $photo
 * @property integer $id_industry
 * @property string $revenue
 * @property string $opening_year
 * @property integer $opening_year_hide
 * @property string $location
 * @property integer $location_hide
 * @property string $discription
 */
class ProfileBusiness extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProfileBusiness the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_profile_business';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_user', 'required'),
            array('id_user, name_hide, id_industry, opening_year_hide, location_hide', 'numerical', 'integerOnly' => true),
            array('display_name, photo, location', 'length', 'max' => 100),
            array('revenue', 'length', 'max' => 50),
            array('opening_year', 'length', 'max' => 20),
            array('discription', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_user, display_name, name_hide, photo, id_industry, revenue, opening_year, opening_year_hide, location, location_hide, discription', 'safe', 'on' => 'search'),
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
            'display_name' => 'Display Name',
            'name_hide' => 'Name Hide',
            'photo' => 'Photo',
            'id_industry' => 'Id Sub Industry',
            'revenue' => 'Revenue',
            'opening_year' => 'Opening Year',
            'opening_year_hide' => 'Opening Year Hide',
            'location' => 'Location',
            'location_hide' => 'Location Hide',
            'discription' => 'Discription',
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
        $criteria->compare('display_name', $this->display_name, true);
        $criteria->compare('name_hide', $this->name_hide);
        $criteria->compare('photo', $this->photo, true);
        $criteria->compare('id_industry', $this->id_sub_industry);
        $criteria->compare('revenue', $this->revenue, true);
        $criteria->compare('opening_year', $this->opening_year, true);
        $criteria->compare('opening_year_hide', $this->opening_year_hide);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('location_hide', $this->location_hide);
        $criteria->compare('discription', $this->discription, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
