<?php
	require(__DATAGEN_CLASSES__ . '/CartMessagesGen.class.php');

	/**
	 * The CartMessages class defined here contains any
	 * customized code for the CartMessages class in the
	 * Object Relational Model.  It represents the "xlsws_cart_messages" table 
	 * in the database, and extends from the code generated abstract CartMessagesGen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage DataObjects
	 * 
	 */
	class CartMessages extends CartMessagesGen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $objCartMessages->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		 
		public static function CreateMessage($intCartId,$strMessage) {
			$msg = new CartMessages;
			$msg->CartId=$intCartId;
			$msg->Message=$strMessage;
			try {
				$msg->Save();

				return true;
				
			} catch(Exception $e) {
				QApplication::Log(E_ERROR, 'CreateMessage', "Error on Cart ".$intCartId." for msg ".$strMessage);
				return false;
			}
		} 
		 
		public function __toString() {
			return sprintf('CartMessages Object %s',  $this->intRowid);
		}

		public static function DeleteByCartId($intCartId) {
			_dbx("delete from xlsws_cart_messages where cart_id=".$intCartId);
			
		}


	}
?>