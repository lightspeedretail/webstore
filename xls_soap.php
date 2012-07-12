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

/*** THIS SCRIPT IS SOLELY USED BY THE LIGHTSPEED SOFTWARE FOR PUSH AND PULL REQUESTS VIA SOAP, DO NOT MODIFY ***/
ini_set('soap.wsdl_cache_enabled',false);
ob_start();
require_once('xls_ws_service.php');
ob_end_clean();

define('XLSWS_SOAP' , true);

if(_xls_get_conf('DEBUG_LS_SOAP_CALL' , false)  && isset($GLOBALS['HTTP_RAW_POST_DATA']))
	_xls_log("SOAP DEBUG : " . print_r($GLOBALS['HTTP_RAW_POST_DATA'] , true));

// ENABLE SOAP Debugging by the following SQL:
// INSERT INTO `xlsws_configuration` (`rowid`, `title`, `key`, `value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`) VALUES (NULL, 'Debug', 'DEBUG_LS_SOAP_CALL', '1', 'Debug', 0, 1, '2009-06-20 00:48:46', '2009-03-16 16:58:08', NULL);

XLSWService::Run('XLSWService', isset($_SERVER['HTTPS_HOST'])?('https://' . $_SERVER['HTTPS_HOST']):('http://' . $_SERVER['HTTP_HOST']));

?>