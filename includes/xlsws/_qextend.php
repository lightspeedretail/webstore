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
     * Pass a string through the Translator and return
     * @param string $strMsg
     * @return string
     */
    function _sp($strMsg) {
	    return QApplication::Translate($strMsg);
    }

    /**
     * Xsilva safe translator performs _p(_t($strMsg))
     * This has the effect of first translating and then replacing HTML Entities
     * @param string $strMsg
     * @return string
     */
    function _xt($strMsg) {
        if (gettype($strMsg) != 'object')
            print(QApplication::HtmlEntities(
                QApplication::Translate($strMsg)
            ));
        else
            print($strMsg);
    }

    /**
     * Redirect to a given URL
     * @param string $url
     */
    function _rd($url = '') {	
    	global $_SERVER;
	
    	if(empty($url))
    		$url = $_SERVER["REQUEST_URI"];
	
    	QApplicationBase::Redirect($url);
    }

    /**
     * Shortcut for a javascript alert
     * @param string $msg
     */
    function _qalert($msg){
	    $msg = addslashes($msg);
    	QApplication::ExecuteJavaScript("alert('$msg')");
    }

    /**
     * Shortcut to perform a database query
     * May perform either a NonQuery (ex.: update/insert) or a Query (select)
     * @param string $strQuery
     * @param string $queryFunc {NonQuery,Query}
     * @return object $objDbResult
     */
    function _dbx($strQuery , $queryFunc = "NonQuery"){
	    $objDatabase = QApplication::$Database[1];

        // Perform the Query
        $objDbResult = $objDatabase->$queryFunc($strQuery);
    
        return $objDbResult;
    }

    /**
     * Shortcut to perform a database query and return the first result item
     * @param string $strQuery
     * @param string $queryFunc {NonQuery,Query}
     * @return object $objDbResult
     */
    function _dbx_first_cell($strQuery) {
	    $res = _dbx($strQuery , "Query");
		$row = $res->FetchRow();
		
		if(!$row)
		    return false;
			
        return $row[0];
    }
	
?>
