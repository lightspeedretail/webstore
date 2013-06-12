<?php


class wsborderlookup extends CWidget {
	/**
	 * @var mixed the CSS file used for the widget. Defaults to null, meaning
	 * using the default CSS file included together with the widget.
	 * If false, no CSS file will be used. Otherwise, the specified CSS file
	 * will be included when using this widget.
	 */
	public $cssFile;
	/**
	 * @var array additional HTML attributes that will be rendered in the UL tag.
	 * By Default, the class is set to 'xbreadcrumbs'.
	 */
	public $htmlOptions=array();

	public $checkTheme = true;

	public $sidebarName = "Order Lookup";

	/**
	 * Renders the content of the widget.
	 */
	public $links;


	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		Yii::import('application.extensions.'.get_class($this).'.models.*');

	}

	/* Runs widget which simply loads the search template
	*/
	public function run()
	{
		$model=new LookupForm;

		if(isset($_POST['LookupForm']))
		{

			$model->attributes=$_POST['LookupForm'];

			if($model->validate())
			{
				//Because our validate already checks to see if it's a valid combination, we can trust loading here and just redir
				if($model->orderType ==CartType::order) {
					$objCustomer = Customer::LoadByEmail($model->emailPhone);
					$objCart = Cart::model()->findByAttributes(array('id_str'=>$model->orderId,'customer_id'=>$objCustomer->id));
					Yii::app()->controller->redirect($objCart->Link);
				}

				if($model->orderType ==CartType::sro) {
					$objSro = Sro::model()->findByAttributes(array('customer_email_phone'=>$model->emailPhone,'ls_id'=>$model->orderId));
					Yii::app()->controller->redirect($objSro->Link);
				}
			}
		}
		$this->render('index',array('model'=>$model));
	}

}