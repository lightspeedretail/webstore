<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * xlsws_searchresult class
 * This is the controller class for the category search results pages
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the search results pages
 */

class xlsws_searchresult extends xlsws_index {
	protected $subcategories = null;
	protected $search_array;
	protected $default_search_param = 'and';

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main(){
		global $XLSWS_VARS, $config_products_per_page;

		$XLSWS_VARS['search'] = strip_tags($XLSWS_VARS['search']);

		$this->crumbs[] = array('key'=>'search=' . $XLSWS_VARS['search'] , 'case'=> '' , 'name'=>_sp('Search Results'));

		$this->mainPnl = new QPanel($this);

		$this->dtrProducts = new QDataRepeater($this->mainPnl);
		$this->dtrProducts->CssClass = "product_list rounded";
		// Let's set up pagination -- note that the form is the parent
		// of the paginator here, because it's on the form where we
		// make the call to $this->dtrProducts->Paginator->Render()

		$this->dtrProducts->Paginator = new XLSPaginator($this->mainPnl , "pagination");
		$this->dtrProducts->ItemsPerPage = _xls_get_conf('PRODUCTS_PER_PAGE' , 8);

		// Enable AJAX-based rerendering for the QDataRepeater
		$this->dtrProducts->UseAjax = true;

		// DataRepeaters use Templates to define how the repeated
		// item is rendered
		$this->mainPnl->Template = templateNamed('product_list.tpl.php');
		$this->dtrProducts->Template = templateNamed('product_list_item.tpl.php');

		// Finally, we define the method that we run to bind the data source to the datarepeater
		$this->dtrProducts->SetDataBinder('dtrProducts_Bind');
		Visitor::add_view_log(0, ViewLogType::search , '' , $XLSWS_VARS['search']);
	}

	/**
	 * dtrProducts_Bind - Binds a listing of products to the current list of search results
	 * @param none
	 * @return none
	 */
	protected function dtrProducts_Bind() {
		global $XLSWS_VARS;

		static $try;

		$search = trim($XLSWS_VARS['search']);
		$prods = array();
		$total = 0;

		if(($search != null && $search != "") || $XLSWS_VARS['advsearch'] == "true") {
			if($search == null || $search == "")
				$search = " ";

			$search = addslashes($search);
			$catQ = "";
			$matrixQ = "";

			if ($XLSWS_VARS['filter'] == "1" && !empty($XLSWS_VARS['c'])) {
				$searchcat = end($this->menu_categories); //no need to do a query to get the current category object, array is already set
				$results = $searchcat->GetChildIds();
				$results[] = $XLSWS_VARS['c'];

				$catQ = " AND xlsws_product_category_assn.category_id in (" . implode(",",$results) . ")";
			}

			if (!_xls_get_conf("CHILD_SEARCH"))
				$matrixQ = " AND xlsws_product.fk_product_master_id=0";

			$db = Product::GetDatabase();

			$qFull = 'SELECT distinct xlsws_product.* FROM xlsws_product LEFT JOIN xlsws_product_category_assn on xlsws_product.rowid = xlsws_product_category_assn.product_id WHERE (xlsws_product.name LIKE "%'.$search.'%" OR xlsws_product.web_keyword1 LIKE "%'.$search.'%" OR xlsws_product.web_keyword1 LIKE "%'.$search.'%" OR xlsws_product.description LIKE "%'.$search.'%" OR xlsws_product.web_keyword2 LIKE "%'.$search.'%" OR web_keyword3 LIKE "%'.$search.'%" OR xlsws_product.code LIKE "%'.$search.'%")  AND xlsws_product.web=1' . $matrixQ. $catQ;
			$q = 'SELECT COUNT(distinct xlsws_product.rowid) as total_matches FROM xlsws_product LEFT JOIN xlsws_product_category_assn on xlsws_product.rowid = xlsws_product_category_assn.product_id WHERE (xlsws_product.name LIKE "%'.$search.'%" OR xlsws_product.web_keyword1 LIKE "%'.$search.'%" OR xlsws_product.web_keyword1 LIKE "%'.$search.'%" OR xlsws_product.description LIKE "%'.$search.'%" OR xlsws_product.web_keyword2 LIKE "%'.$search.'%" OR xlsws_product.web_keyword3 LIKE "%'.$search.'%" OR xlsws_product.code LIKE "%'.$search.'%")  AND xlsws_product.web=1' . $matrixQ . $catQ;

			if (!empty($XLSWS_VARS['startprice'])) {
				if (_xls_get_conf('TAX_INCLUSIVE_PRICING') == "1") {
					$q .= " AND xlsws_product.sell_tax_inclusive >= " . $XLSWS_VARS['startprice'];
					$qFull .= " AND xlsws_product.sell_tax_inclusive >= " . $XLSWS_VARS['startprice'];
				} else {
					$q .= " AND xlsws_product.sell_web >= " . $XLSWS_VARS['startprice'];
					$qFull .= " AND xlsws_product.sell_web >= " . $XLSWS_VARS['startprice'];
				}
			}

			if (!empty($XLSWS_VARS['endprice'])) {
				if (_xls_get_conf('TAX_INCLUSIVE_PRICING') == "1") {
					$q .= " AND xlsws_product.sell_tax_inclusive <= " . $XLSWS_VARS['endprice'];
					$qFull .= " AND xlsws_product.sell_tax_inclusive <= " . $XLSWS_VARS['endprice'];
				} else {
					$q .= " AND xlsws_product.sell_web <= " . $XLSWS_VARS['endprice'];
					$qFull .= " AND xlsws_product.sell_web <= " . $XLSWS_VARS['endprice'];
				}
			}

			$sort_order = _xls_convert_camel(_xls_get_conf('PRODUCT_SORT_FIELD' , 'Name'));
			$sort_type = "asc";

			if ($sort_order == "inventory_total")
				$sort_type = "desc";

			$qFull .= " ORDER BY " . $sort_order . " " . $sort_type;
			$qFull .= ' LIMIT ' . $this->dtrProducts->LimitClause->Offset . ', '. $this->dtrProducts->LimitClause->MaxRowCount;

			$matches = $db->Query($qFull);
			$total_query = $db->Query($q);

			$prods = Product::InstantiateDbResult($matches);
			$total_arr = $total_query->FetchArray();
			$total = $total_arr['total_matches'];
		} else {
			// if no search parameter given ??
			$this->mainPnl->RemoveChildControls(true);
			$this->mainPnl->Template = templateNamed('msg.tpl.php');
			$this->msg = _sp("No search keywords specified.");
			return;
		}

		$this->dtrProducts->TotalItemCount = $total;

		if($this->dtrProducts->TotalItemCount <= 0) {
			$this->mainPnl->RemoveChildControls(true);
			$this->mainPnl->Template = templateNamed('msg.tpl.php');
			$this->msg = _sp("Sorry no product was found");
		} else {
			$this->clear_prod_images();
			//create images
			foreach($prods as $prod) {
				$this->create_prod_img(
					$this->dtrProducts,
					$prod,
					'ListingImage',
					_xls_get_conf('LISTING_IMAGE_WIDTH',50),
					_xls_get_conf('LISTING_IMAGE_HEIGHT',40)
				);
			}

			$this->dtrProducts->DataSource = $prods;
		}
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_searchresult::Run('xlsws_searchresult', templateNamed('index.tpl.php'));
