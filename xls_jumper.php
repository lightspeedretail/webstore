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
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */
/*
 * 
 * This is a jumper page. It renders a form with hidden fields to POST to a payment processor for further handling
 * or simply redirects back to the thank you page if the order is not paid via credit card
 */

include_once('includes/prepend.inc.php');

$form = _xls_stack_pop('xls_jumper_form');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php _p(QApplication::$EncodingType); ?>" />
<?php if (isset($strPageTitle)): ?>
		<title><?php _p($strPageTitle); ?></title>
<?php endif; ?>
		<link rel="stylesheet" type="text/css" href="<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__); ?>/styles.css"/>
		<script type="text/javascript" src="assets/js/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("form:first").submit();
});
</script>

</head>
<body>

<h1 class="jumper" style="text-align: center;"><?php _p("Please wait while your request is processed"); ?> <img src="assets/images/spinner_14.gif"/></h1>

<?php echo $form; ?>

</body>
</html>