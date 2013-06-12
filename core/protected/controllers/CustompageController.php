<?php
/**
 * Default controller, used in absence of any other criteria
 *
 * @category   Controller
 * @package    Custompage
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2013-05-14

 */
class CustompageController extends Controller
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
		$model = CustomPage::LoadByRequestUrl(Yii::app()->getRequest()->getQuery('id'));
		if (!($model instanceof CustomPage))
			_xls_404();

		$this->pageTitle=$model->PageTitle;
		$this->pageDescription=$model->meta_description;
		$this->pageImageUrl = '';
		$this->breadcrumbs = array(
			$model->title=>$model->RequestUrl,
		);

		$this->CanonicalUrl = $model->CanonicalUrl;
		$this->render('index',array('model'=>$model,'objCustomPage'=>$model));



	}


	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{

		$model = CustomPage::LoadByRequestUrl("contact-us");
		$this->pageTitle=$model->PageTitle;
		$this->pageDescription=$model->meta_description;
		$this->breadcrumbs = array(
			$model->title=>$model->RequestUrl,
		);

		$ContactForm=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$ContactForm->attributes=$_POST['ContactForm'];
			if($ContactForm->validate())
			{

				$objEmail = new EmailQueue;

				if (!Yii::app()->user->isGuest) {
					$objCustomer = Customer::GetCurrent();
					$objEmail->customer_id = $objCustomer->id;
					$ContactForm->fromName = $objCustomer->mainname;
					$ContactForm->fromEmail = $objCustomer->email;
				}

				$strHtmlBody =$this->renderPartial('/mail/_contactform', array('model'=>$ContactForm), true);
				$strSubject = Yii::t('email','Contact Us:').$ContactForm->contactSubject;
				$objEmail->htmlbody = $strHtmlBody;
				$objEmail->subject = $strSubject;
				$orderEmail = _xls_get_conf('ORDER_FROM','');
				$objEmail->to = empty($orderEmail) ? _xls_get_conf('EMAIL_FROM') : $orderEmail;

				$objHtml = new HtmlToText;

				//If we get back false, it means conversion failed which 99.9% of the time means improper HTML.
				$strPlain = $objHtml->convert_html_to_text($strHtmlBody);
				if ($strPlain !== false)
					$objEmail->plainbody = $strPlain;

				if (!$objEmail->save()) {
					Yii::log("Error creating email ".print_r($objEmail,true)." ".
						print_r($objEmail->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				}

				Yii::app()->user->setFlash('success',
					Yii::t('email','Message sent. Thank you for contacting us. We will respond to you as soon as possible.'));

				//Attempt to use an AJAX call to send the email. If it doesn't work, the Download process will catch it anyway.
				$jsScript = "$.ajax({url:\"".CController::createUrl('site/sendemail',array("id"=>$objEmail->id))."\"});";
				Yii::app()->clientScript->registerScript(
					'sendemail',
					$jsScript,
					CClientScript::POS_READY
				);

			} else {
				Yii::app()->user->setFlash('error',Yii::t('cart','Please check your form for errors.'));
				if (YII_DEBUG)
					Yii::app()->user->setFlash('error',print_r($ContactForm->getErrors(),true));
			}
		}

		if (!Yii::app()->user->isGuest){
			$objCustomer = Customer::GetCurrent();
			$ContactForm->fromName = $objCustomer->mainname;
			$ContactForm->fromEmail = $objCustomer->email;

		}

		$this->CanonicalUrl = $model->CanonicalUrl;

		$this->render('contact',array('ContactForm'=>$ContactForm,'model'=>$model));
	}



}