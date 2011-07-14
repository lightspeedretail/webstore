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

/**
 * Used by QDatabaseBase.class.php definition
 *
 * 
 *
 */
	require(dirname(__FILE__) . '/../_require_prepend.inc.php');
	$intDatabaseIndex = $_POST['intDatabaseIndex'];
	$strProfileData = $_POST['strProfileData'];
	$strReferrer = $_POST['strReferrer'];

	$objProfileArray = unserialize(base64_decode($strProfileData));
	$objProfileArray = QType::Cast($objProfileArray, QType::ArrayType);
	if ((count($objProfileArray) % 2) != 0)
		throw new Exception('Database Profiling data appears to have been corrupted.');
?>
<html>
	<head>
		<title>Qcodo Development Framework - Database Profiling Tool</title>
		<style>
			body { font-family: 'Arial', 'Helvetica', 'sans-serif'; font-size: 14px; }
			a:link, a:visited { text-decoration: none; }
			a:hover { text-decoration: underline; }

			pre { font-family: 'Lucida Console', 'Courier New', 'Courier', 'monospaced'; font-size: 11px; line-height: 13px; }
			.page { padding: 10px; }

			.headingLeft {
				background-color: #446644;
				color: #ffffff;
				padding: 10px 0px 10px 10px;
				font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif';
				font-size: 18px;
				font-weight: bold;
				width: 70%;
				vertical-align: middle;
			}
			.headingLeftSmall { font-size: 10px; }
			.headingRight {
				background-color: #446644;
				color: #ffffff;
				padding: 0px 10px 10px 10px;
				font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif';
				font-size: 10px;
				width: 30%;
				vertical-align: middle;
				text-align: right;
			}
			.title { font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif'; font-size: 19px; font-style: italic; color: #330055; }
			.code { background-color: #f4eeff; padding: 1px 10px 1px 10px; }
			
			.function { font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif'; font-size: 12px; font-weight: bold; }
			.function_details { font-family: 'Verdana', 'Arial', 'Helvetica', 'sans-serif'; font-size: 10px; color: #777777; }
		</style>
		<script type="text/javascript">
			function Toggle(spanId) {
				var obj = document.getElementById(spanId);

				if (obj) {
					if (obj.style.display == "block") {
						// Make INVISIBLE
						obj.style.display = "none";
					} else {
						// Make VISIBLE
						obj.style.display = "block";
					}
				}
			}
			
			function ShowAll() {
				for (var intIndex = 1; intIndex < <?php _p(count($objProfileArray)); ?>; intIndex = intIndex + 2) {
					var obj = document.getElementById('query' + intIndex);
					obj.style.display = "block";
				}
			}
			
			function HideAll() {
				for (var intIndex = 1; intIndex < <?php _p(count($objProfileArray)); ?>; intIndex = intIndex + 2) {
					var obj = document.getElementById('query' + intIndex);
					obj.style.display = "none";
				}
			}
		</script>
	</head>
	<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"> 

		<table border="0" cellspacing="0" width="100%">
			<tr>
				<td nowrap="nowrap" class="headingLeft"><span class="headingLeftSmall">Qcodo Development Framework <?= QCODO_VERSION ?><br /></span>Database Profiling Tool</div></td>
				<td nowrap="nowrap" class="headingRight">
					<b>Database Index:</b> <?php _p($intDatabaseIndex); ?>;&nbsp;&nbsp;<b>Database Type:</b> <?php _p(QApplication::$Database[$intDatabaseIndex]->Adapter); ?><br />
					<b>Database Server:</b> <?php _p(QApplication::$Database[$intDatabaseIndex]->Server); ?>;&nbsp;&nbsp;<b>Database Name:</b> <?php _p(QApplication::$Database[$intDatabaseIndex]->Database); ?><br />
					<b>Profile Generated From:</b> <?php _p($strReferrer); ?>
				</td>
			</tr>
		</table><br />

		<div class="page">
<?php
			$intCount = count($objProfileArray) / 2;
			if ($intCount == 0)
				_p('<b>There were no queries that were performed.</b>', false);
			else if ($intCount == 1)
				_p('<b>There was 1 query that was performed.</b>', false);
			else
				printf('<b>There were %s queries that were performed.</b>', $intCount);
?>
			<br />
			<a href="javascript: ShowAll()" class="function_details">Show All</a>
			 &nbsp;&nbsp;|&nbsp;&nbsp; 
			<a href="javascript: HideAll()" class="function_details">Hide All</a>
			<br /><br /><br />
<?php
			for ($intIndex = 0; $intIndex < count($objProfileArray); $intIndex++) {
				if ((count($objProfileArray[$intIndex]) > 3) &&
					(array_key_exists('function', $objProfileArray[$intIndex][2])) &&
					(($objProfileArray[$intIndex][2]['function'] == 'QueryArray') ||
					 ($objProfileArray[$intIndex][2]['function'] == 'QuerySingle') ||
					 ($objProfileArray[$intIndex][2]['function'] == 'QueryCount')))
					$objDebugBacktrace = $objProfileArray[$intIndex][3];
				else
					$objDebugBacktrace = $objProfileArray[$intIndex][2];
				$intIndex++;
				$strQuery = $objProfileArray[$intIndex];

				$objArgs = (array_key_exists('args', $objDebugBacktrace)) ? $objDebugBacktrace['args'] : array();
				$strClass = (array_key_exists('class', $objDebugBacktrace)) ? $objDebugBacktrace['class'] : null;
				$strType = (array_key_exists('type', $objDebugBacktrace)) ? $objDebugBacktrace['type'] : null;
				$strFunction = (array_key_exists('function', $objDebugBacktrace)) ? $objDebugBacktrace['function'] : null;
				$strFile = (array_key_exists('file', $objDebugBacktrace)) ? $objDebugBacktrace['file'] : null;
				$strLine = (array_key_exists('line', $objDebugBacktrace)) ? $objDebugBacktrace['line'] : null;
?>
				<span class="function">
					Called by <?php _p($strClass . $strType . $strFunction . '(' . implode(', ', $objArgs) . ')'); ?>
				</span>
				 &nbsp;&nbsp;|&nbsp;&nbsp; 
				<a href="javascript: Toggle('query<?php _p($intIndex); ?>')" class="function_details">Show/Hide</a>
				<br />
				<span class="function_details"><b>File: </b><?php _p($strFile); ?>; &nbsp;&nbsp;<b>Line: </b><?php _p($strLine); ?>
				</span><br />
				<div class="code" id="query<?php _p($intIndex); ?>" style="display: none"><pre><?php _p($strQuery); ?></pre></div>
				<br /><br />
<?php
			}
?>

		</div>
	</body>
</html>