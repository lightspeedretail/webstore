<?php

class SystemController extends AdminBaseController
{
	public $controllerName = "System";

	//Codes for this controller
	const SYSTEM_CONFIGURATION = 1;
	const EMAIL_SERVERS = 5;
	const SECURITY = 16;
	const SEO_URL = 21;
	const SEO_PRODUCT = 22;
	const SEO_CATEGORY = 23;
	const PROCESSORS = 28;

	public function actions()
	{
		return array(
			'edit'=>'admin.edit',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','edit','erasecarts','log','purge'),
				'roles'=>array('admin'),
			),
		);
	}

	public function beforeAction($action)
	{



		$this->menuItems =
			array(

				array('label'=>'Setup', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'System Configuration', 'url'=>array('system/edit', 'id'=>self::SYSTEM_CONFIGURATION)),
					array('label'=>'Event Processors', 'url'=>array('system/edit', 'id'=>self::PROCESSORS)),
					array('label'=>'Email Servers', 'url'=>array('system/edit', 'id'=>self::EMAIL_SERVERS)),
					array('label'=>'Security', 'url'=>array('system/edit', 'id'=>self::SECURITY)),
				array('label'=>'Tasks', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'Purge Deleted Categories/Families', 'url'=>array('system/purge')),
					array('label'=>'Erase abandoned carts &gt; '.intval(_xls_get_conf('CART_LIFE' , 30)).' days', 'url'=>array('system/erasecarts')),
				array('label'=>'System Log', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'View Log', 'url'=>array('system/log')),

		);

		//run parent init() after setting menu so highlighting works
		return parent::beforeAction($action);

	}

	public function actionIndex()
	{
		$this->render("index");
	}



	/**
	 * Return Instructions to be displayed in admin panel. This function should be overridden in each controller
	 * @param $id
	 * @return null
	 */
	protected function getInstructions($id)
	{

		switch($id)
		{


			case self::EMAIL_SERVERS:
				return "<P>Important: The Order From email address must be for the SMTP account entered. Entering a different email (i.e. spoofing) will cause emails not to send (the error log will report Invalid Username/Password). If the Order From field is left blank, the system will use the Store Email from ".CHtml::link("Store Information",$this->createUrl("default/edit",array('id'=>2)))."</p>";

			case self::PROCESSORS:
				return "<P>For advanced use, this page can be used to augment or replace certain Web Store functionality with external processors. Third-party extensions can be designed to be displayed among these options. See our development documentation for details.</p>";


			default:
				return null;
		}

	}

	public function actionLog()
	{
		$model = new Log();

		$this->render("log", array('model'=>$model));

	}

	public function actionPurge()
	{
		_dbx("DELETE xlsws_category.* FROM xlsws_category
				LEFT JOIN xlsws_category_addl ON xlsws_category_addl.id = xlsws_category.id
				WHERE xlsws_category_addl.id IS NULL");

		_dbx("DELETE xlsws_family.* from xlsws_family left join xlsws_product on xlsws_family.id=xlsws_product.family_id where xlsws_product.id is null");

		Yii::app()->user->setFlash('success',Yii::t('cart','Deleted categories and families removed {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));

		$this->render("purge");

	}

	public function actionErasecarts()
	{

		$sql = "
		FROM xlsws_cart P, xlsws_cart_item C
		WHERE
			P.customer_id IS NULL AND
			P.id = C.cart_id AND
			P.cart_type IN (" . CartType::cart . "," . CartType::giftregistry . "," . CartType::awaitpayment . ") AND
			P.modified < '".date("Y-m-d", strtotime("-".intval(_xls_get_conf('CART_LIFE' , 30))." DAYS"))."' AND
			id_str IS NULL";


		$intIdStr = Yii::app()->db->createCommand("select count(*) ".$sql)->queryScalar();

		_dbx("DELETE P, C ".$sql);


		_dbx("OPTIMIZE table xlsws_cart");
		_dbx("OPTIMIZE table xlsws_cart_item");
		_dbx("OPTIMIZE table xlsws_customer");
		_dbx("OPTIMIZE table xlsws_wish_list");
		_dbx("OPTIMIZE table xlsws_wish_list_items");
		_dbx("OPTIMIZE table xlsws_product");
		_dbx("OPTIMIZE table xlsws_product_related");
		_dbx("OPTIMIZE table xlsws_category");
		_dbx("OPTIMIZE table xlsws_product_category_assn");

		Yii::app()->user->setFlash('success',Yii::t('cart','{qty} old carts removed {time}.',array('{qty}'=>$intIdStr, '{time}'=>date("d F, Y  h:i:sa"))));

		$this->render("erasecarts");

	}


}