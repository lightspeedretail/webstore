<?php

/**
 * This is the base model class for table "{{images}}".
 *
 * The followings are the available columns in table '{{images}}':
 * @property string $id
 * @property string $image_path
 * @property integer $width
 * @property integer $height
 * @property string $parent
 * @property integer $index
 * @property string $product_id
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property ImagesCloud[] $imagesClouds
 * @property Product[] $products
 *
 * @package application.models.base
 * @name BaseImages
 */
abstract class BaseImages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{images}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created, modified', 'required'),
			array('width, height, index', 'numerical', 'integerOnly'=>true),
			array('image_path', 'length', 'max'=>255),
			array('parent, product_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, image_path, width, height, parent, index, product_id, created, modified', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'imagesClouds' => array(self::HAS_MANY, 'ImagesCloud', 'image_id'),
			'products' => array(self::HAS_MANY, 'Product', 'image_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'image_path' => 'Image Path',
			'width' => 'Width',
			'height' => 'Height',
			'parent' => 'Parent',
			'index' => 'Index',
			'product_id' => 'Product',
			'created' => 'Created',
			'modified' => 'Modified',
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
		$criteria->compare('image_path',$this->image_path,true);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);
		$criteria->compare('parent',$this->parent,true);
		$criteria->compare('index',$this->index);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}