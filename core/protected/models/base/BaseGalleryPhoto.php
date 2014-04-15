<?php

/**
 * This is the base model class for table "{{gallery_photo}}".
 *
 * The followings are the available columns in table '{{gallery_photo}}':
 * @property integer $id
 * @property integer $gallery_id
 * @property integer $rank
 * @property string $name
 * @property string $description
 * @property string $file_name
 * @property string $thumb_ext
 *
 * The followings are the available model relations:
 * @property Gallery $gallery
 *
 * @package application.models.base
 * @name BaseGalleryPhoto
 */
abstract class BaseGalleryPhoto extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{gallery_photo}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gallery_id', 'required'),
			array('gallery_id, rank', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>512),
			array('file_name', 'length', 'max'=>128),
			array('thumb_ext', 'length', 'max'=>6),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, gallery_id, rank, name, description, file_name, thumb_ext', 'safe', 'on'=>'search'),
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
			'gallery' => array(self::BELONGS_TO, 'Gallery', 'gallery_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'gallery_id' => 'Gallery',
			'rank' => 'Rank',
			'name' => 'Name',
			'description' => 'Description',
			'file_name' => 'File Name',
			'thumb_ext' => 'Thumb Ext',
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
		$criteria->compare('gallery_id',$this->gallery_id);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('thumb_ext',$this->thumb_ext,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}