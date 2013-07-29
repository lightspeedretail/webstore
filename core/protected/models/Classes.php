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

	public static function LoadByRequestUrl($strName) {

		return Classes::model()->findByAttributes(array('request_url'=>$strName));
	}

	public function getLink() {

		return _xls_site_url("/class/".$this->request_url);
	}

	public static function GetTree() {

		$criteria = new CDbCriteria();
		$criteria->alias = 'Classes';

		$criteria->order = 'class_name';

		$objRet = Classes::model()->findAll($criteria);

		return Classes::getDataFormatted(Classes::parseTree($objRet,0));

	}

	protected static function formatData($person) {
		return array(
			'text'=>$person['text'],
			'label'=>$person['label'],
			'link'=>$person['link'],
			'url'=>$person['link'],
			'id'=>$person['id'],
			'child_count'=>$person['child_count'],
			'hasChildren'=>0);
	}

	protected static function getDataFormatted($data) {
		$personFormatted = array();
		if (is_array($data))
			foreach($data as $k=>$person) {
				$personFormatted[$k] = Classes::formatData($person);

			}
		return $personFormatted;
	}

	public static function parseTree($objRet, $root = 0)
	{
		$return = array();
		# Traverse the tree and search for direct children of the root
		foreach($objRet as $objItem) {
			if ($objItem->child_count>0 || Yii::app()->params['DISPLAY_EMPTY_CATEGORY'])
				$return[] = array(
					'text'=>CHtml::link($objItem->class_name,$objItem->Link),
					'label' => $objItem->class_name,
					'link' => $objItem->Link,
					'url' => $objItem->Link,
					'id' => $objItem->id,
					'child_count' => $objItem->child_count,
					'children' => null
				);

		}
		return empty($return) ? null : $return;
	}

}