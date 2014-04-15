<?php

/**
 * This is the base model class for table "{{images_cloud}}".
 *
 * The followings are the available columns in table '{{images_cloud}}':
 * @property string $id
 * @property string $image_id
 * @property string $cloud_image_id
 * @property string $cloudinary_public_id
 * @property string $cloudinary_cloud_name
 * @property string $cloudinary_version
 *
 * The followings are the available model relations:
 * @property Images $image
 *
 * @package application.models.base
 * @name BaseImagesCloud
 */
abstract class BaseImagesCloud extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{images_cloud}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image_id, cloud_image_id', 'required'),
			array('image_id, cloud_image_id, cloudinary_version', 'length', 'max'=>20),
			array('cloudinary_public_id, cloudinary_cloud_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, image_id, cloud_image_id, cloudinary_public_id, cloudinary_cloud_name, cloudinary_version', 'safe', 'on'=>'search'),
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
			'image' => array(self::BELONGS_TO, 'Images', 'image_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'image_id' => 'Image',
			'cloud_image_id' => 'Cloud Image',
			'cloudinary_public_id' => 'Cloudinary Public',
			'cloudinary_cloud_name' => 'Cloudinary Cloud Name',
			'cloudinary_version' => 'Cloudinary Version',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('image_id',$this->image_id,true);
		$criteria->compare('cloud_image_id',$this->cloud_image_id,true);
		$criteria->compare('cloudinary_public_id',$this->cloudinary_public_id,true);
		$criteria->compare('cloudinary_cloud_name',$this->cloudinary_cloud_name,true);
		$criteria->compare('cloudinary_version',$this->cloudinary_version,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}