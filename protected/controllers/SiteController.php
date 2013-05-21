<?php
/**
 * SRO Controller, used for SRO display
 *
 * @category   Controller
 * @package    SRO
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2013-05-14

 */
class SiteController extends Controller
{
	public $layout='//layouts/column2';


	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

		$homePage = _xls_get_conf('HOME_PAGE','*products');
		switch ($homePage)
		{
			case "*index":
				$this->render("index");
				break;

			case "*products":

				$criteria = new CDbCriteria();

				if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD') == Product::InventoryMakeDisappear)
					$criteria->addCondition('current=1 AND web=1 AND parent IS NULL AND inventory_avail>0');
				else
					$criteria->condition = 'current=1 AND web=1 AND parent IS NULL';

				if (Product::HasFeatured())
					$criteria->addCondition('featured=1');
				$criteria->order = _xls_get_sort_order();

				$item_count = Product::model()->count($criteria);

				$pages = new CPagination($item_count);
				$pages->setPageSize(Yii::app()->params['listPerPage']);
				$pages->applyLimit($criteria);

				$model = Product::model()->findAll($criteria);

				$model = $this->createBookends($model);

				//We leech off the grid file from search
				if ( Yii::app()->theme && file_exists('webroot.themes.'.Yii::app()->theme->name.'.search.grid'))
					$gridFile = 'webroot.themes.'.Yii::app()->theme->name.'.search.grid';
				else $gridFile = "application.views.search.grid";

				$this->render($gridFile,array(
					'model'=> $model, // must be the same as $item_count
					'item_count'=>$item_count,
					'page_size'=>Yii::app()->params['listPerPage'],
					'items_count'=>$item_count,
					'pages'=>$pages,
				));

				break;

			default:
				//Custom Page
				$objCustomPage = CustomPage::LoadByKey($homePage);
				if (!($objCustomPage instanceof CustomPage))
					_xls_404();

				$this->pageTitle=$objCustomPage->PageTitle;
				$this->pageDescription=$objCustomPage->meta_description;
				$this->pageImageUrl = '';
				$this->breadcrumbs = array(
					$objCustomPage->title=>$objCustomPage->RequestUrl,
				);

				$dataProvider = $objCustomPage->GetSliderDataProvider();

				$this->CanonicalUrl = $objCustomPage->CanonicalUrl;
				$this->render('/custompage/index',array('objCustomPage'=>$objCustomPage,'dataProvider'=>$dataProvider));

		}

	}

	/**
	 * Sitemap
	 */
	public function actionMap()
	{

		$tdata = Category::GetTree(true);
		$arrCustomPages = CustomPage::model()->activetabs()->findAll();
		$arrProducts = array();

		$this->render('map',array('tdata'=>$tdata,'arrProducts'=>$arrProducts,'arrCustomPages'=>$arrCustomPages));

	}



	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->layout='//layouts/errorlayout';
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}



	/**
	 * Process login from the popup Login box
	 */
	public function actionLogin()
	{
		$model=new LoginForm();

		$response_array = array();

		// collect user input data
		if(isset($_POST['LoginForm']))
		{

			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				$response_array['status'] = 'success';
			}
			else {
				$response_array['status'] = 'error';
				$response_array['errormsg'] = _xls_convert_errors($model->getErrors());
			}

			echo json_encode($response_array);
		}

	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		if(_xls_facebook_login())
			Yii::app()->facebook->destroySession();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Displays the category grid of top level categories to begin browsing
	 */
	public function actionCategory() {

		$id = Yii::app()->getRequest()->getQuery('id');

		//If we're not passing a specific category id, reroute to main display
		if (!empty($id))
			$this->redirect(array('search/index?c='.$id));

		$criteria = new CDbCriteria();
		$criteria->alias = 'Category';
		$criteria->condition = 'parent = 0';
		$criteria->order = 'menu_position';

		$item_count = Category::model()->count($criteria);

		$pages = new CPagination($item_count);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria);  // the trick is here!

		$model = Category::model()->findAll($criteria);
		foreach($model as $m) {
			$arrModel[] = $m;
		}

		$this->render('category',array(
				'arrmodel'=> $arrModel, // must be the same as $item_count
				'item_count'=>$item_count,
				'page_size'=>Yii::app()->params['listPerPage'],
				'items_count'=>$item_count,
				'pages'=>$pages,
			));

	}

	public function actionForgotpassword()
	{
		$model=new LoginForm();
	
		if(isset($_POST['LoginForm']))
		{

			$model->attributes=$_POST['LoginForm'];


			$objCustomer = Customer::model()->findByAttributes(array('record_type'=>Customer::REGISTERED,'email'=>$model->email));
			if($objCustomer instanceof Customer)
			{
				if (is_null($objCustomer->password))
				{
					$response_array = array(
						'status'=>"failure",
						'message'=> Yii::t('global','Your email address was found but only as a registered Facebook user. Log in via Facebook.'));
					echo json_encode($response_array);
					return;
				}

				$strHtmlBody =$this->renderPartial('/mail/_forgotpassword',array('model'=>$objCustomer), true);
				$strSubject = Yii::t('global','Password reminder');

				$objEmail = new EmailQueue;

				$objEmail->htmlbody = $strHtmlBody;
				$objEmail->subject = $strSubject;
				$objEmail->to = $objCustomer->email;

				$objEmail->save();


				$response_array = array(
					'status'=>"success",
					'message'=> Yii::t('wishlist','Your password has been sent in email.'),
					'url'=>CController::createUrl('site/sendemail',array("id"=>$objEmail->id)),
					'reload'=>true,
				);

				echo json_encode($response_array);
			} else {
				$response_array = array(
					'status'=>"failure",
					'message'=> Yii::t('global','Your email address was not found in our system.'));
				echo json_encode($response_array);
			}


		} else {
			$response_array = array(
				'status'=>"failure",
				'message'=> Yii::t('global','Please enter your email before clicking this link.'));
				echo json_encode($response_array);
			}
	}

	public function actionSendemail()
	{

		$id = Yii::app()->getRequest()->getQuery('id');

		$headers = array(
			'MIME-Version: 1.0',
			'Content-type: text/html; charset=utf8'
		);

		$objMail = EmailQueue::model()->findByPk($id);
		if ($objMail instanceof EmailQueue) {

			Yii::import("ext.KEmail.KEmail");
			$orderEmail = _xls_get_conf('ORDER_FROM','');

			$blnResult = Yii::app()->email->send(
				empty($orderEmail) ? _xls_get_conf('EMAIL_FROM') : $orderEmail,
				$objMail->to,
				$objMail->subject,
				$objMail->htmlbody,
				$headers);

			if($blnResult)
			{
				$objMail->delete();
				echo json_encode("success");
			}
			else
			{
				$objMail->sent_attempts += 1;
				$objMail->save();
				Yii::log("Sending email failed ID ".$id, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				echo json_encode("failure");
			}
		}

	}



}