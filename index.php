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
	
		$strFile = $objUrl->RouteDepartment.".php";
		break;
		
	case 'feeds':
		$strFile = "feeds/".$objUrl->RouteId.".php";
		break;		

	case 'xlspg':
		$strFile = $objUrl->RouteId.".php";
		break;
		
	default:
		$strFile = "category.php";
		break;
}

if(file_exists(CUSTOM_INCLUDES . $strFile)) {
		include(CUSTOM_INCLUDES . $strFile);
		exit(); }
	elseif(file_exists('xlsws_includes/'.$strFile)) {
		include('xlsws_includes/'.$strFile);
		exit(); }
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
/*


// Convert SEO friendly URL -- 
if (isset($XLSWS_VARS['seo_rewrite'])) {
	$uriPath = parse_url(QApplication::$RequestUri, PHP_URL_PATH);
	$uriPath = str_replace(__SUBDIRECTORY__, '', $uriPath);
	$uriPath = substr($uriPath, 1, strlen($uriPath));
	$uriPath = str_replace('.html', '', $uriPath);
	$uriPath = rtrim($uriPath, '/');

	$uriPathParts = explode('/', $uriPath);
	$uriPathLower = strtolower($uriPath);

	// Skip index -- nothing to do in SEO
	if ($uriPathLower == "index") {
	}
	// Support for sitemap
	else if ($uriPathLower == "sitemap") {
		$fp = tmpfile();
		_xls_generate_sitemap($fp);
		fseek($fp, 0);
		fpassthru($fp);
		exit();
	}
	else if (count($uriPathParts) > 0) {
		$arrCategories = array();
		$intParent = 0;

		// Load Categories by urlencode Name
		foreach ($uriPathParts as $strSlug) {
			foreach (Category::$Manager->GetBySlug(trim($strSlug))
			as $objMatch) {
				if ($objMatch->Parent == $intParent) {
					$arrCategories[] = $objMatch->Rowid;
					$intParent = $objMatch->Rowid;

					// Once a Category has been loaded, remove from array
					unset($uriPathParts[array_search($strSlug,
						$uriPathParts)]);

					break;
				}
			}
		}

		$_GET['c'] = $XLSWS_VARS['c'] = implode('.', $arrCategories);
	}

	if (count($uriPathParts) > 0) {
		$uriPath = implode('/', $uriPathParts);
		$uriPath = urldecode($uriPath);
		$uriPathLower = strtolower($uriPath);

		if ($product = Product::LoadByCode($uriPath)) {
			$_GET['product'] = $XLSWS_VARS['product'] = $product->Code;
		}
		else if ($page = CustomPage::LoadByKey($uriPath)) {
			$_GET['cpage'] = $XLSWS_VARS['cpage'] = $page->Key;
		}
		else if ($product = Product::QuerySingle(QQ::AndCondition(
			QQ::Equal(QQN::Product()->Name , $uriPath)))) {
			$_GET['product'] = $XLSWS_VARS['product'] = $product->Code;
		}
		else if ($product = Product::QuerySingle(QQ::AndCondition(
			QQ::Equal(QQN::Product()->Description , $uriPath)))) {
			$_GET['product'] = $XLSWS_VARS['product'] = $product->Code;
		}
		else if ($family = Family::LoadByFamily($uriPath)) {
			$_GET['family'] = $XLSWS_VARS['family'] = $family->Family;
		}
		else if ($page = CustomPage::QuerySingle(QQ::AndCondition(
			QQ::Equal(QQN::CustomPage()->Title , $uriPath)))) {
			$_GET['cpage'] =$XLSWS_VARS['cpage'] = $page->Key;
		}
		else {
			_rd(_xls_site_dir() .
				"/index.php?seo_forward=true&search=$uriPath");
		}
	}
}
*/


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


