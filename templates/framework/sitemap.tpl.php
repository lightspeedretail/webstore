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
   
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * Framework template Sitemap template for SiteMap link on lower right of website
 *
 * 
 *
 */



function sitemap_print_children($childs){
	
	if(!$childs)
		return;
	
	if(is_array($childs) && (count($childs) == 0))
		return;
		
?>
			<ul style="margin-left:10px; list-style-type: square; line-height: 20px;">
			<?php foreach($childs as $child):?>
				<li style="padding-left:5px;"><a href="<?= $child['link'] ?>"><?php _xt($child['name']) ?></a><?php sitemap_print_children($child['children']) ?></li>
			<?php endforeach; ?>
			</ul>
<?php
	
}


?>

<br style="clear:both"/>
	
		<div style="float:left; margin-left:15px; padding: 15px; display:inline;">
			<h5><?php _xt("Site pages") ?></h5><br />
			<ul style="margin-left:20px; list-style-type: square; line-height: 20px;">
			<?php foreach($this->sitemap_pages as $page):?>
				<li style="padding-left:5px;"><a href="<?= $page['link'] ?>"><?php _xt($page['name']) ?></a><?php sitemap_print_children($page['children']) ?></li>
			<?php endforeach; ?>
			</ul>
		</div>	
		
		<div style="float:left; margin-left:90px; padding: 15px; display:inline; ">
			<h5><?php _xt("Products") ?></h5><br />
			<ul style="margin-left:20px; list-style-type: square; line-height: 20px;">
			<?php foreach($this->sitemap_categories as $categ):?>
				<li style="padding-left:5px;"><a href="<?= $categ['link'] ?>"><?php _xt($categ['name']) ?></a>
				
				<!-- <?php sitemap_print_children($categ['children']) ?> --></li>
			<?php endforeach; ?>
			</ul>
		</div>
