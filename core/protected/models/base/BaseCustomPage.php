<?php

/**
 * This is the base model class for table "{{custom_page}}".
 *
 * The followings are the available columns in table '{{custom_page}}':
 * @property string $id
 * @property string $page_key
 * @property string $title
 * @property string $page
 * @property string $request_url
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $modified
 * @property string $created
 * @property string $product_tag
 * @property integer $tab_position
 *
 * The followings are the available model relations:
 * @property Category[] $categories
 *
 * @package application.models.base
 * @name BaseCustomPage
 */
abstract class BaseCustomPage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{custom_page}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, modified', 'required'),
			array('tab_position', 'numerical', 'integerOnly'=>true),
			array('page_key', 'length', 'max'=>32),
			array('title', 'length', 'max'=>64),
			array('request_url, meta_keywords, meta_description, product_tag', 'length', 'max'=>255),
			array('page, created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, page_key, title, page, request_url, meta_keywords, meta_description, modified, created, product_tag, tab_position', 'safe', 'on'=>'search'),
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
			'categories' => array(self::HAS_MANY, 'Category', 'custom_page'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'page_key' => 'Page Key',
			'title' => 'Title',
			'page' => 'Page',
			'request_url' => 'Request Url',
			'meta_keywords' => 'Meta Keywords',
			'meta_description' => 'Meta Description',
			'modified' => 'Modified',
			'created' => 'Created',
			'product_tag' => 'Product Tag',
			'tab_position' => 'Tab Position',
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
		$criteria->compare('page_key',$this->page_key,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('page',$this->page,true);
		$criteria->compare('request_url',$this->request_url,true);
		$criteria->compare('meta_keywords',$this->meta_keywords,true);
		$criteria->compare('meta_description',$this->meta_description,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('product_tag',$this->product_tag,true);
		$criteria->compare('tab_position',$this->tab_position);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}