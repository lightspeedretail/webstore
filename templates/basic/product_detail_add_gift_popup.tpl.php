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
 * Basic template: Popup when clicking Add To Wish List when viewing product 
 *
 * 
 *
 */

?>
<div style="margin: 5px 0 0 125px;">
<?php $this->misc_components['select_gift_registry']->Render(); ?><br />
<?php $this->misc_components['add_gift_registry']->Render();
$this->misc_components['cancel_gift_registry']->Render();
?>

</div>
