<?php

/**
 * This is the model class for table "{{classes}}".
 *
 * @package application.models
 * @name Classes
 *
 */
class Classes extends BaseClasses
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Classes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function ConvertSEO() {

		$arr = Classes::model()->findAll();
		foreach ($arr as $obj) {
			$obj->request_url = _xls_seo_url($obj->class_name);
			$obj->save();
		}

	}

	public function UpdateChildCount()
	{

		$criteria = new CDbCriteria();
		$criteria->alias = 'Product';



		$criteria->condition = 'class_id = :id AND web=1
			AND (current=1 OR (current=0 AND inventoried=1 AND inventory_avail>0))
			AND (
				(master_model=1) OR
				(master_model=0 AND parent IS NULL)
			)';
		$criteria->params = array (':id'=>$this->id);


		$intCount = Product::model()->count($criteria);
		$this->child_count = $intCount;
		$this->save();


	}

}