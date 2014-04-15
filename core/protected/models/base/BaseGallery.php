<?php

/**
 * This is the base model class for table "{{gallery}}".
 *
 * The followings are the available columns in table '{{gallery}}':
 * @property integer $id
 * @property string $versions_data
 * @property integer $name
 * @property integer $description
 *
 * The followings are the available model relations:
 * @property GalleryPhoto[] $galleryPhotos
 *
 * @package application.models.base
 * @name BaseGallery
 */
abstract class BaseGallery extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{gallery}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('versions_data', 'required'),
			array('name, description', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, versions_data, name, description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'galleryPhotos' => array(self::HAS_MANY, 'GalleryPhoto', 'gallery_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'versions_data' => 'Versions Data',
			'name' => 'Name',
			'description' => 'Description',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('versions_data',$this->versions_data,true);
		$criteria->compare('name',$this->name);
		$criteria->compare('description',$this->description);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}