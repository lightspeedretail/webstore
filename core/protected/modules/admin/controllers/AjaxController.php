<?php

class AjaxController extends AdminBaseController
{


	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('promoset','getstates','integrationcategories',
					'intcatsave','intsubcats','shippingset','updaterestrictions','currentcats'),
				'roles'=>array('admin'),
			),
		);
	}


	public function actionPromoset()
	{

		$id = Yii::app()->getRequest()->getQuery('id');
		$objPromoCode = PromoCode::model()->findByPk($id);


		$model = new RestrictionForm();

		if ($objPromoCode instanceof PromoCode)
		{
			$model->id=$objPromoCode->id;
			$model->promocode=$objPromoCode->code;
			$model->exception=$objPromoCode->exception;

			list($model->categories,$model->families,$model->classes,$model->keywords,$model->codes) = $this->parseRestrictions($objPromoCode->lscodes);
		}
		echo $this->renderPartial("_restrictions",array('model'=>$model),true);

	}

	public function actionShippingset()
	{

		$moduleid = Yii::app()->getRequest()->getQuery('id');


		if (empty($moduleid)) return;

		$moduleid = str_replace("AdminForm","",$moduleid);


		$objPromoCode = PromoCode::model()->findByAttributes(array('module'=>$moduleid));

		$model = new ShippingRestrictionForm();

		if(!($objPromoCode instanceof PromoCode))
		{
			//We don't have an existing line item
			$objPromoCode = new PromoCode();
			$objPromoCode->module = $moduleid;
			$objPromoCode->enabled = 0; //only defined LS are enabled for shipping restrictions
			$objPromoCode->type = PromoCode::Currency; //always 0 for shipping restrictions
			$objPromoCode->amount = 0;
			$objPromoCode->save();
		}

		//Prepopulate if we have them
		$model->id=$objPromoCode->id;
		$model->promocode = Yii::app()->getComponent($moduleid)->AdminName;
		$model->exception=$objPromoCode->exception;

		list($model->categories,$model->families,$model->classes,$model->keywords,$model->codes) = $this->parseRestrictions($objPromoCode->lscodes);

		echo $this->renderPartial("_restrictions",array('model'=>$model),true);

	}

	public function actionUpdateRestrictions()
	{
		$blnShipping = false;

		$errormsg = Yii::t('admin',"An error occurred saving Promo Code restrictions. Please check your System Log.");

		$arrRPost = Yii::app()->getRequest()->getPost('RestrictionForm');
		$arrSPost = Yii::app()->getRequest()->getPost('ShippingRestrictionForm');

		if (isset($arrRPost)) $arrPost = $arrRPost;
		if (isset($arrSPost))
		{
			$arrPost = $arrSPost;
			$blnShipping=true;
		}

		if(isset($arrPost['id']))
		{
			$id = $arrPost['id'];
			$objPromoCode = PromoCode::model()->findByPk($id);

			if ($objPromoCode instanceof PromoCode)
			{
				$lscodes = '';
				unset($arrPost['id']);
				$arrSections = array(
					'category:'=>'categories',
					'family:'=>'families',
					'keyword:'=>'keywords',
					'class:'=>'classes',
					''=>'codes');
				foreach ($arrSections as $key=>$arrSection)
					if(isset($arrPost[$arrSection])) {
						foreach($arrPost[$arrSection] as $sectionValue)
							$lscodes .= $key.$sectionValue.",";
						unset($arrPost[$arrSection]);
					}

				//Since we're using our Promo Code restriction structure for general shipping restrictions...
				if ($blnShipping && $objPromoCode->module != "freeshipping")
					if (strlen($lscodes)>0) $objPromoCode->enabled=1; else $objPromoCode->enabled=0;

				if (strlen($lscodes)>0) $lscodes = substr($lscodes,0,-1);
				else $arrPost['exception']=0;

				$arrPost['lscodes'] = $blnShipping==true ? "shipping:,".$lscodes : $lscodes;

				$objPromoCode->attributes = $arrPost;
				if ($objPromoCode->validate())
				{
					if (!$objPromoCode->save())
						echo $errormsg;
					else echo "success";
				}
				else {
					echo $errormsg;
					Yii::log("Admin panel validation error saving promo code ".print_r($objPromoCode->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				}
			} else echo $errormsg;
		} else echo $errormsg;


	}


	/**
	 * Query top level categories for given service, pass back contents of dialog box
	 * including dropdowns.
	 */
	public function actionIntegrationCategories()
	{

		$id = Yii::app()->getRequest()->getQuery('id');
		$service = Yii::app()->getRequest()->getQuery('service');
		$objCategory = Category::model()->findByPk($id);
		$strRequestUrl = $objCategory->request_url;

		//All our category tables follow the same pattern
		$strModelName = "Category".ucfirst($service);

		//A model (table) for each service
		$model = new $strModelName();
		echo $this->renderPartial("/integration/_integratedcats",
			array('model'=>$model,
				'strRequestUrl'=>$strRequestUrl,
				'strModelName'=>$strModelName,
				'service'=>$service)
			,true);



	}

	/**
	 * Retrive the currently selected 3rd party category that's assigned to our WS category number ($id)
	 */
	public function actionCurrentcats()
	{

		$id = Yii::app()->getRequest()->getQuery('id');
		$service = Yii::app()->getRequest()->getQuery('service');
		$arrReturn = array();

		$objCategory = Category::model()->findByPk($id); //Look up WS category
		$strModelName = "Category".ucfirst($service); //Model for 3rd party service


		//Do we have a setting
		$objInt = CategoryIntegration::model()->findByAttributes(array('category_id'=>$objCategory->id,'module'=>$service));
		if(!($objInt instanceof CategoryIntegration))
		{
			//We don't have a category set, but is this a child category and do we have a parent?
			if ($objCategory->HasParent()) {
				$objInt = CategoryIntegration::model()->findByAttributes(
					array('category_id'=>$objCategory->parent,'module'=>$service));
			}

		}


		if ($objInt instanceof CategoryIntegration)
		{
			//Look up 3rd party category tree
			$objPicked = $strModelName::model()->findByPk($objInt->foreign_id);
			if (!($objPicked instanceof $strModelName)) die();

			$arrCats=array();
			$arrReturn['producttypes'] = "";
			$lastId = 0;
			for ($x=1; $x<=7; $x++) {

				$strName = "name".$x;
				$strNextName = "name".($x+1);

				$objPickedLayer = null;

				if (!is_null($objPicked->$strName))
					$objPickedLayer = $strModelName::model()->find(array(
						'condition'=>$strName.'=:thisname AND '.$strNextName.' is null and id> :id',
						'params'=>array(':thisname'=>$objPicked->$strName, ':id'=>$lastId),
					));

				if ($objPickedLayer instanceof $strModelName)
				{
					$arrCats[$x] = $objPickedLayer->id;
					$lastId =  $objPickedLayer->id;
					if (isset($objPickedLayer->product_type))
						$arrReturn['producttypes'] = $this->getProductTypes($objPickedLayer->product_type);
				}
			}

			$arrReturn['cats']=$arrCats;

			switch ($service)
			{
				case "google":
					if (!is_null($objInt->extra))
						$arrExtra = explode(",",$objInt->extra);
					else $arrExtra = array('Unisex','Adult');

					$arrReturn['gender']=$arrExtra[0];
					$arrReturn['age']=$arrExtra[1];
					break;

				case "amazon":
					if (!is_null($objInt->extra))
						$arrReturn['producttypes']=$objInt->extra;
					break;
			}

		}

		echo json_encode($arrReturn);
	}

	/**
	 * When a category is chosen, find subcategories available for that selection
	 */
	public function actionIntsubcats()
	{
		$service = Yii::app()->getRequest()->getQuery('service');
		$intLevel = Yii::app()->getRequest()->getQuery('lv');
		$intSelected = Yii::app()->getRequest()->getQuery('selected');
		$strModelName = "Category".ucfirst($service); //Model for 3rd party service

		$objPicked = $strModelName::model()->findByPk($intSelected);

		if ($intLevel<1 || $intLevel>9) $intLevel=1;
		$strNext = "name".($intLevel+1);
		$strAfter = "name".($intLevel+2);

		$criteria = new CDbCriteria();
		$criteria->select = 't.id,t.'.$strNext;
		$criteria->order = $strNext;
		$arrParam = array();
		for($x = 1; $x<=$intLevel; $x++)
		{
			$criteria->AddCondition("name".$x."=:name".$x);
			$strThis = "name".$x;
			$arrParam[':name'.$x]=$objPicked->$strThis;
		}
		$criteria->AddCondition($strNext." is NOT NULL");
		$criteria->AddCondition($strAfter." is NULL");
		$criteria->params =$arrParam;

		$arrReturn['cats'] = CHtml::listData($strModelName::model()->findAll($criteria), 'id', $strNext);
		if(isset($objPicked->product_type)) $arrReturn['producttypes']=$this->getProductTypes($objPicked->product_type);
		echo json_encode($arrReturn);


	}

	protected function getProductTypes($strType)
	{


		$arrReturn = array();
		if(!empty($strType))
		{
			$result = file_get_contents(YiiBase::getPathOfAlias('ext.wsamazon.assets.xsd')."/".$strType.".xsd");

			$result = str_replace(' minOccurs="0"','',$result);
			preg_match_all('/<(?:"[^"]*"[\'"]*|\'[^\']*\'[\'"]*|[^\'">])+>/', $result, $matches);
			if ($key = array_search('<xsd:element name="ProductType">',$matches[0]))
			{
				$arrReturn = array();

				do
				{
					$key++;

					if (stripos($matches[0][$key],"element ref") !== false)
					{
						preg_match('/ref="(.*?)"/',$matches[0][$key],$m);
						$arrReturn[$m[1]]=$m[1];
					}
					if (stripos($matches[0][$key],"</xsd:choice>") !== false)
						$key=0;



				} while ($key>0 && $key<count($matches[0]));

				sort($arrReturn);

			}
		}
		return $arrReturn;

	}


	/**
	 * Save chosen third-party category
	 */
	public function actionIntCatSave() {

		$service = Yii::app()->getRequest()->getQuery('service');
		$strSelected = Yii::app()->getRequest()->getQuery('selected');

		$arrSelected = explode("|",$strSelected);
		if (count($arrSelected)>1)
		{

			$objCategory = Category::model()->findByPk($arrSelected[0]);
			if ($objCategory instanceof Category)
			{
				$strTable = "Category".ucfirst($service);

				$objIntCategory = $strTable::model()->findByPk($arrSelected[1]);
				if ($objIntCategory instanceof $strTable)
				{
					if(!$objIntCategory->isUsable)
					{
						echo json_encode(ucfirst($service).
							" does not allow you to pick this category without choosing a further subcategory.");
						return;
					}
					CategoryIntegration::model()->deleteAllByAttributes(array('category_id'=>$objCategory->id,'module'=>$service));
					$objCI = new CategoryIntegration();
					$objCI->category_id = $objCategory->id;
					$objCI->module=$service;
					$objCI->foreign_id=$objIntCategory->id;
					switch (count($arrSelected))
					{
						case 4: $objCI->extra = $arrSelected[2].",".$arrSelected[3]; break;
						case 3: $objCI->extra = $arrSelected[2]; break;
						case 2: $objCI->extra =  null; break;
					}

					if (!$objCI->save())
						echo json_encode(_xls_convert_errors($objCI->getErrors()));
					else echo json_encode("success|".$objIntCategory->name0);

				} else echo json_encode(ucfirst($service)." category not found");
			} else echo json_encode("Web Store category not found");
		} else echo json_encode("Array error");

	}

	public function actionGetstates()
	{
		$intCountry = Yii::app()->getRequest()->getPost('country_id');
		$data = CHtml::listData(State::model()->findAllByAttributes(array('country_id'=>$intCountry,'active'=>1),array('order'=>'sort_order,state')), 'id', 'code');
		if (empty($data))
			$data[0]="n/a";

		foreach($data as $key=>$val)
			echo CHtml::tag('option', array('value'=>$key),CHtml::encode($val),true);


	}



	protected function parseRestrictions($strRestrictions)
	{
		$arrCode = explode(",",$strRestrictions);

		if (empty($arrCode)) //no product restrictions
			return array(array(),array(),array(),array(),array());


		$arrCategories = array();
		$arrFamilies = array();
		$arrClasses = array();
		$arrKeywords = array();
		$arrCodes = array();

		foreach($arrCode as $strCode) {

			if (substr($strCode, 0,9) == "category:")
				$arrCategories[] = trim(substr($strCode,9,255));

			elseif (substr($strCode, 0,6) == "class:")
				$arrClasses[] = trim(substr($strCode,6,255));

			elseif (substr($strCode, 0,7) == "family:")
				$arrFamilies[] = trim(substr($strCode,7,255));

			elseif (substr($strCode, 0,8) == "keyword:")
				$arrKeywords[] = trim(substr($strCode,8,255));

			else
				$arrCodes[] = trim($strCode);


		}

		//Yii for some reason requires the order to match the control option order, so since our controls are alphabetical, sort here
		asort($arrCategories);
		asort($arrFamilies);
		asort($arrClasses);
		asort($arrKeywords);
		asort($arrCodes);

		return array($arrCategories,$arrFamilies,$arrClasses,$arrKeywords,$arrCodes);

	}



}
