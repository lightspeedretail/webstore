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
        $strPort = '25';

        if ($strUrl) {
            switch (_xls_get_conf('EMAIL_SMTP_SSL_MODE', '0')) {
                case '0':
                    $strPort = '25';
                    break;
                case '1':
                    $strUrl = 'ssl://' . $strUrl;
                    $strPort = '465';
                    break;
                case '2':
                    $strUrl = 'tls://' . $strUrl;
                    $strPort = '465';
                    break;
                default:
                    QApplication::Log(E_WARNING, 'smtp',
                        _sp('Invalid SMTP SSL mode defined'));
                    break;
            }
        }

        QEmailServer::$SmtpServer = $strUrl;
        QEmailServer::$SmtpPort = _xls_get_conf('EMAIL_SMTP_PORT', $strPort);

        if (_xls_get_conf('EMAIL_SMTP_USERNAME', false)) {
            QEmailServer::$AuthLogin = true;
            QEmailServer::$SmtpUsername = 
                _xls_get_conf('EMAIL_SMTP_USERNAME');
            QEmailServer::$SmtpPassword = 
                _xls_get_conf('EMAIL_SMTP_PASSWORD');
        }
    }
}
