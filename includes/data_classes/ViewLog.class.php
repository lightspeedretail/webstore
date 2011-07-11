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
	require(__DATAGEN_CLASSES__ . '/ViewLogGen.class.php');

	/**
	 * The ViewLog class defined here contains any
	 * customized code for the ViewLog class in the
	 * Object Relational Model.  It represents the "xlsws_view_log" table 
	 * in the database, and extends from the code generated abstract ViewLogGen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 * 
	 * @package My Application
	 * @subpackage DataObjects
	 * 
	 */
	class ViewLog extends ViewLogGen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $objViewLog->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('ViewLog Object %s',  $this->intRowid);
		}



		// Override or Create New Properties and Variables
		// For performance reasons, these variables and __set and __get override methods
		// are commented out.  But if you wish to implement or override any
		// of the data generated properties, please feel free to uncomment them.

		protected $strSomeNewProperty;

		public function __get($strName) {
			switch ($strName) {
				case 'VisitorId':
					$visitor = $this->Visitor;
					$customer = false;
					if($visitor)
						$customer = $visitor->Customer;
						
					if($customer)
						return $customer->Mainname;
					elseif($visitor)
						return sprintf(_sp('Visitor from %s') , $visitor->Host);
					else
						return _sp('Unknown visitor');
					break;	
				case 'Vars':
					$prod = Product::Load($this->intResourceId);
					
					if($this->intLogTypeId == ViewLogType::productview  && $prod)
						return sprintf(_sp("Viewed product %s") , $prod->Code);
					elseif($this->intLogTypeId == ViewLogType::productcartadd  && $prod)
						return sprintf(_sp("Added product %s to cart") , $prod->Code);
					elseif($this->intLogTypeId == ViewLogType::categoryview  && ($categ = Category::Load($this->intResourceId)))
						return sprintf(_sp("Viewed category %s") , $categ->Name);
					elseif($this->intLogTypeId == ViewLogType::familyview  && ($family = Family::Load($this->intResourceId)))
						return sprintf(_sp("Viewed family %s") , $family->Family);
					elseif($this->intLogTypeId == ViewLogType::giftregistryview  && ($gr = GiftRegistry::Load($this->intResourceId)))
						return sprintf(_sp("Viewed gift registry %s") , $gr->RegistryName);
					elseif($this->intLogTypeId == ViewLogType::search )
						return sprintf(_sp("Searched for %s") , $this->strVars);
					elseif($this->intLogTypeId == ViewLogType::customerlogin )
						return sprintf(_sp("Logged in from %s") , $this->Visitor?$this->Visitor->Host:'');
					elseif($this->intLogTypeId == ViewLogType::customerlogout )
						return sprintf(_sp("Logged out from %s") , $this->Visitor?$this->Visitor->Host:'');
					elseif($this->intLogTypeId == ViewLogType::checkoutcustomer )
						return sprintf(_sp("In customer checkout page from %s") , $this->Visitor?$this->Visitor->Host:'');
					elseif($this->intLogTypeId == ViewLogType::checkoutfinal )
						return sprintf(_sp("Final stage of checkout from %s") , $this->Visitor?$this->Visitor->Host:'');
					elseif($this->intLogTypeId == ViewLogType::checkoutshipping )
						return sprintf(_sp("Selecting shipping in checkout from %s") , $this->Visitor?$this->Visitor->Host:'');
					else
						return sprintf(_sp("Visited page %s") , $this->strPage);

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

	}
?>