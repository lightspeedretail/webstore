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
 * Sharing tools and code that should go in footer 
 *
 * 
 *
 */
?>
<div id="sharingtools">
	<a href="http://pinterest.com/pin/create/button/?url=<? echo $this->prod->CanonicalUrl; ?>&media=<? echo
	_xls_site_dir($this->prod->SmallImage,true); ?>&description=<? echo urlencode($this->prod->Name); ?>"
	   class="pin-it-button" count-layout="none"><img border="0" src="//assets.pinterest.com/images/PinExt.png"
	                                                  title="Pin It"/></a>

	<div class="g-plusone" data-size="medium" data-annotation="none" data-width="50"></div>
	<script>(function (d) {
		var js, id = 'facebook-jssdk';
		if (d.getElementById(id)) {
			return;
		}
		js = d.createElement('script');
		js.id = id;
		js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		d.getElementsByTagName('head')[0].appendChild(js);
	}(document));</script>
	<div class="fb-like" data-href="<? echo $this->prod->CanonicalUrl; ?>" data-send="false" data-layout="button_count"
	     data-width="90" data-show-faces="false" style="vertical-align:top;zoom:1;*display:inline"></div>


</div>