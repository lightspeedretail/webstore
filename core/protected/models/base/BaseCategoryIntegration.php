<?php

/**
 * This is the base model class for table "{{category_integration}}".
 *
 * The followings are the available columns in table '{{category_integration}}':
 * @property string $category_id
 * @property string $module
 * @property string $foreign_id
 * @property string $extra
 *
 * The followings are the available model relations:
 * @property Category $category
 *
 * @package application.models.base
 * @name BaseCategoryIntegration
 */
abstract class BaseCategoryIntegration extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{category_integration}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module', 'length', 'max'=>30),
			array('foreign_id', 'length', 'max'=>11),
			array('extra', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('category_id, module, foreign_id, extra', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'category_id' => 'Category',
			'module' => 'Module',
			'foreign_id' => 'Foreign',
			'extra' => 'Extra',
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

		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('module',$this->module,true);
		$criteria->compare('foreign_id',$this->foreign_id,true);
		$criteria->compare('extra',$this->extra,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}