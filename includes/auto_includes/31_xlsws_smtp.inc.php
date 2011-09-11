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

    if (!empty(QApplication::$Database)) {
        if (!defined('__PREPEND_QUICKINIT__')) {
            $strUrl = _xls_get_conf('EMAIL_SMTP_SERVER', false);
            $strPort = _xls_get_conf('EMAIL_SMTP_PORT', '25');

            if ($strUrl) { 
            	switch(_xls_get_conf('EMAIL_SMTP_SECURITY_MODE', '0')) {
            		case 1: break;
            		case 2: $strUrl = 'ssl://' . $strUrl; break;
            		case 3: $strUrl = 'tls://' . $strUrl; break;
            	
            		default: 
	            		switch ($strPort) {
		                    case '465':
		                    case '995':
		                        $strUrl = 'ssl://' . $strUrl;
		                        break;
		                    case '587': 
		                        $strUrl = 'tls://' . $strUrl; 
		                        break;
		                }
		              }

                QEmailServer::$SmtpServer = $strUrl;
                QEmailServer::$SmtpPort = $strPort;

                if (_xls_get_conf('EMAIL_SMTP_USERNAME',false)) { 
                    QEmailServer::$AuthLogin = true;
                    QEmailServer::$SmtpUsername = 
                        _xls_get_conf('EMAIL_SMTP_USERNAME');
                    QEmailServer::$SmtpPassword = 
                        _xls_get_conf('EMAIL_SMTP_PASSWORD');
                }
            }
        }
    }

?>
