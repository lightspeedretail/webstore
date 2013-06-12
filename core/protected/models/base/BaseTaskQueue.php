<?php

/**
 * This is the base model class for table "{{task_queue}}".
 *
 * The followings are the available columns in table '{{task_queue}}':
 * @property string $id
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property string $data_id
 * @property string $product_id
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Product $product
 *
 * @package application.models.base
 * @name BaseTaskQueue
 */
abstract class BaseTaskQueue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{task_queue}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module, controller, action, data_id', 'length', 'max'=>255),
			array('product_id', 'length', 'max'=>20),
			array('created, modified', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, module, controller, action, data_id, product_id, created, modified', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'module' => 'Module',
			'controller' => 'Controller',
			'action' => 'Action',
			'data_id' => 'Data',
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
		$criteria->compare('module',$this->module,true);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('data_id',$this->data_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}