<?php
	require(__DATAGEN_CLASSES__ . '/ShippingTiersGen.class.php');

	/**
	 * The ShippingTiers class defined here contains any
	 * customized code for the ShippingTiers class in the
	 * Object Relational Model.  It represents the "xlsws_shipping_tiers" table 
	 * in the database, and extends from the code generated abstract ShippingTiersGen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 * 
	 * @package My Application
	 * @subpackage DataObjects
	 * 
	 */
	class ShippingTiers extends ShippingTiersGen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $objShippingTiers->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('ShippingTiers Object %s',  $this->intRowid);
		}


	}
?>