<?php

class DatabaseadminController extends AdminBaseController
{
	public $controllerName = "Db";


	/**
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','add','edit','delete','orders',
					'pay','pending','products','unpaid','update','customers','translate','wishlist','wishlistitem','resetpassword'),
				'roles'=>array('admin'),
			),
		);
	}


	/**
	 * Create menu for this controller
	 * @param CAction $action
	 * @return bool
	 */
	public function beforeAction($action)
	{

		$this->menuItems =
			array(
				array('label'=>'Customers', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'Edit Customers', 'url'=>array('databaseadmin/customers')),
				array('label'=>'Orders', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'Downloaded Orders', 'url'=>array('databaseadmin/orders')),
					array('label'=>'Pending Orders', 'url'=>array('databaseadmin/pending')),
					array('label'=>'Unpaid Orders', 'url'=>array('databaseadmin/unpaid')),
				array('label'=>'Products', 'linkOptions'=>array('class'=>'nav-header')),
					array('label'=>'Product Lookup', 'url'=>array('databaseadmin/products')),



			);

		$arrLang = _xls_avail_languages();
		if (count($arrLang)>1) {
			$this->menuItems[] = array('label'=>'Translations', 'linkOptions'=>array('class'=>'nav-header'));
			$strOriginal = $arrLang[_xls_get_conf('LANG_CODE')];
			array_shift($arrLang);
			foreach($arrLang as $key=>$value)
				$this->menuItems[] = array(
					'label'=>$strOriginal."=>".$value,
					'url'=>array('databaseadmin/translate?dest='.$key));
		}


		return parent::beforeAction($action);

	}

	/**
	 * Index (help pages)
	 */
	public function actionIndex()
	{
		$this->render('index');
	}


	/**
	 * Display order history
	 */
	public function actionOrders()
	{
		$model = new Cart();
		$model->downloaded=1;
		if (isset($_GET['q']))
		{
			$model->id_str = $_GET['q'];
			$model->datetime_cre = $_GET['q'];
		}

		$this->render("orders", array('model'=>$model));

	}

	/**
	 * Display orders waiting to download (paid but not yet downloaded)
	 */
	public function actionPending()
	{
		$model = new Cart();
		$model->downloaded=0;
		$model->cart_type=CartType::order;
		if (isset($_GET['q']))
		{
			$model->id_str = $_GET['q'];
			$model->datetime_cre = $_GET['q'];
		}

		$this->registerAsset("js/edit.js");
		$this->render("pending", array('model'=>$model));

	}

	/**
	 * Display unpaid carts
	 */
	public function actionUnpaid()
	{
		$model = new Cart();
		$model->downloaded=0;
		$model->cart_type=CartType::awaitpayment;
		if (isset($_GET['q']))
		{
			$model->id_str = $_GET['q'];
			$model->datetime_cre = $_GET['q'];
		}

		$this->registerAsset("js/pay.js");
		$this->render("unpaid", array('model'=>$model));

	}

	/**
	 * Edit cart items manually
	 */
	public function actionEdit()
	{

		$id = Yii::app()->getRequest()->getQuery('id');

		$model = new CartItem();
		$model->cart_id = $id;
		$model->cart_type = 4;

		echo $this->renderPartial("_edititems",array('model'=>$model),true);

	}

	/**
	 * Mark a cart as paid manually
	 */
	public function actionPay()
	{

		$id = Yii::app()->getRequest()->getQuery('id');

		//
		$model = CartPayment::model()->findByPk($id);
		$objCart = Cart::model()->findByAttributes(array('payment_id'=>$id));
		if (isset($_POST['Cart']) &&  isset($_POST['CartPayment']))
		{

			$objCart->attributes = $_POST['Cart'];
			$model->attributes = $_POST['CartPayment'];
			$objCart->setScenario('manual');
			$model->setScenario('manual');

			if ($objCart->validate() && $model->validate())
			{
				$objCart->save();
				$model->save();
				echo "success";

			} else echo implode(" ",_xls_convert_errors($objCart->getErrors() + $model->getErrors()));

		} else echo $this->renderPartial("_pay",array('objCart'=>$objCart,'model'=>$model),true);

	}

	/**
	 * Delete an item out of a cart manually
	 */
	public function actionDelete()
	{
		foreach ($_POST['cid'] as $value) {
			Yii::log("Admin Panel DELETE cart item ".$value, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			CartItem::model()->deleteByPk($value);
		}

		echo "success";

	}

	/**
	 * Ajax update function for any grid changes
	 */
	public function actionUpdate()
	{
		$pk = Yii::app()->getRequest()->getPost('pk');
		$name = Yii::app()->getRequest()->getPost('name');
		$value = Yii::app()->getRequest()->getPost('value');

		Cart::model()->updateByPk($pk,array($name=>$value));
		echo "success";


	}

	/**
	 * Product lookup and optional delete, shows inventory numbers
	 */
	public function actionProducts()
	{
		if(isset($_POST['pk']) && isset($_POST['name']) && isset($_POST['value']))
		{
			if ($_POST['name']=='code' && $_POST['value']=="")
			{
				$m1 = CartItem::model()->findAllByAttributes(array('product_id'=>$_POST['pk']));
				$m2 = DocumentItem::model()->findAllByAttributes(array('product_id'=>$_POST['pk']));
				if (count($m1)+count($m2)>0)
					echo "You cannot delete a product that has been used on an order";
				else {
					Product::model()->updateAll(array('image_id'=>null),'id ='.$_POST['pk']);
					Images::model()->deleteAllByAttributes(array('product_id'=>$_POST['pk']));
					ProductCategoryAssn::model()->deleteAllByAttributes(array('product_id'=>$_POST['pk']));
                    ProductRelated::model()->deleteAllByAttributes(array('product_id'=>$_POST['pk']));
                    ProductRelated::model()->deleteAllByAttributes(array('related_id'=>$_POST['pk']));
                    ProductTags::model()->deleteAllByAttributes(array('product_id'=>$_POST['pk']));
                    ProductQtyPricing::model()->deleteAllByAttributes(array('product_id'=>$_POST['pk']));
                    ProductText::model()->deleteAllByAttributes(array('product_id'=>$_POST['pk']));
					WishlistItem::model()->deleteAllByAttributes(array('product_id'=>$_POST['pk']));
					TaskQueue::model()->deleteAllByAttributes(array('product_id'=>$_POST['pk']));
					Product::model()->deleteByPk($_POST['pk']);
					echo "delete";
				}
			} else echo Yii::t('admin','You cannot change a product code here. Delete the code to remove it manually from the Web Store database');

		} else {


			$model = new Product();
			if (isset($_GET['q']))
			{
				$model->code = $_GET['q'];
			}

			$this->render("products", array('model'=>$model));
		}

	}

	/**
	 * Customer lookup and edit
	 */
	public function actionCustomers()
	{
		if(isset($_POST['pk']) && isset($_POST['name']) && isset($_POST['value']))
		{

			$pk = Yii::app()->getRequest()->getPost('pk');
			$name = Yii::app()->getRequest()->getPost('name');
			$value = Yii::app()->getRequest()->getPost('value');

			Customer::model()->updateByPk($pk,array($name=>$value));
			echo "success";

		} else {

			$model = new Customer();
			if (isset($_GET['q']))
				$model->email = $_GET['q']; //we actually use this variable to search in several fields


			$objs = Customer::model()->findAllByAttributes(array('allow_login'=>2));

			if (count($objs)>0)
			{
				$str = "The following accounts have external Admin access to your store: ";
				foreach ($objs as $obj)
					$str .= "<b>$obj->fullname</b>, ";
				Yii::app()->user->setFlash('warning',substr($str,0,-2));
			}

			$this->registerAsset("js/wishlist.js");
			$this->registerAsset("js/resetpw.js");
			$this->render("customers", array('model'=>$model));
		}


	}

	/**
	 *
	 */
	public function actionWishlist()
	{


		$id = Yii::app()->getRequest()->getQuery('id');

		$model = Customer::model()->findByPk($id);
		echo $this->renderPartial("_wishlist",array('model'=>$model),true);



	}

	/**
	 *
	 */
	public function actionWishlistitem()
	{


		$id = Yii::app()->getRequest()->getQuery('id');

		$model = new WishlistItem();
		$model->registry_id = $id;

		echo $this->renderPartial("_wishlistitem",array('model'=>$model),true);



	}


	/**
	 * Reset/create new password for user, and send customer email
	 */
	public function actionResetPassword()
	{

		$id = Yii::app()->getRequest()->getQuery('id');

		$model = Customer::model()->findByPk($id);

		$Customer = Yii::app()->getRequest()->getPost('Customer');
		if (isset($Customer)) {

			$retVal='';

			$model->password = _xls_encrypt($Customer['password_repeat']);
			$model->save();

			if($retVal=="")
			{
				Yii::app()->user->setFlash('success',Yii::t('admin','Password updated and sent for {user} at {time}.',array('{user}'=>$model->fullname,'{time}'=>date("d F, Y  h:i:sa"))));
				$retVal = "success";
				if( ($theme=Yii::app()->getTheme()) !==null &&
					file_exists(YiiBase::getPathOfAlias('webroot').'/themes/'.$theme->name.'/mail/_forgotpassword.php'))
					$path = 'webroot.themes.'.$theme->name.'.mail._forgotpassword';
				else
					$path = 'application.views.mail._forgotpassword';

				$strHtmlBody =$this->renderPartial($path,array('model'=>$model), true);
				$strSubject = Yii::t('global','Password reminder');

				$objEmail = new EmailQueue;

				$objEmail->htmlbody = $strHtmlBody;
				$objEmail->subject = $strSubject;
				$objEmail->to = $model->email;

				$objEmail->save();
				$bln = _xls_send_email($objEmail->id,true);

				if (!$bln)
					Yii::app()->user->setFlash('error',Yii::t('admin','Email failed to send to user'));

			}

			echo $retVal;
		} else
		echo $this->renderPartial("_resetpw",array('model'=>$model),true);



	}



	/**
	 * Customer lookup and edit
	 */
	public function actionTranslate()
	{
		//What language are we translating
		$strDestLang = Yii::app()->getRequest()->getQuery('dest');
		$strCategory = Yii::app()->getRequest()->getQuery('category');
		if (empty($strCategory)) $strCategory="checkout";

		if(isset($_POST['pk']) && isset($_POST['name']) && isset($_POST['value']))
		{

			$pk = Yii::app()->getRequest()->getPost('pk');
			$name = Yii::app()->getRequest()->getPost('name');
			$value = Yii::app()->getRequest()->getPost('value');

			$string=Stringtranslate::model()->findByAttributes(array('language'=>$strDestLang,'id'=>$pk));
			if(!$string)
			{
				$string = new Stringtranslate();
				$string->id = $pk;
				$string->language = $strDestLang;
			}

			$string->translation=$value;
			$string->save();

			echo "success";

		} else {

			$model = new Stringsource();
			if (isset($_GET['q']))
				$model->string = $_GET['q']; //we actually use this variable to search in several fields
			$model->dest = $strDestLang;
			$model->category=$strCategory;

			$this->render("translate", array('model'=>$model));
		}






	}



}