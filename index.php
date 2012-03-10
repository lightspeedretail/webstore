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
		_rd('index.php');
	}

	// User does not know the key
	if(_xls_stack_get('xls_offlinekey') != $offlinekey){
		include(templateNamed('offline.tpl.php'));
		exit();
	}
}

// Cache categories since they are used throughout
Category::$Manager->AddArray(
	Category::LoadAll()
);

// Convert SEO friendly URL
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

$strPageTitle = _xls_get_conf('STORE_NAME' , 'XSilva Web Store');
$xlsws_form = 'xlsws_index';

// Print out any image data then exit
// TODO : Refactor the image types collection
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

// Store screen size to visitor log
if (isset($_POST['store_screen'])) {
	$visitor = Visitor::get_visitor();
	$visitor->ScreenRes = $_POST['width'] . 'X' . $_POST['height'];
	$visitor->Save();
}

// View selection process
// TODO : Refactor / Create the autoloader for views
elseif (isset($_GET['xlspg'])) {
	$page = basename($_GET['xlspg']);

	if(file_exists(CUSTOM_INCLUDES . "$page" . ".php"))
		include(CUSTOM_INCLUDES . "$page" . ".php");
	else
		include("xlsws_includes/$page" . ".php");
}

elseif (isset($_GET['product'])) {
	if (file_exists(CUSTOM_INCLUDES . "product.php"))
		include(CUSTOM_INCLUDES . "product.php");
	else
		include('xlsws_includes/product.php');
}

elseif (isset($_GET['customer_register'])) {
	if (file_exists(CUSTOM_INCLUDES . "customer_register.php"))
		include(CUSTOM_INCLUDES . "customer_register.php");
	else
		include('xlsws_includes/customer_register.php');
}

elseif (isset($_GET['search'])) {
	if (file_exists(CUSTOM_INCLUDES . "searchresults.php"))
		include(CUSTOM_INCLUDES . "searchresults.php");
	else
		include('xlsws_includes/searchresults.php');
}

elseif (isset($_GET['family'])) {
	if (file_exists(CUSTOM_INCLUDES . "family.php"))
		include(CUSTOM_INCLUDES . "family.php");
	else
		include('xlsws_includes/family.php');
}

elseif (isset($_GET['cpage'])) {
	if (file_exists(CUSTOM_INCLUDES . "custom_page.php"))
		include(CUSTOM_INCLUDES . "custom_page.php");
	else
		include('xlsws_includes/custom_page.php');
}

elseif ((!isset($XLSWS_VARS['c'])) &&
	($page = CustomPage::LoadByKey('index'))) {

	$XLSWS_VARS['cpage'] = $_GET['cpage'] = 'index';
	if(file_exists(CUSTOM_INCLUDES . "custom_page.php"))
		include(CUSTOM_INCLUDES . "custom_page.php");
	else
		include('xlsws_includes/custom_page.php');
}

else {
	if(file_exists(CUSTOM_INCLUDES . "category.php"))
		include(CUSTOM_INCLUDES . "category.php");
	else
		include('xlsws_includes/category.php');
}

?>
