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
 * template Wish List (Gift Registry) Wish List details calling template
 * Calls each element that makes up the detail display
 *
 *
 */

?>
	<div class="row"><?php $this->pnlGRGift->Render(); ?></div>
	<div class="row"><?php $this->pnlGRForm->Render(); ?></div>
	<div class="row"><?php $this->pnlGRView->Render(); $this->pnlGRProduct->Render(); ?></div>
	<div class="row"><?php $this->pnlGREmail->Render(); ?></div>


	
	
			

			

