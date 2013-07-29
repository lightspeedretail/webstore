<?php

/**
 * Wishlist controller
 *
 * Used for all wish list functionality (formerly known as gift registry)
 *
 * @category   Controller
 * @package    Wishlist
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2012-12-06

 */
class WishlistController extends Controller
{

	public function init()
	{
		//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/custom.js');
		parent::init();
	}
	public function actionIndex()
	{

		//We should only show this option to a logged in user
		if (Yii::app()->user->isGuest)
			throw new CHttpException(404,'The requested page does not exist.');

		$this->breadcrumbs = array(
			'My Wish Lists'=>$this->createUrl("/wishlist"),
		);

		$objWishLists = Wishlist::LoadUserLists();
		$this->render('index',array('objWishlists'=>$objWishLists));

	}

	public function actionCreate()
	{

		$this->breadcrumbs = array(
			'My Wish Lists'=>$this->createUrl("/wishlist"),
			'Create a Wish List'=>$this->createUrl("wishlist/create"),
		);

		//We should only show this option to a logged in user
		if (Yii::app()->user->isGuest)
			throw new CHttpException(404,'The requested page does not exist.');

		$model = new Wishlist();

		// collect user input data
		if(isset($_POST['Wishlist']))
		{

			$model->attributes=$_POST['Wishlist'];
			if($model->validate())
			{

				$model->customer_id = Yii::app()->user->id;
				$model->gift_code = md5(uniqid());
				if (!$model->save())
					Yii::log("Error creating Wish List ".print_r($model->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				$this->redirect($this->createUrl("wishlist/view",array('code'=>$model->gift_code)));


			}

		} else {
			//Set up defaults
			$model->visibility = Wishlist::PERSONALLIST;
			$model->after_purchase = Wishlist::LEAVEINLIST;

		}

		$this->render('create',array('model'=>$model));

	}

	public function actionEdit()
	{

		//We should only show this option to a logged in user
		if (Yii::app()->user->isGuest)
			throw new CHttpException(404, Yii::t('wishlist','You must be logged in to edit Wish Lists.'));

		$strCode = Yii::app()->getRequest()->getParam('code');

		$objWishlist = Wishlist::model()->findByAttributes(array('gift_code'=>$strCode));

		$this->breadcrumbs = array(
			'My Wish Lists'=>$this->createUrl("/wishlist"),
			'Edit Wish List'=>$this->createUrl("wishlist/edit",array('code'=>$strCode)),
		);

		if (!($objWishlist instanceof Wishlist))
			throw new CHttpException(404,Yii::t('wishlist','The requested Wish List does not exist.'));

		if ($objWishlist->visibility == Wishlist::PRIVATELIST && $objWishlist->customer_id != Yii::app()->user->id)
			throw new CHttpException(404,Yii::t('wishlist','The requested Wish List is not viewable.'));

		// collect user input data
		if(isset($_POST['Wishlist']))
		{
			$model = $objWishlist;
			$model->attributes=$_POST['Wishlist'];
			if($model->validate())
			{

				//Did we check our Delete Me box?
				if ($model->deleteMe) {
					Yii::log("User ".Yii::app()->user->fullname." deleted wish list ".$model->registry_name,
						'warning', 'application.'.__CLASS__.".".__FUNCTION__);

					foreach ($objWishlist->wishlistItems as $objWishrow)
					{
						if (!(is_null($objWishrow->cart_item_id)))
							CartItem::model()->updateByPk($objWishrow->cart_item_id,array('wishlist_item'=>null));
						$objWishrow->delete();

					}
					$objWishlist->delete();
				} else
					if (!$model->save())
						Yii::log("Error creating Wish List ".print_r($model->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

				$this->redirect($this->createUrl("/wishlist"));

			}

		} else {
			//Set up defaults
			$model = $objWishlist;

		}

		$this->render('/wishlist/create',array('model'=>$model));

	}

	public function actionView()
	{

		$strCode = Yii::app()->getRequest()->getParam('code');

		$objWishlist = Wishlist::model()->findByAttributes(array('gift_code'=>$strCode));

		if (!($objWishlist instanceof Wishlist))
			throw new CHttpException(404,'The requested wish list does not exist.');

		if ($objWishlist->visibility == Wishlist::PRIVATELIST && $objWishlist->customer_id != Yii::app()->user->id)
			throw new CHttpException(404,'The requested wish list is private.');



		$WishlistShare = new ShareForm();
		$WishlistShare->code = $objWishlist->gift_code;
		$WishlistShare->comment = Yii::t('wishlist','Please check out my Wish List at {url}',
			array('{url}'=>Yii::app()->createAbsoluteUrl('wishlist/view',array('code'=>$WishlistShare->code))));

		$this->breadcrumbs = array(
			'My Wish Lists'=>$this->createUrl("/wishlist"),
			'Edit Wish List: '.$objWishlist->registry_name=>Yii::app()->createUrl('wishlist/view',array('code'=>$WishlistShare->code)),
		);

		//We pass a dummy model of WishListItem to get our edit form ready
		$this->render('/wishlist/view',array(
			'model'=>$objWishlist,
			'formmodel'=>new WishlistEditForm(),
			'WishlistShare'=>$WishlistShare,
		));

	}


	public function actionSearch()
	{

		$model = new WishlistSearch();
		$objWishlists = array();

		$this->breadcrumbs = array(
			Yii::t('global','My Wish Lists')=>$this->createUrl("/wishlist"),
			Yii::t('global','Wish List Search')=>$this->createUrl("wishlist/search"),
		);

		// collect user input data
		if(isset($_POST['WishlistSearch']))
		{
			$model->attributes=$_POST['WishlistSearch'];
			if($model->validate())
			{
				$objCustomer = customer::LoadByEmail($model->email);
				$objWishlists = Wishlist::model()->findAllByAttributes(array('customer_id'=>$objCustomer->id,'visibility'=>Wishlist::PUBLICLIST));

			}
		}
		//We pass a dummy model of WishListItem to get our edit form ready
		$this->render('/wishlist/search',array(
			'objWishlists'=>$objWishlists,
			'model'=>$model,
		));

	}

	public function actionAdd()
	{

		if (Yii::app()->user->isGuest)
		{
			echo Yii::t('wishlist',"You must be logged in to add to a wish list. Please sign in and try again.");
			return;
		}

		if(Yii::app()->request->isAjaxRequest) {


			$model = new WishlistAddForm();
			// collect user input data
			if(isset($_POST))
			{

				if(isset($_POST['WishlistAddForm']))
					$model->attributes=$_POST['WishlistAddForm'];
				else
					$model->attributes=$_POST;

				if($model->validate())
				{
					$intProductId = $model->id;
					$intQty = $model->qty;

					$strSize = $model->size;
					$strColor = $model->color;

					$objProduct = Product::model()->findByPk($intProductId);
					if (!$objProduct instanceof Product) return;

					if ($objProduct->IsMaster)
					{
						if (!empty($strSize) && !empty($strColor)) //We passed a size color selection, so get the right item
						{
							$objProduct = Product::model()->findByAttributes(
								array('parent'=>$intProductId,'product_size'=>$strSize,'product_color'=>$strColor));
							$model->id = $intProductId = $objProduct->id;
						}
						else
						{
							echo Yii::t('wishlist',"Please choose options before selecting {button}",
								array('{button}'=>Yii::t('product', 'Add to Wish List')));
							return;
						}

					}


					//If we don't have a wish list, create one and add. If we only have one wish list, add it. If we have more than
					//one, we need to prompt for which one to add

					$objWishLists = Wishlist::LoadUserLists();
					switch (count($objWishLists))
					{
						case 0:
							//We don't have a wish list, so let's create one
							$objWishList = new Wishlist();
							$objWishList->registry_name = Yii::t('wishlist','My Wish List');
							$objWishList->visibility = Wishlist::PERSONALLIST;
							$objWishList->after_purchase = Wishlist::LEAVEINLIST;
							$objWishList->customer_id = Yii::app()->user->id;
							$objWishList->gift_code = md5(uniqid());
							$objWishList->ship_option = 0;
							if (!$objWishList->save())
								Yii::log("Error creating Wish List ".print_r($objWishList->getErrors(),true),
									'error', 'application.'.__CLASS__.".".__FUNCTION__);
							$objWishLists = array();
							$objWishLists[0] = $objWishList;
							// No break so we drop to the next set of instructions

						case 1:
							$objWishList = $objWishLists[0];
							$objWishItem = new WishlistItem();
							$objWishItem->registry_id = $objWishList->id;
							$objWishItem->product_id = $intProductId;
							$objWishItem->qty = $intQty;
							if ($objWishItem->save())
								echo Yii::t('wishlist','Item has been added to your Wish List.');
							else
							{
								Yii::log("error saving wishlist item ".print_r($objWishItem->getErrors(),true),
									'error', 'application.'.__CLASS__.".".__FUNCTION__);
								echo Yii::t('wishlist','An error occurred adding this item to your Wish List.');
							}

							break;

						default:
							$Wishlistadd = new WishlistAddForm();
							if (isset($_POST['WishlistAddForm']))
								$Wishlistadd->attributes = $_POST['WishlistAddForm'];

							if ($Wishlistadd->validate())
							{
								$objWishList = Wishlist::model()->findByAttributes(array('gift_code'=>$Wishlistadd->gift_code));
								$objWishItem = new WishlistItem();
								$objWishItem->registry_id = $objWishList->id;
								$objWishItem->product_id = $intProductId;
								$objWishItem->qty = $Wishlistadd->qty;
								if ($objWishItem->save())
									echo Yii::t('global','Item has been added to your Wish List.');
								else
								{
									Yii::log("error saving wishlist item ".print_r($objWishItem->getErrors(),true),
										'error', 'application.'.__CLASS__.".".__FUNCTION__);
									echo Yii::t('global','An error occurred adding this item to your Wish List.');
								}
							}
							else
			                    echo "multiple";
							break;

					}

				} else echo print_r($model->getErrors(),true);

			} else echo "Error missing POST";

		} else echo "Error not AJAX";

	}


	public function actionEdititem()
	{

		if (Yii::app()->user->isGuest)
			throw new CHttpException(404,'The requested page does not exist.');

		$model=new WishlistEditForm();
		error_log(print_r($_POST,true));
		// collect user input data
		if(isset($_POST['WishlistEditForm']))
		{

			$model->attributes=$_POST['WishlistEditForm'];
			if($model->validate())
			{

				$strCode = $model->code;
				$intRow = $model->id;

				//Make sure code we've been passed is valid
				$objWishlist = Wishlist::model()->findByAttributes(array('gift_code'=>$strCode));
				if (!$objWishlist->Visible)
					throw new CHttpException(404,'The requested page does not exist.');

				$objWishrow = WishlistItem::model()->findByAttributes(array('id'=>$intRow,'registry_id'=>$objWishlist->id));
				$objWishrow->qty = $model->qty;
				$objWishrow->qty_received = $model->qty_received;
				$objWishrow->comment = $model->comment;
				$objWishrow->priority = $model->priority;
				if (!$objWishrow->save()) {
					Yii::log("Error saving wish list item ".print_r($objWishrow->getErrors(),true),
						'error', 'application.'.__CLASS__.".".__FUNCTION__);
					$response_array['status'] = 'error';
					$response_array['errormsg'] = print_r($objWishrow->getErrors(),true);

				}
				else
					$response_array = array(
					'status'=>"success",
					'code'=>$objWishlist->gift_code,
					'id'=>$objWishrow->id,
					'qty'=>$objWishrow->qty,
					'qty_received'=>$objWishrow->qty_received,
					'priority'=>$objWishrow->priority,
					'comment'=>$objWishrow->comment,
					'reload'=>true,
				);


			}
			else {
				$response_array['status'] = 'error';
				$response_array['errormsg'] = print_r($model->getErrors(),true);
			}


			echo json_encode($response_array);


		}
		else
			$this->getEditForm();



	}

	public function actionDeleteitem()
	{

		if (Yii::app()->user->isGuest)
			throw new CHttpException(404,'The requested page does not exist.');

		$model=new WishlistEditForm();

		if(isset($_POST['WishlistEditForm']))
		{

			$model->attributes=$_POST['WishlistEditForm'];
			if($model->validate())
			{

				$strCode = $model->code;
				$intRow = $model->id;

				//Make sure code we've been passed is valid
				$objWishlist = Wishlist::model()->findByAttributes(array('gift_code'=>$strCode));
				if (!$objWishlist->Visible)
					throw new CHttpException(404,'The requested page does not exist.');

				$objWishrow = WishlistItem::model()->findByAttributes(array('id'=>$intRow,'registry_id'=>$objWishlist->id));
				if (!(is_null($objWishrow->cart_item_id)))
					CartItem::model()->updateByPk($objWishrow->cart_item_id,array('wishlist_item'=>null));

				if (!$objWishrow->delete()) {
					Yii::log("Error deleting wish list item ".print_r($objWishrow->getErrors(),true),
						'error', 'application.'.__CLASS__.".".__FUNCTION__);
					$response_array['status'] = 'error';
					$response_array['errormsg'] = print_r($objWishrow->getErrors(),true);

				}
				else
					$response_array = array(
						'status'=>"success",
						'code'=>$objWishlist->gift_code,
						'id'=>$objWishrow->id,
						'reload'=>true,
					);


			}
			else {
				$response_array['status'] = 'error';
				$response_array['errormsg'] = print_r($model->getErrors(),true);
			}

			echo json_encode($response_array);


		}




	}

	protected function getEditForm()
	{
		//New dialog box init, so load our initial data
		$strCode = Yii::app()->getRequest()->getParam('code');
		$intRow = Yii::app()->getRequest()->getParam('id');
		$objWishlist = Wishlist::model()->findByAttributes(array('gift_code'=>$strCode));
		if (!$objWishlist->Visible)
			throw new CHttpException(404,'The requested page does not exist.');

		$objWishrow = WishlistItem::model()->findByAttributes(array('id'=>$intRow,'registry_id'=>$objWishlist->id));

		$arrReturn = array(
			'action'=>"update",
			'code'=>$objWishlist->gift_code,
			'id'=>$objWishrow->id,
			'qty'=>$objWishrow->qty,
			'qty_received'=>$objWishrow->qty_received,
			'priority'=>$objWishrow->priority,
			'comment'=>$objWishrow->comment
		);

		echo json_encode($arrReturn);
	}


	public function actionEmail()
	{

		if (Yii::app()->user->isGuest)
			throw new CHttpException(404,'The requested page does not exist.');

		$model=new ShareForm();

		if(isset($_POST['ShareForm']))
		{

			$model->attributes=$_POST['ShareForm'];
			if($model->validate())
			{

				$strCode = $model->code;

				//Make sure code we've been passed is valid
				$objWishlist = Wishlist::model()->findByAttributes(array('gift_code'=>$strCode));
				if (!$objWishlist->Visible)
					throw new CHttpException(404,'The requested page does not exist.');


				if (!Yii::app()->user->isGuest)
				{
					$objCustomer = Customer::model()->findByPk(Yii::app()->user->Id);
					$model->fromEmail = $objCustomer->email;
					$model->fromName = $objCustomer->fullname;
				}

				$strHtmlBody =$this->renderPartial('/mail/_cart',array('model'=>$model), true);
				$strSubject = _xls_format_email_subject('EMAIL_SUBJECT_WISHLIST',$objWishlist->customer->fullname,null);

				$objEmail = new EmailQueue;

				$objEmail->customer_id = $objWishlist->customer_id;
				$objEmail->htmlbody = $strHtmlBody;
				$objEmail->subject = $strSubject;
				$objEmail->to = $model->toEmail;

//				$objHtml = new HtmlToText;
//
//				//If we get back false, it means conversion failed which 99.9% of the time means improper HTML.
//				$strPlain = $objHtml->convert_html_to_text($strHtmlBody);
//				if ($strPlain !== false)
//					$objEmail->plainbody = $strPlain;

				$objEmail->save();


				$response_array = array(
					'status'=>"success",
					'message'=> Yii::t('wishlist','Your wish list has been sent'),
					'url'=>CController::createUrl('site/sendemail',array("id"=>$objEmail->id)),
					'reload'=>true,
				);


			}
			else {
				$response_array['status'] = 'error';
				$response_array['errormsg'] = _xls_convert_errors($model->getErrors());
			}

		echo json_encode($response_array);
		}



	}

}