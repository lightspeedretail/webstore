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

define('__PREPEND_QUICKINIT__', true);

ob_start(); // These includes may spit content which we need to ignore
require('includes/prepend.inc.php');
ob_end_clean();

$db = Product::GetDatabase();

$SQL_FROM = 'xlsws_product';
$SQL_WHERE = 'name';

$searchq = _xls_escape(strip_tags($_GET['q']));
$matches = $db->Query(
	'SELECT ' . $SQL_WHERE .
	' FROM ' . $SQL_FROM .
	' WHERE ' . $SQL_WHERE . ' LIKE "%' .$searchq . '%"' .
	' AND web=1' .
	' AND fk_product_master_id=0'
);

// Begin Return Display ?>
<?php if (strlen($searchq) > 0): ?>
	<ul class="autocomplete rounded" id="autocompletor">

	<?php while ($row = $matches->FetchArray()): ?>
		<li class="search_item" onmouseout="clearList()">
			<a href="javascript:{}"
			   style="border: none;"
			   onclick="document.getElementById('xlsSearch').value = '<?=addslashes(str_replace("\n","",urlencode($row[$SQL_WHERE])))?>';document.getElementById('searchoptions').style.display='none';document.location.href='index.php?search='+ $('#xlsSearch').val(); return false;">
				<?php echo $row[$SQL_WHERE]; ?>
			</a>
		</li>
	<?php endwhile; ?>

	</ul>
<?php endif; ?>