<?php

/**
 * This is the model class for table "{{stringsource}}".
 *
 * @package application.models
 * @name Stringsource
 *
 */
class Stringsource extends BaseStringsource
{

	public $dest;
	public $string;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Stringsource the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchAdmin()
	{

		$criteria=new CDbCriteria;
		$criteria->alias = 'Stringsource';
		$criteria->select = 'Stringsource.id,message,category';
		$criteria->addCondition('category = :cat');
		if (!empty($this->string))
		{
			$criteria->addCondition('message = :string');
			$criteria->params = array(':cat'=>$this->category,':string'=>$this->string);
		}
		else
			$criteria->params = array(':cat'=>$this->category);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'message ASC',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));


	}

	public function getCategories()
	{
		return CHtml::listData(Stringsource::model()->findAllByAttributes(array(),array('distinct'=>true,'order'=>'category')), 'category', 'category');
	}

	public function getTranslated($dest,$id)
	{

		$model = Stringtranslate::model()->findByAttributes(array('id'=>$id,'language'=>$dest));
		if ($model)
			return $model->translation;
		else return "";


	}


}