<?php

/**
 * This is the base model class for table "{{product_text}}".
 *
 * The followings are the available columns in table '{{product_text}}':
 * @property string $id
 * @property string $product_id
 * @property string $lang
 * @property string $title
 * @property string $description_short
 * @property string $description_long
 *
 * @package application.models.base
 * @name BaseProductText
 */
abstract class BaseProductText extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{product_text}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id', 'length', 'max'=>20),
			array('lang', 'length', 'max'=>6),
			array('title', 'length', 'max'=>255),
			array('description_short, description_long', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, lang, title, description_short, description_long', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'lang' => 'Lang',
			'title' => 'Title',
			'description_short' => 'Description Short',
			'description_long' => 'Description Long',
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
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description_short',$this->description_short,true);
		$criteria->compare('description_long',$this->description_long,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}