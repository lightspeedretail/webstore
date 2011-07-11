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

	require(__DATAGEN_CLASSES__ . '/VisitorGen.class.php');

	/**
	 * The Visitor class defined here contains any
	 * customized code for the Visitor class in the
	 * Object Relational Model.  It represents the "XLSWS_VISITOR" table 
	 * in the database, and extends from the code generated abstract VisitorGen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 * 
	 * @package My Application
	 * @subpackage DataObjects
	 * 
	 */
	class Visitor extends VisitorGen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $objVisitor->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('Visitor Object %s',  $this->intRowid);
		}

		

		public static function initiate_visitor(){			
			if(!isset($_SESSION['XLSWS_VISITOR']) || !$_SESSION['XLSWS_VISITOR']){
				
				$visitor = new Visitor();
				$visitor->Ip = $_SERVER['REMOTE_ADDR'];
				$visitor->Host = _xls_get_ip();
				if(isset($_SERVER['HTTP_USER_AGENT']))
					$visitor->Browser = $_SERVER['HTTP_USER_AGENT'];
				else
					$visitor->Browser = 'UNKNOWN BROWSER';
					
				$visitor->Created = new QDateTime(QDateTime::Now);
			//	$visitor->Modified = new QDateTime(QDateTime::Now);
				
				$visitor->Save();
				
				$_SESSION['XLSWS_VISITOR'] = $visitor;
				
			}
			
		}
		
			
		

		public static function add_view_log($resource, $type , $page =''  , $vars = ''){
			self::initiate_visitor();
			
			if(!$page)
				$page = $_SERVER['REQUEST_URI'];
			
			$page = addslashes($page);
			$vars = addslashes($vars);
			
				
			_dbx("INSERT DELAYED INTO `xlsws_view_log` (`resource_id` , `log_type_id` , `visitor_id` , `page` , `vars`, `created`) VALUES ('$resource' , '$type' , '" . $_SESSION['XLSWS_VISITOR']->Rowid . "'  ,'$page' , '$vars' , now()) " , "NonQuery");
							
		}
		
		
		public static function get_visitor(){			
			self::initiate_visitor();
			
			return $_SESSION['XLSWS_VISITOR'];
			
		}
				
		
		public static function get_visitor_name(){
			$v = self::get_visitor();
			if($v->Customer){
				return $v->Customer->Mainname;
			}else
				return $v->Host;
			
		}
		

		public static function update_with_customer_id($custid){
			$visitor = self::get_visitor();
			$visitor->CustomerId = $custid;
			$visitor->Save(false , true);
			$_SESSION['XLSWS_VISITOR'] = $visitor;
			
		}
		
		
		/**
		 * If customer logs out then initiate a new visitor
		 */
		public static function do_logout(){
			unset($_SESSION['XLSWS_VISITOR']);			
		}
		
	}
?>