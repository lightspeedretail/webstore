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

// Initialize code base
require_once('includes/prepend.inc.php');

// LEGACY :: Initialize custom code base
ob_start();
require_once(CUSTOM_INCLUDES . 'prepend.inc.php');
ob_end_clean();


// If store is offline then show offline
if ($offlinekey = _xls_get_conf('STORE_OFFLINE' , '')) {
	// User knows the key and is requesting initial access
	if (isset($_GET['xls_offlinekey']) &&
	   ($offlinekey == $_GET['xls_offlinekey'])) {
		_xls_stack_add('xls_offlinekey' , $offlinekey);
		_rd(_xls_site_dir());
	}

	// User does not know the key
	if(_xls_stack_get('xls_offlinekey') != $offlinekey){
		include(templateNamed('offline.tpl.php'));
		exit();
	}
}


//Initialize our global URL parser so we can access from anywhere
$objUrl = XLSURLParser::getInstance();

if ($objUrl==false) die("A severe error has occurred that should redirect to a 404 page.");


if ($objUrl->Status==301) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$objUrl->RedirectUrl);
	exit();
}

if ($objUrl->Status==404) {
	header('HTTP/1.1 404 Not Found');
	$objUrl->RouteDepartment=404;
}

// Cache categories since they are used throughout
Category::$Manager->AddArray(
	Category::LoadAll()
);

//These may be changed later in processing, but set here to have it just in case
_xls_stack_put('xls_canonical_url',_xls_site_url($objUrl->Uri));
_xls_add_page_title(_xls_get_conf('STORE_NAME' , 'XSilva Web Store'));

//error_log("on dept ".$objUrl->RouteDepartment." ".$objUrl->RouteId);

switch ($objUrl->RouteDepartment)
{
	case 'category':
	case 'custom_page':
	case 'customer_register':
	case 'family':
	case 'product':
	case 'searchresults':
	case '404':
	
		$strFile = $objUrl->RouteDepartment.".php";
		break;
		
	case 'feeds':
		$strFile = "feeds/".$objUrl->RouteId.".php";
		break;		

	case 'xlspg':
		$strFile = $objUrl->RouteId.".php";
		break;
	
}

if (isset($strFile)) {
	if(file_exists(CUSTOM_INCLUDES . $strFile))
			include(CUSTOM_INCLUDES . $strFile);
		elseif(file_exists('xlsws_includes/'.$strFile))
			include('xlsws_includes/'.$strFile);
		else {
			header('HTTP/1.0 404 Not Found');
	  		if (!readfile('404missing.html'))
	  			echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">' . chr(13) .
					'<html><head>' . chr(13) .
					'<title>404 Not Found</title>' . chr(13) .
					'</head><body>' . chr(13) .
					'<h1>Not Found</h1>' . chr(13) .
					'<p>The requested URL '.$objUrl->Uri.' was not found on this server.</p>' . chr(13) .
					'</body></html>';
	
	  		exit();
			}
}

error_log("hit herE");
// Print out any image data then exit
foreach (ImagesType::$NameArray as $strType) {
	if (!isset($_GET[$strType]))
		continue;

	$intType = ImagesType::ToToken($strType);

	$imgid = $_GET[$strType];
	$imgid = trim($imgid);

	if (!empty($imgid))
		$img = Images::Load($imgid);

	if (!$img) {
		$img = new Images();
		if ($intType == ImagesType::normal)
			$img->Width = $img->Height = 256;
	}

	if ($intType == ImagesType::normal) {
		$img->Show();
		exit;
	}

	list($intWidth, $intHeight) = ImagesType::GetSize($intType);
	$img->ShowThumb($intWidth, $intHeight);

	break;
}


