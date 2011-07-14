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
 * Web Admin panel template
 * General use for master dropdown of panel modules 
 * 
 *
 */


global $XLSWS_VARS;
?>
		<div id="selector"> 
			<h2><?php _xt('Select a Module to configure') ?>:</h2><br /> 
			<ul class="sf-menu"> 
				<li> 
						<a href="#"><?php if(isset($XLSWS_VARS['page']) && isset($this->admin_pages[$XLSWS_VARS['page']])) echo($this->admin_pages[$XLSWS_VARS['page']]); else _xt('Configuration') ?></a> 
						<ul> 
							<?php  foreach($this->admin_pages as $pkey=>$ptitle): ?>
								<li><a href="xls_admin.php?page=<?= $pkey . admin_sid(); ?>" ><?= $ptitle ?></a></li> 
							<?php endforeach; ?>
						</ul> 
				</li> 
			</ul> 
		</div> <br style="clear: both;" />
