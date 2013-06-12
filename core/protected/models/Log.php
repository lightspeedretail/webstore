<?php

/**
 * This is the model class for table "{{log}}".
 *
 * @package application.models
 * @name Log
 *
 */
class Log extends BaseLog
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function GarbageCollect() {

		$intLogRotateDays = _xls_get_conf('LOG_ROTATE_DAYS',0);
		if ($intLogRotateDays>0) {
			$LastDate = date('YmdHis', strtotime("-".$intLogRotateDays." days"));

			$sql="DELETE from xlsws_log where DATE_FORMAT(`created`, '%Y%m%d%H%i%s')<'".$LastDate."'";
			Yii::app()->db->createCommand($sql)->execute();
		}

	}


	public function searchAll()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('message',$this->message,true,'OR');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
			'pagination' => array(
				'pageSize' => 10,
			),
		));
	}
}