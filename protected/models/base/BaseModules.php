<?php

/**
 * This is the base model class for table "{{modules}}".
 *
 * The followings are the available columns in table '{{modules}}':
 * @property string $id
 * @property integer $active
 * @property string $module
 * @property string $category
 * @property integer $version
 * @property string $name
 * @property integer $sort_order
 * @property string $configuration
 * @property string $modified
 * @property string $created
 *
 * @package application.models.base
 * @name BaseModules
 */
abstract class BaseModules extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{modules}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modified', 'required'),
			array('active, version, sort_order', 'numerical', 'integerOnly'=>true),
			array('module', 'length', 'max'=>64),
			array('category, name', 'length', 'max'=>255),
			array('configuration, created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, active, module, category, version, name, sort_order, configuration, modified, created', 'safe', 'on'=>'search'),
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
			'active' => 'Active',
			'module' => 'Module',
			'category' => 'Category',
			'version' => 'Version',
			'name' => 'Name',
			'sort_order' => 'Sort Order',
			'configuration' => 'Configuration',
			'modified' => 'Modified',
			'created' => 'Created',
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
		$criteria->compare('active',$this->active);
		$criteria->compare('module',$this->module,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('version',$this->version);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('configuration',$this->configuration,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}