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

// Versioning Information
define('XLSWS_VERSION', '2.1.5');

// Define default values
define('XLS_TRUNCATE_PUNCTUATIONS', ".!?:;,-");
define('XLS_TRUNCATE_SPACE', " ");
define('ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and');

// Load in core functions
require(__XLSWS_INCLUDES__ . '/_functions.php');
require(__XLSWS_INCLUDES__ . '/_qextend.php');

// Add xlsws autoload include paths to QApplication
QApplication::$ClassPath[] = __XLSWS_INCLUDES__;
QApplication::$ClassPath[] = __XLSWS_INCLUDES__ . '/core';
QApplication::$ClassPath[] = __XLSWS_INCLUDES__ . '/qform';
QApplication::$ClassPath[] = __XLSWS_INCLUDES__ . '/view';

// Register custom data classes / types
QApplication::$ClassFile['imagestype'] =
	__DATA_CLASSES__ . '/ImagesType.class.php';

QApplication::$ClassFile['promocodetype'] =
	__DATA_CLASSES__ . '/PromoCodeType.class.php';

// Register xlsws static class path definitions to QApplication
QApplication::$ClassFile['xlssessionhandlerbase'] =
	__XLSWS_INCLUDES__ . '/core/session/XLSSessionHandlerBase.class.php';
QApplication::$ClassFile['xlssessionhandler'] =
	__XLSWS_INCLUDES__ . '/core/session/XLSSessionHandler.class.php';

// Register custom Form State Handler
QApplication::$ClassFile['xlsformstatehandler'] =
	__XLSWS_INCLUDES__ . '/qform/XLSFormStateHandler.class.php';

// Register Object managers for semi-persistent storage
QApplication::$ClassFile['xlsobjectmanager'] =
	__XLSWS_INCLUDES__ . '/core/XLSObjectManager.class.php';
QApplication::$ClassFile['xlsnestedobjectmanager'] =
	__XLSWS_INCLUDES__ . '/core/XLSObjectManager.class.php';

QApplication::$ClassFile['xlsconfigurationmanager'] =
	__XLSWS_INCLUDES__ . '/core/XLSDataClassManager.class.php';
QApplication::$ClassFile['xlscategorymanager'] =
	__XLSWS_INCLUDES__ . '/core/XLSDataClassManager.class.php';
QApplication::$ClassFile['xlsproductmanager'] =
	__XLSWS_INCLUDES__ . '/core/XLSDataClassManager.class.php';
QApplication::$ClassFile['xlscartitemmanager'] =
	__XLSWS_INCLUDES__ . '/core/XLSDataClassManager.class.php';

// Register shipping modules
QApplication::$ClassFile['xlsws_class_shipping'] =
	__XLSWS_INCLUDES__ . '/shipping/xlsws_class_shipping.class.php';

// Register payment modules
QApplication::$ClassFile['xlsws_class_payment'] =
	__XLSWS_INCLUDES__ . '/payment/xlsws_class_payment.class.php';

// Register sidebar modules
QApplication::$ClassFile['xlsws_class_sidebar'] =
	__XLSWS_INCLUDES__ . '/sidebar/xlsws_class_sidebar.class.php';
QApplication::$ClassFile['xlsws_class_sidebar_qp'] =
	__XLSWS_INCLUDES__ . '/sidebar/xlsws_class_sidebar.class.php';
QApplication::$ClassFile['sidebar_order_lookup_qp'] =
	XLSWS_INCLUDES . 'sidebar/sidebar_order_lookup.php';
QApplication::$ClassFile['sidebar_order_lookup'] =
	XLSWS_INCLUDES . 'sidebar/sidebar_order_lookup.php';

// Register Views
QApplication::$ClassFile['xlsws_index'] =
	XLSWS_INCLUDES . 'skeleton.php';
QApplication::$ClassFile['xlsws_product_listing'] =
	XLSWS_INCLUDES . 'product_listing.php';

// Register customized widgets
QApplication::$ClassFile['xlszipfield'] =
	__XLSWS_INCLUDES__ . '/qform/XLSZipField.class.php';
