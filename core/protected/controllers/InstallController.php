<?php

/**
 * Install controller used for initial Web Store install
 *
 * @category   Controller
 * @package    Install
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2013-05-14

 */

class InstallController extends Controller
{

	protected $online;
	
	public function init() {
		Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-cities");
		set_time_limit(300);
		//We override init() to keep our system from trying to autoload stuff we haven't finished converting yet
	}


	/**
	 * For this controller, we only want to run these functions if LSKEY isn't set (meaning we're partially through an install)
	 * Otherwise, we give an exception to prevent running any of these processes.
	 * @param CAction $action
	 * @return bool
	 * @throws CHttpException
	 */
	public function beforeAction($action)
	{

		if (strlen(_xls_get_conf('LSKEY'))>0 &&
			$action->id != "exportconfig" &&
			$action->id != "upgrade" &&
			$action->id != "fixlink" &&
			$action->id != "migratephotos")
		{
			error_log("stopped because key was "._xls_get_conf('LSKEY')." on action ".$action->id);

			throw new CHttpException(404,'The requested page does not exist.');
			return false;
		}
		return parent::beforeAction($action);

	}

	/**
	 * Hide controller behind 404 exception
	 * @throws CHttpException
	 */
	public function actionIndex()
	{
		throw new CHttpException(404,'The requested page does not exist.');
	}

	/**
	 * Create a symbolic link for the views file to our default viewset
	 */
	public function actionFixlink()
	{
		$symfile = YiiBase::getPathOfAlias('application')."/views";
		$strOriginal = YiiBase::getPathOfAlias('application.views')."-".strtolower("cities");
		@unlink($symfile);
		symlink($strOriginal, $symfile);
	}

	/**
	 * Export the initial configuration
	 */
	public function actionExportConfig()
	{

		if(isset($_GET['debug']))
			Yii::log("Exporting Configuration", 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		Configuration::exportConfig();
		Configuration::exportLogging();

		echo json_encode(array('result'=>"success"));
	}

	/**
	 * Master function to call the other upgrade steps
	 */
	public function actionUpgrade()
	{

		$this->online = _xls_number_only($_POST['online']);
		if(isset($_GET['debug']))
			Yii::log("InstallController on line ".$this->online, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		if ($this->online==1)                       $retval = $this->actionConvertStart();
		if ($this->online==2)                       $retval = $this->actionConvertAddressBook();
		if ($this->online==8)                       $retval = $this->actionConvertModules();
		if ($this->online>=9 && $this->online<=13)  $retval = $this->actionConvertGoogle();
		if ($this->online==14)                      $retval = $this->actionConvertKeywordsToTags();
		if ($this->online==15)                      $retval = $this->actionConvertFamilies();
		if ($this->online==16)                      $retval = $this->actionConvertClasses();
		if ($this->online==18)                      $retval = $this->actionConvertDestinationTables();
		if ($this->online==19)                      $retval = $this->actionDropcartfields1();
		if ($this->online==20)                      $retval = $this->actionDropcartfields2();
		if ($this->online==21)                      $retval = $this->actionDropcartfields3();
		if ($this->online==25)                      $retval = $this->actionConvertProductSEO();
		if ($this->online>=32 && $this->online<=44) $retval = $this->actionImportAmazon();
		if ($this->online==45)                      $retval = $this->actionDropcustomerfields();
		if ($this->online==46)                      $retval = $this->actionDropProductFields();
		if ($this->online==47)                      $retval = $this->actionCalculateInventory();
		if ($this->online==48)                      $retval = $this->actionUpdateConfiguration();
		if ($this->online==49)                      $retval = $this->actionApplyLatestChanges();

		if($retval != null)
		{
			if(isset($_GET['debug']))
				if(isset($retval['tag']))
					$retval['tag'] .= " online ".$this->online; else $retval['tag'] = " online ".$this->online;

			echo json_encode($retval);
		}

	}

	/**
	 * Before we do anything else, write our config table to our params file for faster access
	 */
	protected function actionConvertStart()
	{
		if(isset($_GET['debug']))
			Yii::log("Exporting Configuration", 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		Configuration::exportConfig();
		Configuration::exportLogging();

		//And download brooklyn as a default
		$filename = Yii::getPathOfAlias('webroot.themes').DIRECTORY_SEPARATOR.'brooklyn';
		if(!file_exists($filename))
			downloadBrooklyn();

		return array('result'=>"success",'makeline'=>2,'tag'=>'Converting cart addresses','total'=>50);

	}

	/**
	 * Extract shipping and billing address information, create address book and map to the carts
	 */
	protected function actionConvertAddressBook()
	{

		$sql = "select * from xlsws_cart where billaddress_id IS NULL and address_bill IS NOT NULL order by id limit 50";
		$results=Yii::app()->db->createCommand($sql)->queryAll();

		foreach($results AS $result) {

			$result['email'] = strtolower($result['email']);

			//verify that Customer ID really exists in customer table
			$objCust = Customer::model()->findByPk($result['customer_id']);

			if (!($objCust instanceof Customer))
				$result['customer_id']=0;


			if (strlen($result['address_bill'])>0) {

					$arrAddress = explode("\n",$result['address_bill']);
					if (count($arrAddress)==5) {
						//old format address, should be 6 pieces
						$arrAddress[5] = $arrAddress[4];
						$strSt = $arrAddress[3];
						if ($strSt[0]==" ") {
							//no state on this address
							$arrAddress[4] = substr($strSt,1,100);
							$arrAddress[3]="";
						} else {
							$arrSt = explode(" ",$strSt);
							$arrAddress[3] = $arrSt[0];
							$arrAddress[4] = str_replace($arrSt[0]." ","",$strSt);
						}

					}

				$objAddress = new CustomerAddress;

				if (count($arrAddress)>=5) {
					$objCountry = Country::LoadByCode($arrAddress[5]);
					if ($objCountry) {
						$objAddress->country_id = $objCountry->id;

						$objState = State::LoadByCode($arrAddress[3],$objCountry->id);
						if ($objState)
							$objAddress->state_id = $objState->id;
					}



					$objAddress->address1 = $arrAddress[0];
					$objAddress->address2 = $arrAddress[1];
					$objAddress->city = $arrAddress[2];
					$objAddress->postal = $arrAddress[4];

					$objAddress->first_name = $result['first_name'];
					$objAddress->last_name = $result['last_name'];
					$objAddress->company = $result['company'];

					$objAddress->phone = $result['phone'];
					$objAddress->residential = CustomerAddress::RESIDENTIAL;
					$objAddress->created = $result['datetime_cre'];
					$objAddress->modified = $result['datetime_cre'];
					$objAddress->active = 1;


					if (empty($objAddress->address2)) $objAddress->address2=null;
					if (empty($objAddress->company)) $objAddress->company=null;

					$blnFound = false;
					if ($result['customer_id']>0) {

						//See if this is already in our database
						$objPriorAddress = CustomerAddress::model()->findByAttributes(array(
							'address1'=>$objAddress->address1,
							'address2'=>$objAddress->address2,
							'city'=>$objAddress->city,
							'postal'=>$objAddress->postal,
							'first_name'=>$objAddress->first_name,
							'last_name'=>$objAddress->last_name,
							'company'=>$objAddress->company,
							'phone'=>$objAddress->phone));

						if ($objPriorAddress instanceof CustomerAddress) {
							Yii::app()->db->createCommand("update xlsws_cart set billaddress_id=".$objPriorAddress->id." where id=".$result['id'])->execute();
							$blnFound=true;
						}
						else
							$objAddress->customer_id=$result['customer_id'];

					}
					else
					{
						//We need a shell customer record just for the email
						$objC = Customer::model()->findByAttributes(array('email'=>$result['email']));
						if ($objC instanceof Customer)
							Yii::app()->db->createCommand("UPDATE xlsws_cart set customer_id=".$objC->id." where id=".$result['id'])->execute();
						else
						{
							$objC = new Customer;
							$objC->record_type = Customer::GUEST;
							$objC->email = $result['email'];
							$objC->first_name=$objAddress->first_name;
							$objC->last_name=$objAddress->last_name;
							$objC->company=$objAddress->company;
							if (!$objC->validate())
							{
								$arrErr = $objC->getErrors();

								if (isset($arrErr['email'])){
									$objC->email = $result['id'].".invalid@example.com";
								}
								if (!$objC->validate())
									return print_r($objC->getErrors(),true);
							}

							if (!$objC->save()) {
								Yii::log("Import Error ".print_r($objC->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
								return print_r($objC->getErrors(),true);
							}
							else $cid = $objC->id;
							Yii::app()->db->createCommand("upDATE xlsws_cart set customer_id=".$cid." where id=".$result['id'])->execute();
						}

						$result['customer_id'] = $objC->id;
						$objAddress->customer_id=$result['customer_id'];
					}


					if (!$blnFound) {
						if (!$objAddress->save()) {
							//We have a corrupt billing address, just blank it out so import goes on
							Yii::app()->db->createCommand("update xlsws_cart set address_bill=null where id=".$result['id'])->execute();
						}
						else {
							$cid = $objAddress->id;
							Yii::app()->db->createCommand("update xlsws_cart set billaddress_id=".$cid." where id=".$result['id'])->execute();
						}

					}
				}
				else
				{
					//We have a corrupt billing address, just blank it out so import goes on
					Yii::app()->db->createCommand("update xlsws_cart set address_bill=null where id=".$result['id'])->execute();
				}

				$objAddress = new CustomerAddress;


				$objCountry = Country::LoadByCode($result['ship_country']);
				if ($objCountry) {
					$objAddress->country_id = $objCountry->id;

					$objState = State::LoadByCode($result['ship_state'],$objCountry->id);
					if ($objState)
						$objAddress->state_id = $objState->id;
				}




				$objAddress->first_name = $result['ship_firstname'];
				$objAddress->last_name = $result['ship_lastname'];
				$objAddress->company = $result['ship_company'];
				$objAddress->address1 = $result['ship_address1'];
				$objAddress->address2 = $result['ship_address2'];
				$objAddress->city = $result['ship_city'];


				$objAddress->postal = $result['ship_zip'];
				$objAddress->phone = $result['ship_phone'];
				$objAddress->residential = CustomerAddress::RESIDENTIAL;
				$objAddress->created = $result['datetime_cre'];
				$objAddress->modified = $result['datetime_cre'];
				$objAddress->active = 1;
				if (empty($objAddress->address2)) $objAddress->address2=null;
				if (empty($objAddress->company)) $objAddress->company=null;


				$blnFound = false;
			if ($result['customer_id']>0) {



				//See if this is already in our database
				$objPriorAddress = CustomerAddress::model()->findByAttributes(array(
					'address1'=>$objAddress->address1,
					'city'=>$objAddress->city,
					'postal'=>$objAddress->postal,
					'first_name'=>$objAddress->first_name,
					'last_name'=>$objAddress->last_name,
					'company'=>$objAddress->company,
					'phone'=>$objAddress->phone));

				if ($objPriorAddress instanceof CustomerAddress) {
					Yii::app()->db->createCommand("update xlsws_cart set shipaddress_id=".$objPriorAddress->id." where id=".$result['id'])->execute();
					$blnFound=true;
				}
				else
					$objAddress->customer_id=$result['customer_id'];
			}

				if (!$blnFound)
				{
					if (!$objAddress->save())
						Yii::log("Import Error ".print_r($objAddress->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					else
					{
						$cid = $objAddress->id;
						Yii::app()->db->createCommand("update xlsws_cart set shipaddress_id=".$cid." where id=".$result['id'])->execute();
					}
				}
			}


			$objShipping = new CartShipping;

			$objShipping->shipping_method = $result['shipping_method'];
			$objShipping->shipping_module = $result['shipping_module'];
			$objShipping->shipping_data = $result['shipping_data'];
			$objShipping->shipping_cost = $result['shipping_cost'];
			$objShipping->shipping_sell = $result['shipping_sell'];

			if (!$objShipping->save())
				return print_r($objShipping->getErrors());
			else
				$cid = $objShipping->id;
			Yii::app()->db->createCommand("update xlsws_cart set shipping_id=".$cid." where id=".$result['id'])->execute();


			$objPayment = new CartPayment;

			$objPayment->payment_method = $result['payment_method'];
			$objPayment->payment_module = str_replace(".php","",$result['payment_module']);
			$objPayment->payment_data = $result['payment_data'];
			$objPayment->payment_amount = $result['payment_amount'];
			$objPayment->datetime_posted = $result['datetime_posted'];

			if ($result['fk_promo_id']>0) {

				$objPromo = PromoCode::model()->findByPk($result['fk_promo_id']);
				if ($objPromo) $objPayment->promocode = $objPromo->code;

			}

			if (!$objPayment->save())
				return print_r($objPayment->getErrors());
			else
				$cid = $objPayment->id;
			Yii::app()->db->createCommand("update xlsws_cart set payment_id=".$cid." where id=".$result['id'])->execute();


		}


		$results2=Yii::app()->db->createCommand(
			"select count(*) from xlsws_cart where billaddress_id IS NULL and address_bill IS NOT NULL")->queryScalar();

		if ($results2==0) $remain=8;
		else
			$remain=2;

		return array('result'=>"success",'makeline'=>$remain,'total'=>50,'tag'=>'Converting cart addresses, '.$results2.' remaining');


	}


	/**
	 * 8 Rename modules, load google
	 */
	protected function actionConvertModules()
	{
		//Change country to ID instead of text string based on xlsws_countries
		$strCountry = _xls_get_conf('DEFAULT_COUNTRY');
		$objCountry = Country::LoadByCode($strCountry);
		if ($objCountry)
			_xls_set_conf('DEFAULT_COUNTRY',$objCountry->id);

		_dbx("update xlsws_modules set module = replace(module, '.php', '')");


		$arrModuleRename = array(
			'authorize_dot_net_aim' =>'authorizedotnetaim',
			'authorize_dot_net_sim' =>'authorizedotnetsim',
			'axia'                  =>'axia',
			'beanstream_aim'        =>'beanstreamaim',
			'beanstream_sim'        =>'beanstreamsim',
			'cheque'                =>'cheque',
			'eway_cvn_aus'          =>'ewayaim',
			'merchantware'          =>'merchantware',
			'paypal_webpayments_pro'=>'paypalpro',
			'paypal'                =>'paypal',
			'phone_order'           =>'phoneorder',
			'purchase_order'        =>'purchaseorder',
			'worldpay'              =>'worldpaysim',
			'xlsws_class_payment'   =>'cashondelivery'
		);

		foreach ($arrModuleRename as $key=>$value) {
			$objModule =  Modules::model()->findByAttributes(array('category'=>'payment','module'=>$key));
			if ($objModule instanceof Modules) {
				$objModule->module = $value;
				$objModule->save();
			}
		}

		$arrModuleRename = array(
			'australiapost'     =>'australiapost',
			'canadapost'        =>'canadapost',
			'destination_table' =>'destinationshipping',
			'fedex'             =>'fedex',
			'flat_rate'         =>'flatrate',
			'free_shipping'     =>'freeshipping',
			'intershipper'      =>'intershipper',
			'iups'              =>'iups',
			'store_pickup'      =>'storepickup',
			'tier_table'        =>'tieredshipping',
			'ups'               =>'ups',
			'usps'              =>'usps'
		);

		foreach ($arrModuleRename as $key=>$value) {
			$objModule =  Modules::model()->findByAttributes(array('category'=>'shipping','module'=>$key));
			if ($objModule instanceof Modules) {
				$objModule->module = $value;
				$objModule->save();
			}
		}

		$arrModuleRename = array(
			'xlsws_class_sidebar'     =>'wsbsidebar',
			'sidebar_wishlist'        =>'wsbwishlist',
			'sidebar_order_lookup' =>'wsborderlookup'
		);

		foreach ($arrModuleRename as $key=>$value) {
			$objModule =  Modules::model()->findByAttributes(array('category'=>'sidebar','module'=>$key));
			if ($objModule instanceof Modules) {
				$objModule->module = $value;
				$objModule->save();
			}
		}

		//fix for bad 2.5.2 configuration string
		$objModule =  Modules::model()->findByAttributes(array('module'=>'storepickup'));
		if ($objModule instanceof Modules)
		{
			$conf = $objModule->configuration;
			$conf = str_replace('s:12"Store Pickup"','s:12:"Store Pickup"',$conf);
			$objModule->configuration = $conf;
			$objModule->save();
		}


		_dbx("INSERT INTO `xlsws_modules` (`active`, `module`, `category`, `version`, `name`, `sort_order`, `configuration`, `modified`, `created`)
VALUES	(0, 'wsmailchimp', 'CEventCustomer', 1, 'MailChimp', 1, 'a:2:{s:7:\"api_key\";s:0:\"\";s:4:\"list\";s:9:\"Web Store\";}', CURRENT_TIMESTAMP, NULL);");


		$arrKeys = array('SEO_PRODUCT_TITLE','SEO_PRODUCT_DESCRIPTION','SEO_CATEGORY_TITLE','SEO_CUSTOMPAGE_TITLE','EMAIL_SUBJECT_CART','EMAIL_SUBJECT_WISHLIST','EMAIL_SUBJECT_CUSTOMER','EMAIL_SUBJECT_OWNER');
		foreach ($arrKeys as $key)
		{

			$obj = Configuration::LoadByKey($key);
			$obj->key_value = str_replace("%storename%","{storename}",$obj->key_value);
			$obj->key_value = str_replace("%name%","{name}",$obj->key_value);
			$obj->key_value = str_replace("%description%","{description}",$obj->key_value);
			$obj->key_value = str_replace("%shortdescription%","{shortdescription}",$obj->key_value);
			$obj->key_value = str_replace("%longdescription%","{longdescription}",$obj->key_value);
			$obj->key_value = str_replace("%shortdescription%","{shortdescription}",$obj->key_value);
			$obj->key_value = str_replace("%keyword1%","",$obj->key_value);
			$obj->key_value = str_replace("%keyword2%","",$obj->key_value);
			$obj->key_value = str_replace("%keyword3%","",$obj->key_value);
			$obj->key_value = str_replace("%price%","{price}",$obj->key_value);
			$obj->key_value = str_replace("%family%","{family}",$obj->key_value);
			$obj->key_value = str_replace("%class%","{class}",$obj->key_value);
			$obj->key_value = str_replace("%crumbtrail%","{crumbtrail}",$obj->key_value);
			$obj->key_value = str_replace("%rcrumbtrail%","{rcrumbtrail}",$obj->key_value);
			$obj->key_value = str_replace("%orderid%","{orderid}",$obj->key_value);
			$obj->key_value = str_replace("%customername%","{customername}",$obj->key_value);
			$obj->save();

		}
		$obj = Configuration::LoadByKey('LANG_CODE');
		$obj->key_value = strtolower($obj->key_value);
		$obj->save();
		$obj = Configuration::LoadByKey('LANGUAGES');
		$obj->key_value = strtolower($obj->key_value);
		$obj->save();



		return array('result'=>"success",'makeline'=>9,'tag'=>'Installing Google categories (group 1 of 6)','total'=>50);

	}


	/**
	 * 9-13 load amazon, Convert our web keywords into new tags table
	 */
	protected function actionConvertGoogle()
	{
		$ct=0;

		//Load google categories
		_dbx('SET FOREIGN_KEY_CHECKS=0');
		if ($this->online==9)
			Yii::app()->db->createCommand()->truncateTable(CategoryGoogle::model()->tableName());
		$file = fopen(YiiBase::getPathOfAlias('ext.wsgoogle.assets')."/googlecategories.txt", "r");
		if ($file)
			while(!feof($file)) {
				$strLine = fgets($file);

				$ct++;
				if (
					($ct>=1 && $ct<=1000 && $this->online==9) ||
					($ct>=1001 && $ct<=2000 && $this->online==10) ||
					($ct>=2001 && $ct<=3000 && $this->online==11) ||
					($ct>=3001 && $ct<=4000 && $this->online==12) ||
					($ct>=4001 && $ct<=5000 && $this->online==13)
				)
				{
					$objGC = new CategoryGoogle();
					$objGC->name0 = trim($strLine);
					$arrItems = array_filter(explode(" > ",$strLine));
					if(isset($arrItems[0]))    $objGC->name1=trim($arrItems[0]);
					if(isset($arrItems[1]))    $objGC->name2=trim($arrItems[1]);
					if(isset($arrItems[2]))    $objGC->name3=trim($arrItems[2]);
					if(isset($arrItems[3]))    $objGC->name4=trim($arrItems[3]);
					if(isset($arrItems[4]))    $objGC->name5=trim($arrItems[4]);
					if(isset($arrItems[5]))    $objGC->name6=trim($arrItems[5]);
					if(isset($arrItems[6]))    $objGC->name7=trim($arrItems[6]);
					if(isset($arrItems[7]))    $objGC->name8=trim($arrItems[7]);
					if(isset($arrItems[8]))    $objGC->name9=trim($arrItems[8]);

					$objGC->save();
				}
			}
		fclose($file);

		if ($this->online==13)
		{
			for ($x=1; $x<=9; $x++)
				_dbx("update xlsws_category_google set `name".$x."`=null where `name".$x."`=''");

			CategoryGoogle::model()->deleteAllByAttributes(array('name1'=>null));


			//Import old google categories to new
			try {
				$dbC = Yii::app()->db->createCommand();
				$dbC->setFetchMode(PDO::FETCH_OBJ);//fetch each row as Object

				$dbC->select()->from('xlsws_category')->where('google_id IS NOT NULL')->order('id');

				foreach ($dbC->queryAll() as $row) {
					_dbx("delete from xlsws_category_integration where module='google' AND foreign_id=".$row->google_id." and category_id=".$row->id);
					$obj = new CategoryIntegration();
					$obj->category_id = $row->id;
					$obj->module = "google";
					$obj->foreign_id = $row->google_id;
					$obj->extra = $row->google_extra;
					$obj->save();
				}
				_dbx("alter table xlsws_category drop google_id");
				_dbx("alter table xlsws_category drop google_extra");
			}
			catch (Exception $e)
			{

			}


		}
		_dbx('SET FOREIGN_KEY_CHECKS=1');

		return array('result'=>"success",'makeline'=>($this->online+1),'tag'=>'Installing Google categories (group '.($this->online-7)." of 6)",'total'=>50);

	}


	/**
	 * 14: Convert the keyword columns into the new tags table
	 * @return string
	 */
	protected function actionConvertKeywordsToTags()
	{




		$sql = "insert ignore into xlsws_tags (tag) select distinct web_keyword1 from xlsws_product where coalesce(web_keyword1,'')<>'' order by web_keyword1";
		Yii::app()->db->createCommand($sql)->execute();

		$sql = "insert ignore into xlsws_tags (tag) select distinct web_keyword2 from xlsws_product where coalesce(web_keyword2,'')<>'' order by web_keyword2";
		Yii::app()->db->createCommand($sql)->execute();

		$sql = "insert ignore into xlsws_tags (tag) select distinct web_keyword2 from xlsws_product where coalesce(web_keyword3,'')<>'' order by web_keyword3";
		Yii::app()->db->createCommand($sql)->execute();

		Yii::app()->db->createCommand("delete from xlsws_tags where tag is null")->execute();
		Yii::app()->db->createCommand("delete from xlsws_tags where tag=''")->execute();

		Yii::app()->db->createCommand("insert into xlsws_product_tags (product_id,tag_id) select a.id,b.id from xlsws_product as a left join xlsws_tags as b on a.web_keyword1=b.tag where coalesce(web_keyword1,'') <> '' and b.id is not null")->execute();

		Yii::app()->db->createCommand("insert into xlsws_product_tags (product_id,tag_id) select a.id,b.id from xlsws_product as a left join xlsws_tags as b on a.web_keyword2=b.tag where coalesce(web_keyword2,'') <> '' and b.id is not null")->execute();

		Yii::app()->db->createCommand("insert into xlsws_product_tags (product_id,tag_id) select a.id,b.id from xlsws_product as a left join xlsws_tags as b on a.web_keyword3=b.tag where coalesce(web_keyword3,'') <> '' and b.id is not null")->execute();


		//The process above may create duplicates, so we need to remove those and recreate them
		$sql = "select product_id,
			         tag_id,
			         count(*)
			from     xlsws_product_tags
			group by product_id,
			         tag_id
			having   count(*) > 1";
		$results=Yii::app()->db->createCommand($sql)->queryAll();
		foreach($results AS $result) {
			_dbx("delete from xlsws_product_tags where product_id=".$result['product_id']." and tag_id=".$result['tag_id']);
			_dbx("insert into xlsws_product_tags set product_id=".$result['product_id'].", tag_id=".$result['tag_id']);
		}


		//Remove orphaned wish list purchase records (unfortunately lost pre 3.0 but not much we can do except clean it up)
		$results=Yii::app()->db->createCommand('select a.id as id from xlsws_wishlist_item as a left join xlsws_cart_item as b on a.cart_item_id=b.id where cart_item_id is not null AND cart_id is null')->queryAll();
		foreach($results AS $result)
			_dbx("update xlsws_wishlist_item set cart_item_id=null where id =".$result['id']);

		return array('result'=>"success",'makeline'=>15,'total'=>50);

	}

	/**
	 * 25: Convert families into Ids and attach
	 * @return string
	 */
	protected function actionConvertFamilies()
	{
		//families
		$sql = "insert ignore into xlsws_family (family) select distinct family from xlsws_product where coalesce(family,'')<>'' order by family";
		Yii::app()->db->createCommand($sql)->execute();

		_dbx("update xlsws_product as a set family_id=(select id from xlsws_family as b where b.family=a.family)");

		Family::ConvertSEO();

		$objFamilies = Family::model()->findAll();
		foreach ($objFamilies as $obj)
			$obj->UpdateChildCount();

		return array('result'=>"success",'makeline'=>16,'total'=>50);
	}

	/**
	 * 16 Convert classes into Ids and attach
	 * @return string
	 */
	public function actionConvertClasses()
	{
		//class
		$sql = "insert ignore into xlsws_classes (class_name) select distinct class_name from xlsws_product where coalesce(class_name,'')<>'' order by class_name";
		Yii::app()->db->createCommand($sql)->execute();

		_dbx("update xlsws_product as a set class_id=(select id from xlsws_classes as b where b.class_name=a.class_name)");

		Classes::ConvertSEO();

		return array('result'=>"success",'makeline'=>18,'total'=>50);
	}



	/**
	 * 18 Change destination tables and map to country/state ids
	 */
	protected function actionConvertDestinationTables()
	{
		//Convert Wish List items to new formats
		//Ship to me
		//Ship to buyer
		//Keep in store

		$objDestinations = Destination::model()->findAll();
		foreach ($objDestinations as $objDestination)
		{
			if ($objDestination->country=="*") $objDestination->country=null;
			else {
				$objC = Country::LoadByCode($objDestination->country);
				$objDestination->country=$objC->id;
			}

			if ($objDestination->state=="*") $objDestination->state=null;
			else
			{
				$objS = State::LoadByCode($objDestination->state,$objDestination->country);
				$objDestination->state=$objS->id;
			}

			if (!$objDestination->save())
				return print_r($objDestination->getErrors());
		}

		//Need to map destinations to IDs before doing this
		_dbx("update `xlsws_destination` set country=null where country=0;");
		_dbx("update `xlsws_destination` set state=null where state=0;");
		_dbx("ALTER TABLE `xlsws_destination` CHANGE `country` `country` INT(11)  UNSIGNED  NULL  DEFAULT NULL;");
		_dbx("ALTER TABLE `xlsws_destination` CHANGE `state` `state` INT(11)  UNSIGNED  NULL  DEFAULT NULL;");
		_dbx("ALTER TABLE `xlsws_destination` CHANGE `taxcode` `taxcode` INT(11)  UNSIGNED  NULL  DEFAULT NULL;");
		_dbx("ALTER TABLE `xlsws_destination` ADD FOREIGN KEY (`state`) REFERENCES `xlsws_state` (`id`);");
		_dbx("ALTER TABLE `xlsws_destination` ADD FOREIGN KEY (`country`) REFERENCES `xlsws_country` (`id`);");
		_dbx("ALTER TABLE `xlsws_destination` ADD FOREIGN KEY (`taxcode`) REFERENCES `xlsws_tax_code` (`lsid`);");
		_dbx("ALTER TABLE `xlsws_category` CHANGE `custom_page` `custom_page` INT(11)  UNSIGNED  NULL  DEFAULT NULL;");
		_dbx("UPDATE `xlsws_category` set `custom_page`=null where `custom_page`=0;");
		_dbx("ALTER TABLE `xlsws_category` ADD FOREIGN KEY (`custom_page`) REFERENCES `xlsws_custom_page` (`id`);");
		_dbx("ALTER TABLE `xlsws_country` DROP `code_a3`;");
		_dbx("update `xlsws_shipping_tiers` set `class_name`='tieredshipping';");

		return array('result'=>"success",'makeline'=>19,'tag'=>'Applying database schema changes','total'=>50);
	}

	/**
	 * 19 Drop fields no longer needed
	 */
	protected function actionDropCartfields1()
	{

		$sqlstrings = "ALTER TABLE `xlsws_cart` DROP `first_name`;
		ALTER TABLE `xlsws_cart` DROP `last_name`;
		ALTER TABLE `xlsws_cart` DROP `address_bill`;
		ALTER TABLE `xlsws_cart` DROP `address_ship`;
		ALTER TABLE `xlsws_cart` DROP `ship_firstname`;
		ALTER TABLE `xlsws_cart` DROP `ship_lastname`;
		ALTER TABLE `xlsws_cart` DROP `ship_company`;
		ALTER TABLE `xlsws_cart` DROP `ship_address1`;";

		$arrSql = explode(";",$sqlstrings);

		foreach ($arrSql as $strSql)
			if (!empty($strSql)) {
				//Yii::log("running statement ".$strSql, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				Yii::app()->db->createCommand($strSql)->execute();
			}



		return array('result'=>"success",'makeline'=>20,'tag'=>'Creating SEO-friendly URLs','total'=>50);

	}
	/**
	 * 20 Drop fields no longer needed
	 */
	protected function actionDropCartfields2()
	{

		$sqlstrings = "ALTER TABLE `xlsws_cart` DROP `ship_address2`;
		ALTER TABLE `xlsws_cart` DROP `ship_city`;
		ALTER TABLE `xlsws_cart` DROP `ship_zip`;
		ALTER TABLE `xlsws_cart` DROP `ship_state`;";

		$arrSql = explode(";",$sqlstrings);

		foreach ($arrSql as $strSql)
			if (!empty($strSql)) {
				//Yii::log("running statement ".$strSql, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				Yii::app()->db->createCommand($strSql)->execute();
			}



		return array('result'=>"success",'makeline'=>21,'tag'=>'Creating SEO-friendly URLs','total'=>50);

	}
	/**
	 * 21 Drop fields no longer needed
	 */
	protected function actionDropCartfields3()
	{

		$sqlstrings = "
		ALTER TABLE `xlsws_cart` DROP `ship_country`;
		ALTER TABLE `xlsws_cart` DROP `ship_phone`;
		ALTER TABLE `xlsws_cart` DROP `zipcode`;
		ALTER TABLE `xlsws_cart` DROP `contact`;
		ALTER TABLE `xlsws_cart` DROP `company`;";

		$arrSql = explode(";",$sqlstrings);

		foreach ($arrSql as $strSql)
			if (!empty($strSql)) {
				//Yii::log("running statement ".$strSql, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				Yii::app()->db->createCommand($strSql)->execute();
			}



		return array('result'=>"success",'makeline'=>25,'tag'=>'Creating SEO-friendly URLs','total'=>50);

	}


	/**
	 * 25 Create request_urls and export any photos (from pre 2.5 installs)
	 */
	public function actionConvertProductSEO()
	{
		//First, run our request_url creation if needed
		Product::ConvertSEO(-1);
		Category::ConvertSEO();

		$matches=Yii::app()->db->createCommand('SELECT count(*) FROM '.Product::model()->tableName().' WHERE request_url IS NULL AND title is not null')->queryScalar();
		if ($matches>0)
			return array('result'=>"success",'makeline'=>25,'tag'=>'Creating SEO-friendly URLs '.$matches.' remaining','total'=>50);
		else
		{
			//Getting ready for photo convert, drop any orphaned images with blobs
			_dbx("delete a from xlsws_images as a left join xlsws_product as b on a.id=b.image_id where image_path is null and a.id=a.parent and b.id is null;");
			return array('result'=>"success",'makeline'=>32,'tag'=>'Installing Amazon categories (group 1 of 14)','total'=>50);

		}


	}



	/**
	 * 32-44 load amazon, 24 files
	 * @return string
	 */
	protected function actionImportAmazon()
	{

		$arr = array();
		$d = dir(YiiBase::getPathOfAlias('ext.wsamazon.assets.csv'));
		while (false!== ($filename = $d->read())) {
			if (substr($filename,-4)==".csv") {
				$arr[] = $filename;
			}
		}
		$d->close();
		sort($arr);

		_dbx('SET FOREIGN_KEY_CHECKS=0');
		if ($this->online==32)
			Yii::app()->db->createCommand()->truncateTable(CategoryAmazon::model()->tableName());

		$onBlock = 2*($this->online-32);  //online is 32 through 44
		for($x=0; $x<=1; $x++)
		{
			if(isset($arr[$onBlock+$x]))
			{
			$filename = $arr[$onBlock+$x];

			$csvData = file_get_contents(YiiBase::getPathOfAlias('ext.wsamazon.assets.csv')."/".$filename);
			$csvDataa = explode(chr(13),$csvData);
			$arrData = array();
			foreach ($csvDataa as $item)
				$arrData[]= str_getcsv($item, ",",'"');
			array_shift($arrData);


			foreach($arrData as $data)
			{
				$objGC = new CategoryAmazon();
				$objGC->name0 = trim($data[1]);
				$objGC->item_type = trim($data[2]);
				$arrItems = array_filter(explode("/",$data[1]));
				if(isset($arrItems[0]))    $objGC->name1=trim($arrItems[0]);
				if(isset($arrItems[1]))    $objGC->name2=trim($arrItems[1]);
				if(isset($arrItems[2]))    $objGC->name3=trim($arrItems[2]);
				if(isset($arrItems[3]))    $objGC->name4=trim($arrItems[3]);
				if(isset($arrItems[4]))    $objGC->name5=trim($arrItems[4]);
				if(isset($arrItems[5]))    $objGC->name6=trim($arrItems[5]);
				if(isset($arrItems[6]))    $objGC->name7=trim($arrItems[6]);
				if(isset($arrItems[7]))    $objGC->name8=trim($arrItems[7]);
				if(isset($arrItems[8]))    $objGC->name9=trim($arrItems[8]);

				$objGC->save();


			}}
		}
		$this->online++;

		if ($this->online-31 == 14)
			return array('result'=>"success",'makeline'=>$this->online,'tag'=>'Removing unused database fields 1', 'total'=>50);
			else
				return array('result'=>"success",'makeline'=>$this->online,'tag'=>'Installing Amazon categories (group '.($this->online-31)." of 14)", 'total'=>50);
	}


	/**
	 * 45 Drop unused customer fields
	 * @return string
	 */
	protected function actionDropcustomerfields()
	{

		$sqlstrings =
		"ALTER TABLE `xlsws_customer` DROP `address1_1`;
		ALTER TABLE `xlsws_customer` DROP `address1_2`;
		ALTER TABLE `xlsws_customer` DROP `address2_1`;
		ALTER TABLE `xlsws_customer` DROP `address_2_2`;
		ALTER TABLE `xlsws_customer` DROP `city1`;
		ALTER TABLE `xlsws_customer` DROP `city2`;
		ALTER TABLE `xlsws_customer` DROP `country1`;
		ALTER TABLE `xlsws_customer` DROP `country2`;
		ALTER TABLE `xlsws_customer` DROP `homepage`;
		ALTER TABLE `xlsws_customer` DROP `phone1`;
		ALTER TABLE `xlsws_customer` DROP `phonetype1`;
		ALTER TABLE `xlsws_customer` DROP `phone2`;
		ALTER TABLE `xlsws_customer` DROP `phonetype2`;
		ALTER TABLE `xlsws_customer` DROP `phone3`;
		ALTER TABLE `xlsws_customer` DROP `phonetype3`;
		ALTER TABLE `xlsws_customer` DROP `phone4`;
		ALTER TABLE `xlsws_customer` DROP `phonetype4`;
		ALTER TABLE `xlsws_customer` DROP `state1`;
		ALTER TABLE `xlsws_customer` DROP `state2`;
		ALTER TABLE `xlsws_customer` DROP `zip1`;
		ALTER TABLE `xlsws_customer` DROP `zip2`;
		ALTER TABLE `xlsws_customer` DROP `mainname`;";

		$arrSql = explode(";",$sqlstrings);

		foreach ($arrSql as $strSql)
			if (!empty($strSql))
				Yii::app()->db->createCommand($strSql)->execute();



		return array('result'=>"success",'makeline'=>46,'tag'=>'Removing unused database fields 2','total'=>50);

	}



	/**
	 * 46 Drop product fields
	 * @return string
	 */
	protected function actionDropProductFields()
	{
		$elements = array('full_name','phone','shipping_method','shipping_module','shipping_data',
			'shipping_cost','shipping_sell','payment_method','payment_module','payment_data','payment_amount',
			'datetime_posted','tracking_number','email','cost_total','sell_total');
		foreach ($elements as $element)
		{
			$res = Yii::app()->db->createCommand("SHOW COLUMNS FROM xlsws_cart WHERE Field='".$element."'")->execute();
			if($res)
			{
				Yii::app()->db->createCommand("ALTER TABLE `xlsws_cart` DROP `".$element."`")->execute();
				return array('result'=>"success",'makeline'=>46,'tag'=>'Removed unused database field '.$element,'total'=>50);

			}
		}

		$elements = array('family','class_name','web_keyword1','web_keyword2','web_keyword3','meta_desc','meta_keyword');
		foreach ($elements as $element)
		{
			$res = Yii::app()->db->createCommand("SHOW COLUMNS FROM xlsws_product WHERE Field='".$element."'")->execute();
			if($res)
			{
				Yii::app()->db->createCommand("ALTER TABLE `xlsws_product` DROP `".$element."`")->execute();
				return array('result'=>"success",'makeline'=>46,'tag'=>'Removed unused database field '.$element,'total'=>50);

			}
		}


		//If we're done a droppin', move on...
		Yii::app()->db->createCommand(
			"ALTER TABLE `xlsws_wishlist_item` DROP `registry_status`;")->execute();
		Yii::app()->db->createCommand(
			"ALTER TABLE `xlsws_wishlist_item` ADD CONSTRAINT `xlsws_wishlist_item_ibfk_1` FOREIGN KEY (`registry_id`) REFERENCES `xlsws_wishlist` (`id`);")->execute();

		return array('result'=>"success",'makeline'=>47,'tag'=>'Calculating available inventory','total'=>50);

	}

	/**
	 * 47 Create request_urls and export any photos (from pre 2.5 installs)
	 */
	public function actionCalculateInventory()
	{
		//First, run our request_url creation if needed
		$matches = Product::RecalculateInventory();

		if ($matches>0)
			return array('result'=>"success",'makeline'=>47,'tag'=>'Calculating available inventory '.$matches.' products remaining','total'=>50);
		else
		{
			return array('result'=>"success",'makeline'=>48,'tag'=>'Final cleanup','total'=>50);

		}


	}




	/**
	 * 48 Cleanup details, config options that have changed, NULL where we had 0's, etc.
	 * @return string
	 */
	protected function actionUpdateConfiguration()
	{
		//Migrate our header image to the new folder
		$objConfig = Configuration::LoadByKey('HEADER_IMAGE');
		$objConfig->key_value = str_replace("/photos/","/images/header/",$objConfig->key_value);
		$objConfig->save();

		$objConfig = Configuration::LoadByKey('PRODUCT_SORT_FIELD');
		$objConfig->key_value = str_replace("Name","title",$objConfig->key_value);
		$objConfig->key_value = str_replace("Rowid","id",$objConfig->key_value);
		$objConfig->key_value = str_replace("Modified","modified",$objConfig->key_value);
		$objConfig->key_value = str_replace("Code","code",$objConfig->key_value);
		$objConfig->key_value = str_replace("InventoryTotal","inventory_total",$objConfig->key_value);
		$objConfig->key_value = str_replace("DescriptionShort","description_short",$objConfig->key_value);
		$objConfig->key_value = str_replace("WebKeyword1","title",$objConfig->key_value);
		$objConfig->key_value = str_replace("WebKeyword2","title",$objConfig->key_value);
		$objConfig->key_value = str_replace("WebKeyword3","title",$objConfig->key_value);
		$objConfig->save();

		//What we're gonna do right here is go back.... way back...
		_xls_set_conf('DATABASE_SCHEMA_VERSION',0);
		return array('result'=>"success",'makeline'=>49,'tag'=>'Final cleanup','total'=>50);
	}

	protected function actionApplyLatestChanges()
	{
		//Now the live changes from Web Store world headquarters take over
		$myModule = Yii::app()->getModule('admin');

		Yii::app()->runController($myModule->id . '/upgrade/databaseinstall');

		//We don't even have to return our own JSON status because the actionDatabaseUpgrade() does that for us.
		return null;



	}




}