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
 
 function _xls_sitemap_xml_url2($url , $lastmod = false , $changefreq = 'weekly', $priority = '0.8') {
	
	return  
    '  <url>  
        <loc>'.$url.'</loc>  
        ' . ($lastmod != '' ? '<lastmod>'.$lastmod.'</lastmod>' : ''). 
        '<changefreq>'.$changefreq.'</changefreq>  
        <priority>'.$priority.'</priority>  
        </url>  
    '; 
    
    
}

 
 header("Content-Type: text/xml;charset=UTF-8");
 
 	$ret = "";
	$strSiteDir = _xls_site_dir();

	echo('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
	echo('<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    ');
            
	// index page
	echo(_xls_sitemap_xml_url2($strSiteDir));
	// sitemap page
	echo(_xls_sitemap_xml_url2($strSiteDir . "/sitemap.xml"));

	$categories = Category::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Category()->Rowid)));

	foreach($categories as $category){
		$ret .=  _sp("Generating URL for category") .
			$category->Name . "\n";

		echo(_xls_sitemap_xml_url2($strSiteDir . "/" . $category->Link,
				date("c",strtotime($category->Modified)),
				'weekly'));

	}

	$products = Product::QueryArray(
		QQ::AndCondition(QQ::Equal(QQN::Product()->Web, 1),
			QQ::OrCondition(
				QQ::Equal(QQN::Product()->MasterModel, 1),
				QQ::AndCondition(
					QQ::Equal(QQN::Product()->MasterModel, 0),
					QQ::Equal(QQN::Product()->FkProductMasterId, 0)
				)
			)
		),
		QQ::Clause(QQ::OrderBy(QQN::Product()->Code))
	);

	foreach($products as $product) {
		$ret .=  _sp("Generating URL for product") . " $product->Code " . "\n";


			echo(_xls_sitemap_xml_url2($strSiteDir . "/" . $product->Link,
				date("c",strtotime($product->Modified)),
					'daily',
					($product->Featured ? '0.8' : '0.5')));
		
	}

	$pages = CustomPage::LoadAll();

	foreach($pages as $page) {
		$ret .=  _sp("Generating url for page ") . $page->Title . "\n";


			echo(_xls_sitemap_xml_url2($strSiteDir . "/" . $page->Link,
				date("c",strtotime($page->Modified)),
				'weekly'));


	}

	echo( '</urlset>' . "\n");

 QApplication::Log(E_ERROR, 'Sitemap', $ret);