<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kris
 * Date: 2012-12-28
 * Time: 9:12 AM
 * To change this template use File | Settings | File Templates.
 */

class Sitemap extends CAction
{
	public function run()
	{

		//Load some information we'll use within the loops
		$intStockHandling = _xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0);
		$strQueryAddl = ($intStockHandling == 0 ? " AND inventory_avail>0" : "");


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
		echo($this->sitemapXml($strSiteDir));
		// sitemap page
		echo($this->sitemapXml($strSiteDir . "/sitemap.xml"));

		$categories = Category::model()->findAll();

		foreach($categories as $category){
			$ret .=  _sp("Generating URL for category ") .
				$category->label . "\n";

			echo($this->sitemapXml($category->AbsoluteUrl,
				date("c",strtotime($category->modified)),
				'weekly'));

		}

		$arrProducts=Yii::app()->db->createCommand(
			'SELECT * FROM '.Product::model()->tableName().' WHERE current=1 AND web=1 AND parent IS NULL '.$strQueryAddl.' ORDER BY id'
		)->query();

		while(($arrItem=$arrProducts->read())!==false)
		{

			$objProduct = Product::model()->findByPk($arrItem['id']);

			echo($this->sitemapXml($objProduct->AbsoluteUrl,
				date("c",strtotime($objProduct->modified)),
				'daily',
				($objProduct->featured ? '0.8' : '0.5')));

		}

		$criteria = new CDbCriteria();
		$criteria->condition = 'tab_position > 0';
		$criteria->order = 'tab_position';
		$pages = CustomPage::model()->findAll($criteria);


		foreach($pages as $page) {
			$ret .=  _sp("Generating url for page ") . $page->title . "\n";


			echo($this->sitemapXml($page->Link,
				date("c",strtotime($page->modified)),
				'weekly'));


		}

		echo( '</urlset>' . "\n");
	}




	protected function sitemapXml($url , $lastmod = false , $changefreq = 'weekly', $priority = '0.8') {

		return
			'  <url>  
        <loc>'.$url.'</loc>  
        ' . ($lastmod != '' ? '<lastmod>'.$lastmod.'</lastmod>' : '').
			'<changefreq>'.$changefreq.'</changefreq>  
        <priority>'.$priority.'</priority>  
        </url>  
    ';


	}


}