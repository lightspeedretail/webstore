<?php

/**
 * Search controller
 *
 * Used for both general browsing from categories, as well as specific searches
 *
 * @category   Controller
 * @package    Myaccount
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2012-12-06

 */
class SearchController extends Controller
{
	/**
	 * @var string
	 */
	public $layout='//layouts/column2';
	/**
	 * @var
	 */
	public $subcategories;

	protected $strBreadcrumbCat;

	/**
	 * Display and process our Advanced Search form. On submit, we build a URL and redirect so our
	 * regular search function processes it.
	 */
	public function actionIndex()
	{

		$model = new AdvancedSearchForm();

		if (isset($_POST['AdvancedSearchForm']))
		{
			$model->attributes = $_POST['AdvancedSearchForm'];

			if ($model->validate())
			{

				$strQuery = "?";

				if ($model->cat>0)
				{
					$objCategory = Category::model()->findbyPk($model->cat);
					$strQuery .= "cat=".$objCategory->request_url."&";
				}
				if ($model->q) $strQuery .= "q=".$model->q."&";
				if ($model->startprice) $strQuery .= "startprice=".$model->startprice."&";
				if ($model->endprice) $strQuery .= "endprice=".$model->endprice."&";

				$strQuery = substr($strQuery,0,-1); //Remove trailing character since it's not needed
				$this->redirect($this->createUrl('search/results').$strQuery);

			}

		}

		$this->render('index',array('model'=>$model));

	}


	/**
	 * Used for general category browsing (which is really a search by category or family)
	 * The URL manager passes the category request_url which we look up here
	 */
	public function actionBrowse() {

		$strC = Yii::app()->getRequest()->getQuery('cat');
		$strB = Yii::app()->getRequest()->getQuery('brand');
		$strS = Yii::app()->getRequest()->getQuery('class_name');
		$strInv='';

		//If we haven't passed any criteria, we just query the database
		$criteria = new CDbCriteria();
		$criteria->alias = 'Product';

		if (!empty($strC)) {

			$objCategory = Category::LoadByRequestUrl($strC);
			if($objCategory)
			{
				$criteria->join='LEFT JOIN xlsws_product_category_assn as ProductAssn ON ProductAssn.product_id=Product.id';
				$intIdArray = array($objCategory->id);
				$intIdArray = array_merge($intIdArray, $objCategory->GetBranchPath());
				$criteria->addInCondition('category_id', $intIdArray);

				$this->pageTitle=$objCategory->PageTitle;
				$this->pageDescription=$objCategory->PageDescription;
				$this->pageImageUrl = $objCategory->CategoryImage;
				$this->breadcrumbs = $objCategory->Breadcrumbs;
				$this->pageHeader = $objCategory->label;

				$this->subcategories = $objCategory->SubcategoryTree;

				if ($objCategory->custom_page)
					$this->custom_page_content = $objCategory->customPage->page;

				$this->CanonicalUrl = $this->createAbsoluteUrl($objCategory->Link);
			}



		}

		if (!empty($strB)) {

			$objFamily = Family::LoadByRequestUrl($strB);
			if($objFamily)
			{
				$criteria->addCondition('family_id = :id');
				$criteria->params = array (':id'=>$objFamily->id);

				$this->pageTitle=$objFamily->PageTitle;
				//$this->pageDescription=$objFamily->PageDescription;
				//$this->breadcrumbs = $objCategory->Breadcrumbs;
				$this->pageHeader = $objFamily->family;


				$this->CanonicalUrl = $this->createAbsoluteUrl($objFamily->Link);
			}


		}

		if (!empty($strS)) {

			$objClasses = Classes::LoadByRequestUrl($strS);
			if($objClasses)
			{
				$criteria->addCondition('class_id = :id');
				$criteria->params = array (':id'=>$objClasses->id);

				//$this->pageTitle=$objClasses->PageTitle;
				//$this->pageDescription=$objFamily->PageDescription;
				//$this->breadcrumbs = $objClasses->class_name;
				$this->pageHeader = $objClasses->class_name;
				$this->CanonicalUrl = $this->createAbsoluteUrl($objClasses->Link);


			}


		}


		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD') == Product::InventoryMakeDisappear)
			$criteria->addCondition('(inventory_avail>0 OR inventoried=0)');

		if (!_xls_get_conf('CHILD_SEARCH') || empty($strQ))
			$criteria->addCondition('Product.parent IS NULL');


		$criteria->addCondition('web=1');
		$criteria->addCondition('current=1');
		$criteria->order = 'Product.'._xls_get_sort_order();


		$numberOfRecords = Product::model()->count($criteria);

		$pages = new CPagination($numberOfRecords);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria);  // the trick is here!

		$model = Product::model()->findAll($criteria);
		$model = $this->createBookends($model);

		$this->returnUrl = $this->CanonicalUrl;
		$this->pageImageUrl = "";

		$this->render('grid',array(
		'model'=> $model,
		'item_count'=>$numberOfRecords,
		'page_size'=>Yii::app()->params['listPerPage'],
		'items_count'=>$numberOfRecords,
		'pages'=>$pages,
		));

	}



	/**
	 * Display our search results based on  criteria.
	 */
	public function actionResults() {

		$strQ = Yii::app()->getRequest()->getQuery('q');

		//If we passed a category as a request_url, translate here
		if (isset($_GET['cat'])) {
			$objCategory = Category::LoadByRequestUrl($_GET['cat']);
			if ($objCategory instanceof Category)
				$_GET['cat'] = $objCategory->id;
		}

		$formModel = new AdvancedSearchForm();
		$formModel->attributes = $_GET;

		$strQ = str_replace(array('<','>','=',';'),'',$strQ);

		//We have to run the query twice -- once to get total # for our paginator, then again with our current limits

		//Get our total qty first for pagination
		$objCommand = $this->BuildCommand($formModel,-1); //passing -1 as the limit triggers a count(*) query
		$numberOfRecords = $objCommand->queryScalar();
		$pages=new CPagination(intval($numberOfRecords));
		$pages->pageSize = Yii::app()->params['listPerPage'];

		$objCommand = $this->BuildCommand($formModel,$pages->pageSize, $pages->currentPage*$pages->pageSize);
		$rows = $objCommand->QueryAll();

		//Convert rows to objects for our view layer so it's consistent
		$model = Product::model()->populateRecords($rows,false);

		if (empty($this->strBreadcrumbCat))
			$this->breadcrumbs = array(
			Yii::t('global','Searching for "{terms}"',array('{terms}'=>$strQ))=>'',
		);
		else $this->breadcrumbs = array(
			Yii::t('global','Searching for "{terms}" in category "{category}"',array('{terms}'=>$strQ,'{category}'=>$this->strBreadcrumbCat))=>'',
		);
		$this->CanonicalUrl = $this->createAbsoluteUrl('/search/results',$formModel->attributes,'',"&amp;");



		$this->returnUrl = $this->CanonicalUrl;
		$this->pageImageUrl = "";

		$model = $this->createBookends($model);

		$this->render('grid',array(
				'model'=> $model,
				'item_count'=>$numberOfRecords,
				'page_size'=>Yii::app()->params['listPerPage'],
				'items_count'=>$numberOfRecords,
				'pages'=>$pages,
		));



	}


	/**
	 * AJAX responder for Live Search (dropdown that appears when typing in letters in search box)
	 * We max this out at 10 results to not overwhelm our dropdown box
	 */
	public function actionLive()
	{

		$strQ = Yii::app()->getRequest()->getQuery('q');
		$strQ = str_replace(array('<','>','=',';'),'',$strQ);

		$formModel = new AdvancedSearchForm();
		$formModel->q = $strQ;

		$objCommand = $this->BuildCommand($formModel,10);
		$rows = $objCommand->QueryAll();
		$model = Product::model()->populateRecords($rows,false);
		$arrReturn['options'] = CHtml::listData($model, 'Link', 'title');

		echo json_encode($arrReturn);
	}

	/**
	 * Build a weighted search query. Whole words rank higher than partial, and
	 * words in titles rank higher than in descriptions, which are higher than tags
	 *
	 * Returns a SQL string based on our passed search string
	 *
	 * @param $form
	 * @param null $intLimit
	 * @param null $intOffset
	 * @return mixed
	 */
	protected function BuildCommand($formModel,$intLimit = null, $intOffset = null)
	{

		$strQ = $formModel->q;

		$strInv = '';
		$intCount = 0;
		$arrBind = array(':query'=>$strQ);

		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD') == Product::InventoryMakeDisappear)
			$strInv .= " AND (inventory_avail>0 OR inventoried=0) ";

		if (!_xls_get_conf('CHILD_SEARCH'))
			$strInv .= " AND parent IS NULL ";

		//Since we have passed our AdvancedSearchForm, go through the used fields and add them to our query
		foreach($formModel as $key=>$val)
			if (!empty($val) && $key != "q") //We process our query term separately so keep it out of this loop
			{
				$arrBind[':'.$key]=$val;
				switch ($key)
				{
					case 'startprice':      $strInv .= " AND sell_web >= :startprice "; break;
					case 'endprice':        $strInv .= " AND sell_web <= :endprice ";   break;
					case 'product_size':    $strInv .= " AND product_size = :product_size "; break;
					case 'product_color':   $strInv .= " AND product_color = :product_color "; break;
				}
			}


		$arr = explode(" ",$strQ);
		$strCases = "(CASE WHEN `title` = :query THEN 10 ELSE 0 END) +";
		$strWhere = " `title` = :query OR ";


		foreach ($arr as $item)
		{

			$strTag = ':item'.$intCount;
			$strTag1 = ':itemL1'.$intCount;
			$strTag2 = ':itemL2'.$intCount;
			$strTag3 = ':itemL3'.$intCount;
			$arrBind[':item'.$intCount]=$item;
			$arrBind[':itemL1'.$intCount]="%$item%";
			$arrBind[':itemL2'.$intCount]="%$item %";
			$arrBind[':itemL3'.$intCount]="$item%";


			$strCases .= "(CASE WHEN `title` = $strTag THEN 20 ELSE 0 END) +";
			$strCases .= "(CASE WHEN `title` LIKE $strTag3 THEN 15 ELSE 0 END) +";
			$strCases .= "(CASE WHEN `title` LIKE $strTag2 THEN 10 ELSE 0 END) +";
			$strCases .= "(CASE WHEN `title` LIKE $strTag1 THEN 3 ELSE 0 END) +";
			$strCases .= "(CASE WHEN `description_long` LIKE $strTag1 THEN 2 ELSE 0 END) +";
			$strCases .= "(CASE WHEN `tag` = $strTag THEN 2 ELSE 0 END) +";
			$strCases .= "(CASE WHEN `description_short` LIKE $strTag1 THEN 1 ELSE 0 END) +";
			$strCases .= "(CASE WHEN `code` = $strTag THEN 1 ELSE 0 END) +";
			$strCases .= "(CASE WHEN `product_size` = $strTag THEN 1 ELSE 0 END) +";
			$strCases .= "(CASE WHEN `product_color` = $strTag THEN 1 ELSE 0 END) +";

			$strWhere .= " `title` = $strTag OR ";
			$strWhere .= " `title` LIKE $strTag3 OR ";
			$strWhere .= " `title` LIKE $strTag2 OR ";
			$strWhere .= " `title` LIKE $strTag1 OR ";
			$strWhere .= " `description_long` LIKE $strTag1 OR ";
			$strWhere .= " `tag` = $strTag  OR ";
			$strWhere .= " `description_short` LIKE $strTag1 OR ";
			$strWhere .= " `code` = $strTag OR ";
			$strWhere .= " `product_size` = $strTag OR ";
			$strWhere .= " `product_color` = $strTag OR ";
			$intCount++;
		}
		$strCases = substr($strCases,0,-1); //Remove last +
		$strWhere = substr($strWhere,0,-3); //Remove last OR(space)


		$objCommand = Yii::app()->db->createCommand();
		$objCommand->select('t.*, (' . $strCases . ') AS relevance');
		$objCommand->leftJoin('xlsws_product_tags t2', 't2.product_id = t.id');
		$objCommand->leftJoin('xlsws_tags t3', 't2.tag_id = t3.id');
		$objCommand->from('xlsws_product t');
		$objCommand->where('(' . $strWhere . ') ' . $strInv . '	AND web=1 AND current=1');

		//If we have passed a category, append it to the search here
		if(isset($formModel['cat'])  && $formModel['cat']>0)
		{
			$objCategory = Category::model()->findbyPk($formModel['cat']);
			$this->strBreadcrumbCat = $objCategory->label;
			$intIdArray = array($objCategory->id);
			$intIdArray = array_merge($intIdArray, $objCategory->GetBranchPath());
			unset($arrBind[':cat']);
			$objCommand->leftJoin('xlsws_product_category_assn t4', 't4.product_id = t.id');
			$objCommand->where(array('AND', '(' . $strWhere . ') ' . $strInv . ' AND web=1 AND current=1', array('in', 'category_id', $intIdArray)));
		}


		$objCommand->group('t.id');
		$objCommand->order('relevance DESC');
		if (!is_null($intLimit))
		{
			if ($intLimit==-1) {
				//This means we're just running a count, so we don't need all aspects of this query
				$objCommand->select(' count(DISTINCT t.id) ');
				$objCommand->group(null);
				$objCommand->order(null);
			}
			else $objCommand->limit($intLimit, $intOffset);
		}

		foreach ($arrBind as $key=>$val)
			$objCommand->bindValue($key ,$val, PDO::PARAM_STR);



		return $objCommand;
	}



}