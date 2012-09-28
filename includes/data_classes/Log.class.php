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

require(__DATAGEN_CLASSES__ . '/LogGen.class.php');

/**
 * The Log class defined here contains any
 * customized code for the Log class in the
 * Object Relational Model.  It represents the "xlsws_log" table
 * in the database, and extends from the code generated abstract LogGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 *
 * @package My Application
 * @subpackage DataObjects
 *
 */
class Log extends LogGen {
	/**
	 * Default "to string" handler
	 * Allows pages to _p()/echo()/print() this object, and to define the default
	 * way this object would be outputted.
	 *
	 * Can also be called directly via $objLog->__toString().
	 *
	 * @return string a nicely formatted string representation of this object
	 */
	 
	 public static function GarbageCollect() {
	 
	 	$intLogRotateDays = _xls_get_conf('LOG_ROTATE_DAYS',0);
        	if ($intLogRotateDays>0) {
        		$LastDate = date('YmdHis', strtotime("-".$intLogRotateDays." days"));
       
        		_dbx("DELETE from xlsws_log where DATE_FORMAT(`created`, '%Y%m%d%H%i%s')<'".$LastDate."'");
        	}
        	
       }
       
	public function __toString() {
		return sprintf('Log Object %s',  $this->intRowid);
	}
}
