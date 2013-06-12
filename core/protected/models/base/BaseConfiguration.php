<?php

/**
 * This is the base model class for table "{{configuration}}".
 *
 * The followings are the available columns in table '{{configuration}}':
 * @property string $id
 * @property string $title
 * @property string $key_name
 * @property string $key_value
 * @property string $helper_text
 * @property integer $configuration_type_id
 * @property integer $sort_order
 * @property string $modified
 * @property string $created
 * @property string $options
 * @property integer $template_specific
 * @property integer $param
 * @property integer $required
 *
 * @package application.models.base
 * @name BaseConfiguration
 */
abstract class BaseConfiguration extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{configuration}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, key_value, modified', 'required'),
			array('configuration_type_id, sort_order, template_specific, param, required', 'numerical', 'integerOnly'=>true),
			array('title, key_name', 'length', 'max'=>64),
			array('helper_text, options', 'length', 'max'=>255),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, key_name, key_value, helper_text, configuration_type_id, sort_order, modified, created, options, template_specific, param, required', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'key_name' => 'Key Name',
			'key_value' => 'Key Value',
			'helper_text' => 'Helper Text',
			'configuration_type_id' => 'Configuration Type',
			'sort_order' => 'Sort Order',
			'modified' => 'Modified',
			'created' => 'Created',
			'options' => 'Options',
			'template_specific' => 'Template Specific',
			'param' => 'Param',
			'required' => 'Required',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('key_name',$this->key_name,true);
		$criteria->compare('key_value',$this->key_value,true);
		$criteria->compare('helper_text',$this->helper_text,true);
		$criteria->compare('configuration_type_id',$this->configuration_type_id);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('options',$this->options,true);
		$criteria->compare('template_specific',$this->template_specific);
		$criteria->compare('param',$this->param);
		$criteria->compare('required',$this->required);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}