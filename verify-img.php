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

/** THIS SCRIPT IS USED TO GENERATE THE SECURIMAGE CAPTCHA THAT APPEAR ON THE CHECKOUT AND REGISTRATION PAGES, ALTER AT YOUR OWN RISK **/

$CURDIR = dirname(__FILE__);
$SECIMG_DIR='includes/securimage';

define('__PREPEND_QUICKINIT__', true);
require('includes/prepend.inc.php');

// Ensure we load the database session storage object if needed
if (_xls_get_conf('SESSION_HANDLER') == 'DB') {
	QApplication::$ClassFile['xlssessionhandler'] =
		__XLSWS_INCLUDES__ .
		'/core/session/XLSDBSessionHandler.class.php';
	XLSSessionHandler::$CollectionOverridePhp = true;
}

QApplication::$EnableSession = true;
QApplication::InitializeSession();
$sessname = QApplication::$SessionName;

chdir("$CURDIR/$SECIMG_DIR");
include("securimage.php");

header("Content-type: image/jpeg");
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$img = new Securimage();
$img->session_name = $sessname;

//Change some settings
$img->image_width = 150;
$img->image_height = 60;
$img->perturbation = 0.60;
$img->image_bg_color = new Securimage_Color("#f6f6f6");
$img->multi_text_color = array(
	new Securimage_Color("#3399ff"),
	new Securimage_Color("#3300cc"),
	new Securimage_Color("#3333cc"),
	new Securimage_Color("#6666ff"),
	new Securimage_Color("#99cccc")
);
$img->use_multi_text = true;
$img->text_angle_minimum = -5;
$img->text_angle_maximum = 5;
$img->use_transparent_text = true;
$img->text_transparency_percentage = 25; // 100 = completely transparent
$img->num_lines = 6;
$img->line_color = new Securimage_Color("#eaeaea");
$img->image_signature = '';
$img->signature_color = new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));
$img->use_wordlist = true;

$availBackgrounds = array('bg3.jpg', 'bg4.jpg', 'bg5.jpg', 'bg6.png');
$selBackgroundIdxes = array_rand($availBackgrounds, 1);
$selBackgroundIdx = $selBackgroundIdxes[0];
$img->show("backgrounds/" . $availBackgrounds[$selBackgroundIdxes]); // alternate use:  $img->show('/path/to/background_image.jpg');

?>