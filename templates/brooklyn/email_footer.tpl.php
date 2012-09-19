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
 * template HTML Email Footer, called as part of header-template-footer
 * aggregate calls in _xls_mail_body_from_template();
 * 
 *
 */

?>


	</div>
	
	
	<div id="footer" style="height: 36px; background: url(<?= templateNamed('images/email_footer_bg.png') ?>) no-repeat; color: #fff;">
		<p style="display: block; float: left; margin: 8px 0 0 15px; color: #fff;"><a href="mailto:<?= _xls_get_conf('EMAIL_FROM'); ?>"><?= _xls_get_conf('EMAIL_FROM'); ?></a></p>
		<?php if(_xls_get_conf('STORE_PHONE')): ?>
		<p style="display: block; float: right; margin: 8px 15px 0 0;">Phone: <?= _xls_get_conf('STORE_PHONE') ?></p>		
		<?php endif; ?>
	</div>
		
</div>

</body>
</html>
