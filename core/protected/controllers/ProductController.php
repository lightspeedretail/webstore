<?php
/**
 * Product controller
 *
 * Used for single product display
 *
 * @category   Controller
 * @package    Myaccount
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2012-12-06

 */
class ProductController extends Controller
{
	public $layout='//layouts/column2';
	public $objProduct;

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
	 * Since we should always have a product id to display, if we hit the index itself, just throw 404
	 * @throws CHttpException
	 */
	public function actionIndex()
	{
		throw new CHttpException(404,'The requested page does not exist.');
	}

	/**
	 * Default action is product display. This action requires the product id to be passed.
	 * If we do not receive this id, redirect back to the home page since something
	 * went wrong.
	 */
	public function actionView() {


		$id = Yii::app()->getRequest()->getQuery('id');
		if (empty($id))
			throw new CHttpException(404,'The requested page does not exist.');

		//Load a product and display the information
		$model = $this->objProduct = Product::model()->findByPk($id);

		if (!$model)
			throw new CHttpException(404,'The requested page does not exist.');

		if(!$model->IsDisplayable)
			throw new CHttpException(404,'The requested page does not exist.');

		//If our request_url (based on description) has changed, redirect properly
		if ($model->request_url != Yii::app()->getRequest()->getQuery('name'))
			_xls_301($model->Link);

		//Set breadcrumbs
		$this->breadcrumbs = $model->Breadcrumbs;
		$this->pageImageUrl = $model->SmallImageAbsolute;


		$objWishlistAddForm = new WishlistAddForm();
		$objWishlistAddForm->id = $this->objProduct->id;
		$objWishlistAddForm->qty = 1;
		$objWishlistAddForm->lists = $objWishlistAddForm->getLists();
		$objWishlistAddForm->gift_code = Wishlist::LoadFirstCode();


		$this->setPageTitle($model->PageTitle);
		$this->pageDescription = $model->PageDescription;
		$this->CanonicalUrl = $model->AbsoluteLink;
		$this->returnUrl = $this->CanonicalUrl;
		$model->intQty=1;

		//Raise any events first
		$objEvent = new CEventProduct(get_class($this),'onActionProductView',$model);
		_xls_raise_events('CEventProduct',$objEvent);

		$this->render('index',array(
			'model'=>$model,
			'WishlistAddForm'=>$objWishlistAddForm,
		));

	}


	/**
	 * Ajax responder, when choosing a size from a matrix dropdown on the product display page, get available colors
	 */
	public function actionGetColors()
	{

		if(Yii::app()->request->isAjaxRequest) {
			$id = Yii::app()->getRequest()->getParam('id');
			$strSize= Yii::app()->getRequest()->getParam('product_size');

			$model = Product::model()->findByPk($id);

			//ToDo: Solve our issue about size sorting
			$data= $model->getColors($strSize);

			$arrReturn['product_colors'] = '';
			foreach($data as $value=>$name)
				$arrReturn['product_colors'] .= CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);

			echo json_encode($arrReturn);

		}
	}


	/**
	 * Ajax responder, when choosing a color from the dropdown, get product details to update page
	 */
	public function actionGetmatrixproduct()
	{
		if(Yii::app()->request->isAjaxRequest) {

			$id = Yii::app()->getRequest()->getParam('id');
			$strSize= Yii::app()->getRequest()->getParam('product_size');
			$strColor= Yii::app()->getRequest()->getParam('product_color');

			$objProduct = Product::model()->findByAttributes(array('parent'=>$id,'product_size'=>$strSize,'product_color'=>$strColor));


			$arrReturn['status']='success';
			$arrReturn['id']=$objProduct->id;
			$arrReturn['FormattedPrice']=$objProduct->Price;
			$arrReturn['FormattedRegularPrice']=$objProduct->SlashedPrice;
			$arrReturn['image_id']=CHtml::image(Images::GetLink($objProduct->image_id,ImagesType::pdetail));
			$arrReturn['code']=$objProduct->code;
			$arrReturn['title']=$objProduct->Title;
			$arrReturn['InventoryDisplay']=$objProduct->InventoryDisplay;

			if ($objProduct->WebLongDescription)
				$arrReturn['description_long']=$objProduct->WebLongDescription;
				else
					$arrReturn['description_long']=$objProduct->parent0->WebLongDescription;

			if ($objProduct->description_short)
				$arrReturn['description_short']=$objProduct->WebShortDescription;
				else
					$arrReturn['description_short']=$objProduct->parent0->WebShortDescription;

			Yii::app()->clientscript->scriptMap['jquery.js'] = false;
			$arrReturn['photos'] = $this->renderPartial('/product/_photos', array('model'=>$objProduct), true,false);

			echo json_encode($arrReturn);
		}

	}

}