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
					array('label'=>'&larr; Back to System Menu', 'url'=>array('/admin/system')),
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
		if (count($arrLang) > 1)
		{
			$this->menuItems[] = array('label'=>'Translations', 'linkOptions'=>array('class'=>'nav-header'));
			array_shift($arrLang);
			foreach($arrLang as $key => $value)
			{
				$this->menuItems[] = array(
					'label'=>'English' . "=>" . $value,
					'url'=>array('databaseadmin/translate?dest=' . $key));
			}
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

		$objCartPayment = CartPayment::model()->findByPk($id);
		$objCart = Cart::model()->findByAttributes(array('payment_id'=>$id));
		if (isset($_POST['Cart']) && isset($_POST['CartPayment']))
		{
			echo self::processManualPayment($objCart, $objCartPayment);
		}
		else
			echo $this->renderPartial("_pay",array('objCart'=>$objCart,'model'=>$objCartPayment),true);
	}

	/**
	 * Delete an item out of a cart manually
	 */
	public function actionDelete()
	{
		foreach ($_POST['cid'] as $value)
		{
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

		if($name=="downloaded" && $value==0)
		{
			$objCart = Cart::model()->findByPk($pk);
			$objEvent = new CEventOrder('CartController','onCreateOrder',$objCart->id_str);
			_xls_raise_events('CEventOrder',$objEvent);

		}


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
				$items = CartItem::model()->findAll("product_id=".$_POST['pk']." AND (cart_type=".CartType::order." OR cart_type=".CartType::awaitpayment.")");
				if ($items)
				{
					echo "You cannot delete a product that has been used on an order";
				}
				else
				{
					_dbx("set foreign_key_checks=0;");
					Product::model()->updateAll(array('image_id' => null), 'id =' . $_POST['pk']);
					Images::model()->deleteAllByAttributes(array('product_id' => $_POST['pk']));
					ProductCategoryAssn::model()->deleteAllByAttributes(array('product_id' => $_POST['pk']));
					ProductRelated::model()->deleteAllByAttributes(array('product_id' => $_POST['pk']));
					ProductRelated::model()->deleteAllByAttributes(array('related_id' => $_POST['pk']));
					ProductTags::model()->deleteAllByAttributes(array('product_id' => $_POST['pk']));
					ProductQtyPricing::model()->deleteAllByAttributes(array('product_id' => $_POST['pk']));
					ProductText::model()->deleteAllByAttributes(array('product_id' => $_POST['pk']));
					WishlistItem::model()->deleteAllByAttributes(array('product_id' => $_POST['pk']));
					TaskQueue::model()->deleteAllByAttributes(array('product_id' => $_POST['pk']));
					Product::model()->deleteByPk($_POST['pk']);
					_dbx("set foreign_key_checks=1;");
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
	 * Customer lookup and edit
	 */
	public function actionTranslate()
	{
		//What language are we translating
		$strDestLang = Yii::app()->getRequest()->getQuery('dest');
		$strCategory = Yii::app()->getRequest()->getQuery('category');
		if (empty($strCategory))
			$strCategory="checkout";

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

	/**
	 * Process manual payment on admin panel
	 *
	 * @param $objCart
	 * @param $objCartPayment
	 * @return string 'success' or error messages from the models
	 */
	public static function processManualPayment($objCart, $objCartPayment)
	{
		$objCart->setScenario('manual');
		$objCartPayment->setScenario('manual');

		$objCart->attributes = $_POST['Cart'];
		$objCartPayment->attributes = $_POST['CartPayment'];

		switch($objCart->cart_type)
		{
			case CartType::order:
				$objCart->status = OrderStatus::AwaitingProcessing;
				$objCartPayment->payment_status = OrderStatus::Completed;
				$objCartPayment->datetime_posted = new CDbExpression('NOW()');
				break;

			case CartType::awaitpayment:
				$objCart->status = OrderStatus::AwaitingPayment;
				$objCartPayment->payment_status = NULL;
				$objCartPayment->datetime_posted = NULL;
				break;

		}

		if ($objCart->validate() && $objCartPayment->validate())
		{
			$objCart->save();
			$objCartPayment->save();
			$objEvent = new CEventOrder('CartController', 'onCreateOrder', $objCart->id_str);
			_xls_raise_events('CEventOrder', $objEvent);
			return "success";

		}
		else
			return implode(" ", _xls_convert_errors($objCart->getErrors() + $objCartPayment->getErrors()));
	}
}