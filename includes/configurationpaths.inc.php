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
     * Generated paths based on __DOCROOT__ and __SUBDIRECTORY__
     */

    // General Includes (location of the Prepend and Configuration)
    define ('__SITEROOT__', __DOCROOT__ . __SUBDIRECTORY__);
    define ('__INCLUDES__', __SITEROOT__ . '/includes');

    define ('__QCODO__', __INCLUDES__ . '/qcodo');
    define ('__QCODO_CORE__', __INCLUDES__ . '/qcodo/_core');

    // Destination for Code Generated class files
    define ('__DATA_CLASSES__', __INCLUDES__ . '/data_classes');
    define ('__DATAGEN_CLASSES__', __INCLUDES__ . '/data_classes/generated');
    // Define empty constants to prevent Qcodo from generating these
    define ('__FORM_DRAFTS__', '');
    define ('__PANEL_DRAFTS__', '');
    define ('__DATA_META_CONTROLS__', '');
    define ('__DATAGEN_META_CONTROLS__', ''); 

    // Xsilva specific
    define ('__CUSTOM_INCLUDES__', 
        __DOCROOT__ . __SUBDIRECTORY__ . '/custom_includes');
    define ('__XLSWS_INCLUDES__', __INCLUDES__ . '/xlsws');

    define ('CUSTOM_INCLUDES', __CUSTOM_INCLUDES__ . '/');  // LEGACY
    define ('XLSWS_INCLUDES', __SITEROOT__ . '/xlsws_includes/'); // LEGACY
    define('SECIMG_DIR', __INCLUDES__ . '/securimage');

    define ('__DEVTOOLS_CLI__', __XLSWS_INCLUDES__ . '/codegen');

    /**
     * Qcodo expects these to be relative from __DOCROOT__
     */

	//define ('__DEVTOOLS__', __SUBDIRECTORY__ . '/_devtools');
	define ('__JS_ASSETS__', __SUBDIRECTORY__ . '/assets/js');
	define ('__CSS_ASSETS__', __SUBDIRECTORY__ . '/assets/css');
	define ('__IMAGE_ASSETS__', __SUBDIRECTORY__ . '/assets/images');
	define ('__PHP_ASSETS__', __SUBDIRECTORY__ . '/assets/php');
    define ('__PHOTOS__', __SUBDIRECTORY__ . '/photos');
    define ('__CAPTCHA_ASSETS__', __SUBDIRECTORY__ . '/includes/securimage');

    define('ERROR_PAGE_PATH', __PHP_ASSETS__ . '/_core/error_page.php');

?>
