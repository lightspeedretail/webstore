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
 * xlsws_product class
 * This is the controller class for the product detail page
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the product detail page
 */
class xlsws_product extends xlsws_index {
	protected $prod , $origin_prod; //the current product and original product if its matrixed

	protected $txtQty; //the input text box for quantity (hidden by default)

	protected $lblTitle; //the label for the product title
	protected $lblPrice; //the label for the product price
	protected $lblStock; //the label for the product stock levels
	protected $lblDescription; //the label for the product description

	protected $arrOptionProds; //an array of sizes and colors if its a matrixed item
	protected $lstSize; //the listbox for selecting sizes
	protected $lstColor; //the listbox for selecting colors
    protected $strMatrixPlaceholder = "Select %s ...";

	protected $masterProductId; //the rowid of the master product if applicable

	protected $pnlImg; //the panel that holds the image
	protected $pnlImgHolder; //the panel containing the frame around the image for drag n drop

	public $arrAdditionalProdImages = array(); //array of additional product photos
	protected $pnlAdditionalProdImages; //the panel where additional images display

	protected $imgList; //the list of images

	protected $giftRegistryPnl; //the add to wish list panel

	protected $arrAutoAddProducts; //the recommended products/auto add related product objects array
	protected $arrRelatedProducts; //array of related products as product objects
	protected $sldRelated; //the slider widget for showing related products
	public $lightbox; //boolean, are we rendering a product detail lightbox?

	protected $dxImage; //the current selected image
	public $pxyEnlarge; //the preview or enlarge hyperlink
	public $sliderName = "sldRelated";

	protected $autoAddCheckIDs = array(); //array of items to add the auto add tickmark to if you wish to override

	/**
	 * build_registry_dropdown - builds the dropdown to select a gift registry
	 * @param none
	 * @return none
	 */
	protected function build_registry_dropdown() {
		$objRegArray= GiftRegistry::QueryArray(QQ::Equal(QQN::GiftRegistry()->CustomerId, _xls_get_current_customer_id()));
		$this->misc_components['select_gift_registry'] = new XLSListBox($this->giftRegistryPnl);
		$this->misc_components['select_gift_registry']->Width="200px";
		$this->misc_components['select_gift_registry']->AddItem(_sp('-- Select a Wish List --'), null);

		if ($objRegArray) foreach ($objRegArray as $objReg) {
			$this->misc_components['select_gift_registry']->AddItem($objReg->RegistryName, $objReg->Rowid);
		}
	}

	/**
	 * build_registry_add - builds the add button for the registry
	 * @param none
	 * @return none
	 */
	protected function build_registry_add() {
		$this->misc_components['add_gift_registry'] = new QButton($this->giftRegistryPnl);
		$this->misc_components['add_gift_registry']->Text = 'Add';
	}

	/**
	 * build_registry_cancel - builds the dropdown to select a gift registry
	 * @param none
	 * @return none
	 */
	protected function build_registry_cancel() {
		$this->misc_components['cancel_gift_registry'] = new QButton($this->giftRegistryPnl);
		$this->misc_components['cancel_gift_registry']->Text = 'Cancel';
		$this->misc_components['cancel_gift_registry']->ActionParameter = 'hide';
	}

	/**
	 * build_slider - builds the products slideshow slider
	 * @param none
	 * @return none
	 */
	protected function build_slider($objRelatedArray = false) {
		if (!$objRelatedArray) {
			$objRelatedArray = ProductRelated::LoadArrayByProductId(
				$this->prod->Rowid,
				QQ::Clause(QQ::OrderBy(QQN::ProductRelated()->Rowid))
			);
		}

		foreach ($objRelatedArray as $objRelated) {
			$objProduct = Product::Load($objRelated->RelatedId);

			if (!$objProduct)
				continue;

			if (!$objProduct->IsAvailable)
				continue;

			if ($objRelated->Autoadd)
				$this->arrAutoAddProducts[] = array(
					'prod' => $objProduct,
					'qty' => $objRelated->Qty ? $objRelated->Qty : 1
				);
			else
				$this->arrRelatedProducts[] = $objProduct;
		}

		$this->sldRelated = new XLSSlider($this);
		$this->sldRelated->Name = _sp("Related Products");
		$this->sldRelated->sliderTitle = _sp("Related Products");
		$this->sldRelated->SetProducts($this->arrRelatedProducts);
		$this->sldRelated->Template = templateNamed('slider.tpl.php');
	}

	/**
	 * build_size_widget - populates options to use with the size listbox
	 * @param none
	 * @return none
	 */
	protected function build_size_widget() {
		$this->lstSize  = new XLSListBox($this->mainPnl);
        $this->PopulateMatrixSize();
	}

	/**
	 * build_color_widget - populates options to use with the color listbox
	 * @param none
	 * @return none
	 */
	protected function build_color_widget() {
        $this->lstColor  = new XLSListBox($this->mainPnl);
        $this->PopulateMatrixColor();
	}

	protected function update_matrix_widgets() {
		if ($this->lstSize->ItemCount <= 1)
			$this->lstSize->Visible = false;

		elseif ($this->lstSize->ItemCount == 2)
			$this->lstSize->SelectedIndex = 1;

		if ($this->lstColor->ItemCount <= 1)
			$this->lstColor->Visible = false;

		elseif ($this->lstColor->ItemCount == 2)
			$this->lstColor->SelectedIndex = 1;

		if ($this->lstColor->ItemCount == 2 && $this->lstSize->ItemCount == 2)
			$this->color_size_change("xlsws_product", $this->lstSize->ControlId, '');
	}

	/**
	 * build_widgets - builds the widgets needed for the template
	 * @param none
	 * @return none
	 */
	protected function build_widgets() {
		$this->build_registry_dropdown();
		$this->build_registry_add();
		$this->build_registry_cancel();
		$this->build_slider();
		if(count($this->arrOptionProds) >0 ) {
			$this->build_size_widget();
			$this->build_color_widget();
			$this->update_matrix_widgets();
		}

		else {
			$this->lstSize = new XLSListBox($this->mainPnl);
			$this->lstSize->Display=false;

			$this->lstColor = new XLSListBox($this->mainPnl);
			$this->lstColor->Display=false;
		}
	}

	/**
	 * render_detail_lightbox - builds the product detail lightbox page
	 * @param none
	 * @return none
	 */
	protected function render_detail_lightbox() {
		$this->menuPnl->Display = false;
		$this->cartPnl->Display = false;
		$this->sidePnl->Display = false;

		QApplication::ExecuteJavaScript('$("#nav").toggle(false)');
		QApplication::ExecuteJavaScript('$("#login").toggle(false)');
		QApplication::ExecuteJavaScript('$("#footer").toggle(false)');
		QApplication::ExecuteJavaScript('$("#rightside").toggle(false)');

		QApplication::ExecuteJavaScript('$("body").css("width", "600px")');
		QApplication::ExecuteJavaScript('$("#container").css("width", "780px")');
		QApplication::ExecuteJavaScript('$("#content").css("width", "760px")');
		QApplication::ExecuteJavaScript('$("#right").css("overflow", "scroll")');
		QApplication::ExecuteJavaScript('$("#header a").attr("href", "javascript:{void(0);}")');
	}

	/**
	 * bind_widgets - binds callback actions for the widgets
	 * @param none
	 * @return none
	 */
	protected function bind_widgets() {
		$this->misc_components['add_to_cart']->AddAction(new QClickEvent(), new QAutoTempDisabledAjaxAction('prod_add_to_cart'));
		$this->misc_components['add_to_cart']->AddAction(new QClickEvent(), new QTerminateAction());

		if($this->isLoggedIn()) {
			$this->misc_components['show_gift_registry']->AddAction(new QClickEvent(), new QAutoTempDisabledAjaxAction('display_gift_registry'));
			$this->misc_components['show_gift_registry']->AddAction(new QClickEvent(), new QTerminateAction());
		}

		else {
			$this->misc_components['show_gift_registry']->AddAction(new QClickEvent(), new QConfirmAction(_sp('You need to be logged in for adding to Wish List. Do you want to login now?')));
			$this->misc_components['show_gift_registry']->AddAction(new QClickEvent(), new QAutoTempDisabledAjaxAction('showLoginOrLogout'));
			$this->misc_components['show_gift_registry']->AddAction(new QClickEvent(), new QTerminateAction());
		}

		$this->misc_components['add_gift_registry']->AddAction(new QClickEvent(), new QAutoTempDisabledAjaxAction("addToRegistry"));
		$this->misc_components['cancel_gift_registry']->AddAction(new QClickEvent(), new QAutoTempDisabledAjaxAction('display_gift_registry'));
		$this->txtQty->AddAction(new QClickEvent(0,"this.value=='" . _sp('Qty'). "'") , new QJavaScriptAction("this.value=1;"));
		$this->txtQty->AddAction(new QChangeEvent() , new QAjaxAction("update_qty_price"));

		if(count($this->arrOptionProds) >0 ) {
			$this->lstSize->AddAction(new QChangeEvent(0,"this.selectedIndex == 0") , new QJavaScriptAction("this.selectedIndex=1;"));
			$this->lstSize->AddAction(new QChangeEvent() , new QAjaxAction('color_size_change'));
			$this->lstColor->AddAction(new QChangeEvent(0,"this.selectedIndex == 0") , new QJavaScriptAction("this.selectedIndex=1;"));
			$this->lstColor->AddAction(new QChangeEvent() , new QAjaxAction('color_size_change'));
		}

		$this->pxyEnlarge->AddAction(new QClickEvent() , new QAjaxAction('doImageEnlarge'));
		$this->pxyEnlarge->AddAction(new QClickEvent() , new QTerminateAction());
	}

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		global $XLSWS_VARS;

		define('NO_MAIN_PANEL_AUTO_RENDER' , true);
		/*contruct all variables and only label widgets should be defined here to maintain layout*/
		$this->prod =  $this->origin_prod = Product::LoadByCode($XLSWS_VARS['product']);

		$this->mainPnl = new QPanel($this);

		if($this->prod && $this->prod->Web){
			if ($XLSWS_VARS['ajax'] == "true") {
				$this->lightbox = true;
				$this->mainPnl->Template = templateNamed('product_detail_lightbox.tpl.php');
			} else {
				$this->lightbox = false;
				$this->mainPnl->Template = templateNamed('product_detail.tpl.php');
			}

			Visitor::add_view_log($this->prod->Rowid,ViewLogType::productview);
		} else {
			_xls_log("Product not found in product.php -> $XLSWS_VARS[product]");
			_xls_display_msg("Sorry! Product not found.");
		}

		$this->masterProductId = $this->prod->Rowid;

		/*create the add to cart proxy*/
		$this->misc_components['add_to_cart'] = new QControlProxy($this);

		/******************* Gift Registry ************************/
		$this->giftRegistryPnl = new QPanel($this->mainPnl);
		$this->giftRegistryPnl->Template = templateNamed('product_detail_add_gift_popup.tpl.php');
		$this->giftRegistryPnl->Visible = false;

		$this->misc_components['show_gift_registry'] = new QControlProxy($this);

		/******************* Gift Registry ************************/
		// Image - drag and drop

		$this->pnlImgHolder = new QPanel($this->mainPnl , 'prodImageHolder');
		$this->pnlImgHolder->HtmlEntities = false;
		$this->pnlImgHolder->AutoRenderChildren = true;

		$this->create_prod_image();

		$this->pnlAdditionalProdImages = new QPanel($this);
		$this->pnlAdditionalProdImages->Template = templateNamed('product_detail_additional_images.tpl.php');
		$this->PopulateAdditionalImagesPnl();

		// add desc
		if($this->prod->MetaDesc != '')
			_xls_add_meta_desc($this->prod->MetaDesc);
		else
			_xls_add_meta_desc($this->prod->Name);

		if($this->prod->MetaKeyword != '')
			_xls_add_meta_keyword($this->prod->MetaKeyword);
		else
			_xls_add_meta_keyword(array($this->prod->Name , $this->prod->Code , $this->prod->WebKeyword1 , $this->prod->WebKeyword2 , $this->prod->WebKeyword3));

		_xls_add_page_title($this->prod->Name . " - " .  $this->prod->Code);

		// Stock
		$this->lblStock = new QLabel($this->mainPnl);
		$this->lblStock->Text = $this->prod->InventoryDisplay();

		//Title
		$this->lblTitle = new QLabel($this->mainPnl);
		$this->lblTitle->Text = $this->prod->Name;

		// Description
		$this->lblDescription = new QLabel($this->mainPnl);
		if (_xls_get_conf('HTML_DESCRIPTION') == 0)
			$this->lblDescription->Text = nl2br($this->prod->Description);
		else
			$this->lblDescription->Text = $this->prod->Description;

		$this->lblDescription->HtmlEntities = false;

		//price
		$this->lblPrice = new QLabel($this->mainPnl);
		if ($this->prod->MasterModel && _xls_get_conf('MATRIX_PRICE') == 1)
			$this->lblPrice->Text = _sp("choose options for pricing");
		else
			$this->lblPrice->Text = _xls_currency($this->prod->Price);

		// Load Options
		$this->arrOptionProds = Product::LoadArrayByFkProductMasterId($this->prod->Rowid);

		$this->dxImage = new XLSImagePopup($this->prod , $this);
		$this->dxImage->Visible = false;
		$this->pxyEnlarge = new QControlProxy($this);

		$this->mainPnl->AutoRenderChildren = false;

		$this->build_widgets();

		// Qty (UNUSED ON PRODUCT DETAIL PRESENTLY)
		$this->txtQty = new QTextBox($this->mainPnl);
		$this->txtQty->Text = _sp('Qty');

		if(!_xls_get_conf('DISABLE_CART' , false))
			$this->txtQty->Visible = false;

		$this->bind_widgets();

		if ($this->lightbox)
			$this->render_detail_lightbox();

	}

	protected function HasMatrixOptions() {
		if ($this->lstColor->ItemCount <= 1 && $this->lstSize->ItemCount <= 1)
			return false;
		return true;
	}

	protected function GetMatrixSelection() {
		foreach ($this->arrOptionProds as $objProduct)
			if ($this->lstSize->SelectedValue == $objProduct->ProductSize &&
			 $this->lstColor->SelectedValue == $objProduct->ProductColor){
				if (!$objProduct->ImageId)
					$objProduct->ImageId = $this->origin_prod->ImageId;
				return $objProduct;
			}
		return;
	}

	protected function HasMatrixSelection() {
		if ($this->GetMatrixSelection())
			return true;
		return false;
	}

	protected function ValidateMatrix() {
		$objProduct = $this->prod;

		if ($objProduct->IsMaster) {
			if (!$this->HasMatrixOptions()) {
				_qalert(_sp(_xls_get_conf('INVENTORY_ZERO_NEG_TITLE', 'Please Call')));
				return;
			}

			$objProduct = $this->GetMatrixSelection();

			if (!$objProduct) {
				_qalert(sprintf(_sp('Please choose a valid %s/%s option.'),
					$this->prod->SizeLabel, $this->prod->ColorLabel));

				if ($this->lstColor->Visible)
					$this->lstColor->SetFocus();

				else if ($this->lstSize->Visible)
					$this->lstSize->SetFocus();

				return;
			}
		}

		return $objProduct;
	}

    protected function PopulateMatrixSize($strColor = false) {
        $this->lstSize->RemoveAllItems();

        $this->lstSize->AddItem(
            sprintf(
                _sp($this->strMatrixPlaceholder), 
                $this->origin_prod->SizeLabel
            ), null
        );

        $strOptionsArray = array();

        foreach ($this->arrOptionProds as $objProduct) {
            $strSize = $objProduct->ProductSize;

            if ($strSize == '')
                continue;

            if ($strColor && $objProduct->ProductColor != $strColor)
                continue;

            if (in_array($strSize, $strOptionsArray))
                continue;

            if (!$objProduct->IsAvailable) 
                continue;

            $strOptionsArray[] = $strSize;
            $this->lstSize->AddItem($strSize, $strSize);
        }
    }

    protected function PopulateMatrixColor($strSize = false) {
        $this->lstColor->RemoveAllItems();

        $this->lstColor->AddItem(
            sprintf(
                _sp($this->strMatrixPlaceholder), 
                $this->origin_prod->ColorLabel
            ), null
        );

        $strOptionsArray = array();

        foreach ($this->arrOptionProds as $objProduct) {
            $strColor = $objProduct->ProductColor;

            if ($strColor == '')
                continue;

            if ($strSize && $objProduct->ProductSize != $strSize)
                continue;

            if (in_array($strColor, $strOptionsArray))
                continue;

            if (!$objProduct->IsAvailable) 
                continue;

            $strOptionsArray[] = $strColor;
            $this->lstColor->AddItem($strColor, $strColor);
        }
    }

	/**
	 * valid_option - checks if a selected size color option is a valid option in the matrix
	 * @param none
	 * @return none
	 */
	protected function ValidateMatrixSelection () {
		if($this->lstColor->ItemCount <= 1 && $this->lstSize->ItemCount <= 1)
			return false;

		$strSize = $this->lstSize->SelectedValue;
		$strColor = $this->lstColor->SelectedValue;

		if (($this->lstColor->ItemCount > 1)) {
			if ($this->lstColor->SelectedIndex <= 0) {
				return false;
			}
		}

		if (($this->lstSize->ItemCount > 1)) {
			if($this->lstSize->SelectedIndex <= 0) {
				return false;
			}
		}

        $objProduct = false;

		// validate  color-size exist
		foreach ($this->arrOptionProds as $objProduct)
            if ($strSize == $objProduct->ProductSize && 
                $strColor == $objProduct->ProductColor)
    				break;
			else $objProduct = false;

        if (!$objProduct) { 
            $this->lstColor->SelectedIndex = 0;
            _qalert(sprintf(
                _sp('Selected %s/%s option does not exist. Please choose a ' .
                    'different option.'),
                _xls_get_conf('PRODUCT_SIZE_LABEL' , 'Size'),
                _xls_get_conf('PRODUCT_COLOR_LABEL' , 'Color')
            ));
            return false;
        }

		return $objProduct;
	}

    protected function valid_option () {
        QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
        return $this->ValidateMatrixSelection();
    }

	/**
	 * PopulateAdditionalImagesPnl - populates additional images for this product
	 * @param none
	 * @return none
	 */
	protected function PopulateAdditionalImagesPnl() {
		// Additional images
		$images = Images::LoadArrayByProductAsImage($this->prod->Rowid , QQ::Clause(QQ::OrderBy(QQN::Images()->Rowid)));

		$this->arrAdditionalProdImages = $images;

		// Show the main image as well
		if(count($this->arrAdditionalProdImages) > 0)
			$this->arrAdditionalProdImages[] = Images::Load($this->prod->ImageId);

		$this->pnlAdditionalProdImages->Refresh();
	}

	/**
	 * color_size_change - callback function for when a size or color changes in the dropdown selectors
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
    protected function color_size_change($strFormId, $strControlId, 
        $strParameter)
    {

        if (_xls_get_conf('ENABLE_COLOR_FILTER', 0)) { 
            if ($strControlId == $this->lstSize->ControlId) {
                $this->PopulateMatrixColor($this->lstSize->SelectedValue);                
                 if ($this->lstColor->ItemCount == 2)
					$this->lstColor->SelectedIndex = 1;
				 else
				 	$this->lstColor->SelectedIndex = 0;
            }
        }

        $prod = $this->ValidateMatrixSelection();
        if (!$prod)
            return;

		$this->prod = $prod;
		$this->update_qty_price($strFormId, $strControlId, $strParameter);
		$this->lblStock->Text = $this->prod->InventoryDisplay();
		$this->lblTitle->Text = $this->prod->Name;
		if (_xls_get_conf('HTML_DESCRIPTION') == 0)
			$this->lblDescription->Text = nl2br($this->prod->Description);
		else
			$this->lblDescription->Text = $this->prod->Description;

		$this->pnlImgHolder->RemoveChildControls(true);
		$this->create_prod_image();
		$this->pnlImgHolder->Refresh();
		$this->PopulateAdditionalImagesPnl();
		$related = ProductRelated::LoadArrayByProductId($this->prod->Rowid , QQ::Clause(QQ::OrderBy(QQN::ProductRelated()->Rowid)));

		$this->build_slider($related);
	}



	/**
	 * get_qty - get the current quantity to be added to cart
	 * @param none
	 * @return none
	 */
	protected function get_qty() {
		$qty = $this->txtQty->Text;

		if(!is_numeric($qty))
			$qty = 1;

		return abs($qty);
	}

	/**
	 * update_qty_price - callback function to check if quantity pricing applies for an item based on how much they added to cart
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function update_qty_price($strFormId, $strControlId, $strParameter) {
		$qty = $this->get_qty();

		$this->lblPrice->Text = _xls_currency($this->prod->GetPrice($qty));

		if(!is_numeric($this->txtQty->Text))
			$this->txtQty->Text = _sp('Qty');
	}

	/**
	 * prod_add_to_cart - callback function for when add to cart is clicked, or when an item is dragged into the cart
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function prod_add_to_cart($strFormId, $strControlId, $strParameter) {
		$objProduct = $objOriginal = $this->prod;
		$objProduct = $this->ValidateMatrix();

		if (!$objProduct)
			return;
		else
			$this->prod = $objProduct;

		// Remove the existing control before adding new
		$this->RemoveControl($this->pnlImg->ControlId);
		$this->create_prod_image();
		$this->pnlImgHolder->Refresh();

		if (Cart::AddToCart($this->prod, $this->get_qty())) {
			// add auto products - only if parent can be added
			foreach($this->autoAddCheckIDs as $id=>$qty) {
				$ctl = $this->GetControl($id);

				if($ctl->Checked){
					$prod = Product::Load($ctl->ActionParameter);
					if($prod)
						Cart::AddToCart($prod , $qty);
				}
			}
		}

		$this->cartPnl->RemoveChildControls(true);
		$this->build_cart();
		$this->cartPnl->Refresh();
		$this->prod = $objOriginal;
	}

	/**
	 * AutoAddCheckBox - create auto add classes with tickmakrs for a line item
	 * @param Product object - the product object for the item you wish to add an auto add tickmark
	 * @param integer quantity - how many of the item should it auto add to cart
	 * @return none
	 */
	public function AutoAddCheckBox($prod , $qty){
		$id = 'pautoadd' . $prod->Rowid;
		$this->autoAddCheckIDs[$id] = $qty;

		$chk = new QCheckBox($this->mainPnl , $id );
		$chk->ActionParameter = $prod->Rowid;
		$chk->Checked = true;

		$chk->Render();
	}

	/******************* gift registry ************************/
	/**
	 * display_gift_registry - display the wish lists in a list box to add an item to
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function display_gift_registry($strFormId, $strControlId, $strParameter) {
		$this->giftRegistryPnl->Visible = !$this->giftRegistryPnl->Visible;
	}

	/**
	 * addToRegistry - callback function for when add to wish list is pressed
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function addToRegistry($strFormId, $strControlId, $strParameter) {
		$objOriginal = $this->prod;
		$objProduct = $this->ValidateMatrix();

		if (!$objProduct) return;
		else $this->prod = $objProduct;

		//$this->prod = Product::LoadByCode($prod->Code);
		if ($this->misc_components['select_gift_registry']->SelectedValue == '') {
			_qalert(_sp('Please Select a Wish List.'));
			return;
		}

		$count = GiftRegistryItems::QueryArray(
			QQ::AndCondition(
				QQ::Equal(
					QQN::GiftRegistryItems()->ProductId,
					$this->prod->Rowid),
				QQ::Equal(
					QQN::GiftRegistryItems()->RegistryId,
					$this->misc_components['select_gift_registry']->SelectedValue
				)
			)
		);

		$objGiftItem = new GiftRegistryItems();
		$objGiftItem->RegistryId =
			$this->misc_components['select_gift_registry']->SelectedValue;
		$objGiftItem->ProductId = $this->prod->Rowid;
		$objGiftItem->Qty = $this->get_qty();
		$objGiftItem->PurchaseStatus=0;
		$objGiftItem->RegistryStatus='0';
		$objGiftItem->Created= new QDateTime(QDateTime::Now);
		$objGiftItem->Save();

		// add auto products
		foreach($this->autoAddCheckIDs as $id=>$qty) {
			$ctl = $this->GetControl($id);

			if($ctl->Checked) {
				$prod = Product::Load($ctl->ActionParameter);

				if($prod) {
					for($i=1; $i <=$qty; $i++) {
						$objGiftItem = new GiftRegistryItems();
						$objGiftItem->RegistryId = $this->misc_components['select_gift_registry']->SelectedValue;
						$objGiftItem->ProductId = $prod->Rowid;
						$objGiftItem->Qty = $this->get_qty();
						$objGiftItem->PurchaseStatus=0;
						$objGiftItem->RegistryStatus='0';
						$objGiftItem->Created= new QDateTime(QDateTime::Now);
						$objGiftItem->Save();
					}
				}
			}
		}

		QApplication::ExecuteJavaScript("alert('Successfully Added to Wish List.');", true);
		$this->giftRegistryPnl->Visible = false;
		$this->prod = $objOriginal;
	}
	/******************* gift registry ************************/


	/**
	 * doImageEnlarge - enlarges a selected image in a lightbox
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function doImageEnlarge($strFormId, $strControlId, $strParameter) {
		if($strParameter == $this->prod->Code)
			$strParameter = false;

		if(_xls_get_conf('PRODUCT_ENLARGE_SHOW_LIGHTBOX' , 1))
			$this->dxImage->doShow($this->prod , $strParameter?$strParameter:false);
		else {
			if(!$strParameter)
				return;

			$this->prod->ImageId = $strParameter;

			$this->pnlImgHolder->RemoveChildControls(true);

			$this->create_prod_image();
		}
	}

	/**
	 * create_prod_image - creates the primary image for a product
	 * @param none
	 * @return none
	 */
	protected function create_prod_image() {
		$this->pnlImg = $this->create_prod_img($this->pnlImgHolder , $this->prod , 'PDetailImage' , _xls_get_conf('DETAIL_IMAGE_WIDTH',100) , _xls_get_conf('DETAIL_IMAGE_HEIGHT',80) , 'prod_add_to_cart');
		$this->pnlImg->RemoveAllActions('onclick');
		$this->pnlImg->AddAction(new QClickEvent() , new QAjaxAction('doImageEnlarge'));
		$this->pnlImg->CssClass = 'product_detail_image';
		$this->pnlImg->Padding = 0;
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_product::Run('xlsws_product', templateNamed('index.tpl.php'));
