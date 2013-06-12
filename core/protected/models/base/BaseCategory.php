<?php

/**
 * This is the base model class for table "{{category}}".
 *
 * The followings are the available columns in table '{{category}}':
 * @property string $id
 * @property string $label
 * @property string $parent
 * @property integer $menu_position
 * @property integer $child_count
 * @property string $request_url
 * @property string $custom_page
 * @property string $image_id
 * @property string $meta_description
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property CustomPage $customPage
 * @property Category $parent0
 * @property Category[] $categories
 * @property CategoryIntegration[] $categoryIntegrations
 * @property Product[] $xlswsProducts
 *
 * @package application.models.base
 * @name BaseCategory
 */
abstract class BaseCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('menu_position, modified', 'required'),
			array('menu_position, child_count', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>64),
			array('parent, custom_page', 'length', 'max'=>11),
			array('request_url, meta_description', 'length', 'max'=>255),
			array('image_id', 'length', 'max'=>20),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, parent, menu_position, child_count, request_url, custom_page, image_id, meta_description, created, modified', 'safe', 'on'=>'search'),
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
			'customPage' => array(self::BELONGS_TO, 'CustomPage', 'custom_page'),
			'parent0' => array(self::BELONGS_TO, 'Category', 'parent'),
			'categories' => array(self::HAS_MANY, 'Category', 'parent'),
			'categoryIntegrations' => array(self::HAS_MANY, 'CategoryIntegration', 'category_id'),
			'xlswsProducts' => array(self::MANY_MANY, 'Product', '{{product_category_assn}}(category_id, product_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'label' => 'Label',
			'parent' => 'Parent',
			'menu_position' => 'Menu Position',
			'child_count' => 'Child Count',
			'request_url' => 'Request Url',
			'custom_page' => 'Custom Page',
			'image_id' => 'Image',
			'meta_description' => 'Meta Description',
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
		$criteria->compare('label',$this->label,true);
		$criteria->compare('parent',$this->parent,true);
		$criteria->compare('menu_position',$this->menu_position);
		$criteria->compare('child_count',$this->child_count);
		$criteria->compare('request_url',$this->request_url,true);
		$criteria->compare('custom_page',$this->custom_page,true);
		$criteria->compare('image_id',$this->image_id,true);
		$criteria->compare('meta_description',$this->meta_description,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}