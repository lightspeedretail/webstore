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

/*
 * XLSSlider
 *
 * Extends class used to create slider display
 */

class XLSSlider extends QPanel {

	protected $strCssClass = 'products_slider_theme_bg';
	public $links;
	public $sliderCount = 4;
	public $sliderTitle;

	public function GetEndScript() {
		$str = parent::GetEndScript();
		return '$(function() { $( "#' . $this->strControlId .
			'" ).productSlider({ speed : "normal", slideBy : ' .
			$this->sliderCount .' }); });' . $str;
	}

	public function SetProducts($qq, $clause = null) {
		$products = array();
		if ($qq instanceof QQCondition)
			$products = Product::QueryArray($qq, $clause);
		else if (is_array($qq)) {
			$products = $qq;
		}
		else
			return;

		$this->links = array();
		foreach($products as $prod) {
			$this->links[$prod->Rowid] = array();
			$this->links[$prod->Rowid]['image'] = $prod->SmallImage;
			$this->links[$prod->Rowid]['link'] = $prod->Link;
			$this->links[$prod->Rowid]['title'] = $prod->Name;
			$this->links[$prod->Rowid]['title2'] = $prod->Code;
		}
	}
}
