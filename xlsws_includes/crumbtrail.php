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
/*
This script is used to generate an array with each crumb in the crumbtrail set into $this->crumbtrail which can be used from any view later
*/
	$tmpcrumbs = array();
	
	global $XLSWS_VARS;
	
	//if category OR product is set
    if (isset($XLSWS_VARS['c']) || isset($XLSWS_VARS['product'])) {
		if(isset($XLSWS_VARS['product']) && ($product = Product::LoadByCode($XLSWS_VARS['product']))){

			array_push($tmpcrumbs , array( 'link' => $product->Link , 'tag' => 'product'  ,'name' => $product->Name));
			
			// find the reverse category
            if(empty($XLSWS_VARS['c'])) {
                $ccs = Category::LoadArrayByProduct($product->Rowid);
				if($ccs && (count($ccs) > 0))
					$XLSWS_VARS['c'] = current($ccs)->Rowid;
			}
		}

        $category_id = $XLSWS_VARS['c'];
		$category_id = explode("." , $category_id);

		if(count($category_id) >0 )
			$category_id = $category_id[count($category_id)-1]; // take the last category index

		do{
            $category = Category::Load($category_id);

            $strName = $category->Name;
			if ($category && strlen($category->Name) > 20)
				$strName = substr($category->Name, 0, 17) . "...";

			if($category)
				array_push($tmpcrumbs , array( 'key' => $category_id , 'tag' => 'c' , 'name' => $strName , 'link' => $category->Link));
		}while($category && ($category_id = $category->Parent));
	}
	
	$tmpcrumbs = array_reverse($tmpcrumbs);
	
	$crumbs = array();
	$last = array();
	//$crumbs[] = array('key' => 'root', 'name' => 'Store' , 'case' => 'first');
	foreach($tmpcrumbs as $crumb){
		if(isset($crumb['link']))
			$crumb['key'] = $crumb['link'];
		elseif($crumb['tag'] == 'c'){
			$last[] = $crumb['key'];
			$crumb['key'] = "c=" . implode(".",$last);
		}else{
			$crumb['key'] = "c=" . implode(".",$last) . "&product=" . $XLSWS_VARS['product'];
		}
			
		if(!isset($crumb['link']))
			$crumb['link'] = '';
		
		
		$crumbs[] = array('key' => $crumb['key'], 'name' => $crumb['name'] , 'case' => 'other' , 'link' => $crumb['link']);
	}
	
	$this->crumbs = $crumbs;
	// Let's have the pnlPanel auto render any and all child controls
		
	$this->crumbTrail = new QPanel($this);
	$this->crumbTrail->Template = templateNamed('crumbtrail.tpl.php');
	$this->crumbTrail->AutoRenderChildren = true;

	
?>
