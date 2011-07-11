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
 * Framework template Custom Pages, including New Products, Top Products, Promotions 
 *
 * 
 *
 */

?>


<div style="padding: 15px 25px; line-height: 1.5em;"><?php echo $this->content;  ?></div>


<?php if(isset($this->pnlSlider)): ?>
        <br style="clear:both"/>

        <?php  $this->pnlSlider->Render(); ?>
       
        <br style="clear:both"/>


<?php endif; ?>


