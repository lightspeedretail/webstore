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
				'actions'=>array('index','edit','erasecarts','log','purge','info'),
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
					array('label'=>'Security', 'url'=>array('system/edit', 'id'=>self::SECURITY),
						'visible'=>!(Yii::app()->params['LIGHTSPEED_HOSTING'] == '1')),
				array('label'=>'Tasks', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'Purge Deleted Categories/Families', 'url'=>array('system/purge')),
					array('label'=>'Erase abandoned carts &gt; '.intval(_xls_get_conf('CART_LIFE' , 30)).' days',
						'url'=>array('system/erasecarts')
					),
				array('label'=>'Database', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'Database Admin', 'url'=>array('/admin/databaseadmin')),
				array('label'=>'System Log', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'View Log', 'url'=>array('system/log')),
				array('label'=>'About', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'System Information', 'url'=>array('system/info')),
					array('label'=>'Latest Release Notes', 'url'=>array('default/releasenotes')),

		);

		//run parent init() after setting menu so highlighting works
		return parent::beforeAction($action);

	}

	public function actionIndex()
	{
		$this->render("index");
	}

	public function actionInfo()
	{
		$this->render("info");
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

			case self::SECURITY:
				return "Note that Enabling SSL will not work before you have actually ordered and installed your SSL certificate. Turning this option on without the certificate actually installed on your site will cause Web Store to be non-functional.";

			default:
				return null;
		}

	}

	public function actionLog()
	{
		$model = new Log();
		if (isset($_GET['q']))
			$model->message = $_GET['q']; //we actually use this variable to search in several fields

		$this->render("log", array('model'=>$model));

	}

	public function actionPurge()
	{

        $check = CategoryAddl::model()->findAll();

        if (!empty($check))
        {
            $sql = "SELECT id FROM xlsws_category WHERE id NOT IN (SELECT id FROM `xlsws_category_addl`);";

            $emptycats = Yii::app()->db->createCommand($sql)->queryAll();

            foreach ($emptycats as $id)
            {
                $sqldelete = "DELETE FROM xlsws_product_category_assn WHERE category_id = ".$id['id'].";";
                try {
                    Yii::app()->db->createCommand($sqldelete)->execute();
                    $obj = Category::model()->findByPk($id);
                    $obj->UpdateChildCount();
                }
                catch (Exception $e)
                {
                    Yii::app()->user->setFlash('error',Yii::t('admin','Could not purge categories. Product associations could not be removed.'));
                }

            }
        }

        unset($check);

        $sql3 = "DELETE xlsws_category_integration.* FROM xlsws_category_integration
                LEFT JOIN xlsws_category_addl ON xlsws_category_addl.id = xlsws_category_integration.category_id
                WHERE xlsws_category_addl.id IS NULL";

		$sql1 = "DELETE xlsws_category.* FROM xlsws_category
				LEFT JOIN xlsws_category_addl ON xlsws_category_addl.id = xlsws_category.id
				WHERE xlsws_category_addl.id IS NULL";

		$sql2 ="DELETE xlsws_family.* from xlsws_family left join xlsws_product on xlsws_family.id=xlsws_product.family_id where xlsws_product.id is null";
		$success=$check=0;

        try {
            Yii::app()->db->createCommand($sql3)->execute();
            $check=1;

        }
        catch (Exception $e)
        {
            Yii::app()->user->setFlash('error',Yii::t('admin','Could not purge categories. Error encountered unassigning Amazon/Google integrations.'));
        }

        if ($check)
		try {
			Yii::app()->db->createCommand($sql1)->execute();
			$success=1;

		}
		catch (Exception $e)
		{
			Yii::app()->user->setFlash('error',Yii::t('admin','Could not purge categories. Cannot remove deleted categories that are still assigned to products.'));
		}

		if ($success)
		{
			try {
				Yii::app()->db->createCommand($sql2)->execute();
				$success=1;

			}
			catch (Exception $e)
			{
				Yii::app()->user->setFlash('error',Yii::t('admin','Could not purge families.'));
			}
		}

		if ($success)
			Yii::app()->user->setFlash('success',Yii::t('admin','Done. This option has removed any categories and families you deleted in Lightspeed that may have been left on Web Store. {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));

		$this->render("purge");

	}

	/**
	 * Erase carts that are over 30 days old, and don't have a customer_id
	 * associated with them, then optimize the tables related to the shopping
	 * cart experience.
	 */
	public function actionErasecarts()
	{
		$numErased = ShoppingCart::eraseExpired();
		ShoppingCart::optimizeTables();
		Yii::app()->user->setFlash(
			'success',
			Yii::t(
				'admin',
				'{qty} old carts and cart items removed. {time}',
				array(
					'{qty}' => $numErased,
					'{time}' => date("d F, Y  h:i:sa")
				)
			)
		);
		$this->render("erasecarts");
	}

	public function sendEmailTest()
	{


		$objEmail = new EmailQueue();
		$objEmail->subject = "Test email from "._xls_get_conf('STORE_NAME');
		$orderEmail = _xls_get_conf('ORDER_FROM','');
		$objEmail->to = empty($orderEmail) ? _xls_get_conf('EMAIL_FROM') : $orderEmail;
		$objEmail->htmlbody = "<h1>You have successfully received your test email.</h1>";
		$objEmail->save();
		$blnResult = _xls_send_email($objEmail->id,true);

		if ($blnResult)
			Yii::app()->user->setFlash('warning','Test Email Successfully Sent');
		else
		{
			Yii::app()->user->setFlash('error','ERROR -- YOUR TEST EMAIL ATTEMPT FAILED');
			$objEmail->delete();
		}

	}

}
