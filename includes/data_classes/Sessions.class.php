<?php
	require(__DATAGEN_CLASSES__ . '/SessionsGen.class.php');

	/**
	 * The Sessions class defined here contains any
	 * customized code for the Sessions class in the
	 * Object Relational Model.  It represents the "xlsws_sessions" table 
	 * in the database, and extends from the code generated abstract SessionsGen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 * 
	 * @package My Application
	 * @subpackage DataObjects
	 * 
	 */
	class Sessions extends SessionsGen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $objSessions->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('Sessions Object %s',  $this->intIntSessionId);
		}

		/**
		 * Load a single Sessions object,
		 * by strVchName Index(es)
		 * @param integer $strVchName
		 * @return Session
		*/
		public static function LoadByVchName($strVchName) {
			return Sessions::QuerySingle(
				QQ::Equal(QQN::Sessions()->VchName, $strVchName)
			);
		}
	}
?>