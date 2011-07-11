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
 * Framework template Template to create product grid outline
 * calls product_list_item
 * 
 *
 */

?>


<div style="clear:both; padding-left:30px;">

<?php if(isset($this->custom_page_content)): ?>
	<p style="margin-bottom: 15px;">
	<?= $this->custom_page_content; ?>
	</p>
<?php endif; ?>

<?php if(isset($this->category) && $this->category->ImageExist): ?>
	<div style="float:left; display:inline;">
	<img src="<?= $this->category->ListingImage; ?>" />
	</div>
<?php elseif(isset($this->image) && $this->image): ?>
	<div style="float:left; display:inline;">
	<img src="<?= $this->image; ?>" />
	</div>
<?php endif; ?>


<?php  if($this->subcategories  && (count($this->subcategories) > 0)): ?> 
<div style="display:inline; float:left;"><br />
<p style="font-weight: bold; margin: -10px 0 8px 15px; "><?php _xt("Browse subcategories"); ?></strong></p>
<ul style="margin: 0 0 15px 0;">
<?php  foreach($this->subcategories as $categ): ?> 
<li style="margin: 0 0 3px 15px;"><a href="<?= $categ['link']; ?>">&nbsp;<?= $categ['name']; ?></a></li> 
<?php endforeach; ?> 
</ul>
</div>
<?php endif; ?> 
<br style="clear:both"/>

</div>
<br style="clear:both"/>
<div id="main_panel" class="rounded">

        <?php $this->dtrProducts->Render(); ?>
       
        <br style="clear:both"/>


</div>
