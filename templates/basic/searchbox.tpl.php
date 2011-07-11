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
 * Basic template: Search input field in menu bar 
 *
 * 
 *
 */
$this->misc_components['advanced_search']->SetCustomStyle('margin-left','-3px'); //specifically only for basic template
if (QApplication::IsBrowser(QBrowserType::Firefox)) 
   $this->misc_components['search_img']->SetCustomStyle('margin-left','-8px'); //specifically only for basic template

?>
<span class="sbox_l"></span>
<span class="sbox">
		<?php $this->txtSearchBox->Render(); ?></span>
<span class="sbox_r" id="srch_clear"></span>
<span style=""><?= $this->misc_components['search_img']->Render(); ?>
<?= $this->misc_components['advanced_search']->Render(); ?>
</span>
<div id="searchoptions"></div>
