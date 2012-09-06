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
 * template Template to create product grid outline
 * calls product_list_item
 * 
 *
 */

?>


<div>

<?php if(isset($this->custom_page_content)): ?>
	<div id="custom_content">
		<?= $this->custom_page_content; ?>
	</div>
<?php endif; ?>

<?php if( _xls_get_conf('ENABLE_CATEGORY_IMAGE',0) && isset($this->category) && $this->category->ImageExist): ?>
	<div id="category_image">
		<img src="<?= $this->category->CategoryImage; ?>" />
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
<br style="clear:both"/>
<?php endif; ?> 
<h1><?php echo _xls_stack_get('override_category') != '' ? _xls_stack_pop('override_category') : $this->category->Name; ?></h1>
</div>
<?php if ($this->dtrProducts->TotalItemCount>0) : ?>

<div class="sixteen columns">

	<?php $this->dtrProducts->Render(); ?>
</div>


    <br style="clear:both"/>
	</div>
<?php endif; ?>
