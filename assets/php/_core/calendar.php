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
 * Used for calendar.js calendar popup
 *
 * 
 *
 */

	function CastToInt($strNumber) {
		settype($strNumber, "int");
		return $strNumber;
	}

	if ((!array_key_exists("intTimestamp", $_GET)) || (!$_GET["intTimestamp"])) {
		$intTimestamp = time();
	} else
		$intTimestamp = $_GET["intTimestamp"];
		
	$intSelectedMonth = CastToInt(date("n", $intTimestamp));
	$intSelectedDay = CastToInt(date("j", $intTimestamp));
	$intSelectedYear = CastToInt(date("Y", $intTimestamp));
	$intTimestamp = mktime(0,0,0, $intSelectedMonth, $intSelectedDay, $intSelectedYear);
	$dttToday = mktime(0,0,0, date("n"), date("j"), date("Y"));
	
	$intMonthStartsOn = CastToInt(date("w", mktime(0,0,0, $intSelectedMonth, 1, $intSelectedYear)));
	$intMonthDays = CastToInt(date("t", $intTimestamp));
	$intPreviousMonthDays = CastToInt(date("t", mktime(0,0,0, $intSelectedMonth - 1, 1, $intSelectedYear)));
	
	$strQueryArgs = sprintf("&strFormId=%s&strId=%s", $_GET["strFormId"], $_GET["strId"]);
	$strChangeCommand = sprintf('window.opener.document.forms["%s"].elements["%s"].value = "%s"; ',
		$_GET["strFormId"],
		$_GET["strId"],
		date("M j Y", $intTimestamp));
	$strChangeCommand .= sprintf('window.opener.document.forms["%s"].elements["%s_intTimestamp"].value = "%s"; ',
		$_GET["strFormId"],
		$_GET["strId"],
		$intTimestamp);
	$strChangeCommand .= sprintf('if (window.opener.document.forms["%s"].elements["%s"].onchange) window.opener.document.forms["%s"].elements["%s"].onchange();',
		$_GET["strFormId"],
		$_GET["strId"],
		$_GET["strFormId"],
		$_GET["strId"]);
?>
<html>
<head>
	<title>Calendar</title>
	<script type="text/javascript">
		function selectDate(intTimestamp) {
			document.location = "calendar.php?intTimestamp=" + intTimestamp + "<?php print($strQueryArgs); ?>";
		}

		function cancel() {
			window.close();
		}

		function done() {
			<?php print($strChangeCommand); ?>
			window.close();
		}
	</script>
	<style>
		.main {
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 9px;
			text-align: center;
			color: #004d5d
		}
		
		A {
			text-decoration: none;
		}

		.dropdown {
			background-color: #e5e5e5;
			font-family: arial, helvetica, sans-serif;
			font-size: 8pt;
		}
		
		.button {
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 7.5pt;
			font-weight: bold;
			color: #ffffff;
			background-color: #004d5d;
			text-align: center;
			vertical-align: middle;
			height: 18px;
			border: thin solid #223344;
		}

		.offMonth {
			color: #999999;
			background-color: #f0f0f0;
		}
		
		.onMonth {
			color: #005599;
			background-color: #e0f0f0;
		}
		
		.onMonthWeekend {
			color: #80aabb;
			background-color: #ffffff;
		}

		.selected {
			color: #ffffff;
			background-color: #ee0000;
		}
		
		.today {
			color: #ffffff;
			background-color: #80aabb;
		}
	</style>
</head>
<body>
<form method="get" name="myForm"><center>
	<select name="dttMonth" class="dropdown" onchange="selectDate(document.myForm.dttMonth.options[document.myForm.dttMonth.selectedIndex].value)">
<?php
	for ($intMonth = 1; $intMonth <= 12; $intMonth++) {
		$intTimestampLabel = mktime(0,0,0, $intMonth, 1, $intSelectedYear);
		$strLabel = date("F", $intTimestampLabel);
		$strSelected = ($intMonth == $intSelectedMonth) ? "selected" : "";
		printf('<option value="%s" %s>%s</option>', $intTimestampLabel, $strSelected, $strLabel);
	}
?>
	</select> &nbsp; 
	<select name="dttYear" class="dropdown" onchange="selectDate(document.myForm.dttYear.options[document.myForm.dttYear.selectedIndex].value)">
<?php
	for ($intYear = 1970; $intYear <= 2010; $intYear++) {
		$intTimestampLabel = mktime(0,0,0, $intSelectedMonth, 1, $intYear);
		$strLabel = date("Y", $intTimestampLabel);
		$strSelected = ($intYear == $intSelectedYear) ? 'selected="selected"' : '';
		printf('<option value="%s" %s>%s</option>', $intTimestampLabel, $strSelected, $strLabel);
	}
?>
	</select>
	<table cellspacing="2" cellpadding="2" border="0" class="main">
		<tr>
			<td>Su</td>
			<td>Mo</td>
			<td>Tu</td>
			<td>We</td>
			<td>Th</td>
			<td>Fr</td>
			<td>Sa</td>
		</tr>
<?php
	$intDaysBack = ($intMonthStartsOn == 0) ? 7 : $intMonthStartsOn;
	$intIndex = 1 - $intDaysBack;
	$intRowCount = 0;

	while ($intRowCount < 6) {
		print('<tr>');
		for ($intDayOfWeek = 0; $intDayOfWeek <= 6; $intDayOfWeek++) {
			if ($intIndex < 1) {
				$intLabel = $intPreviousMonthDays + $intIndex;
				$intTimestampLabel = mktime(0,0,0, $intSelectedMonth - 1, $intLabel, $intSelectedYear);
				$strCssclass = "offMonth";
			} else if ($intIndex > $intMonthDays) {
				$intLabel = $intIndex - $intMonthDays;
				$intTimestampLabel = mktime(0,0,0, $intSelectedMonth + 1, $intLabel, $intSelectedYear);
				$strCssclass = "offMonth";
			} else {
				$intLabel = $intIndex;
				$intTimestampLabel = mktime(0,0,0, $intSelectedMonth, $intLabel, $intSelectedYear);
				$strCssclass = "onMonth";
				if ((date("w", $intTimestampLabel) == 0) || (date("w", $intTimestampLabel) == 6))
					$strCssclass = "onMonthWeekend";
				else
					$strCssclass = "onMonth";
			}

			if ($intTimestampLabel == $intTimestamp)
				$strCssclass = "selected";
			else if ($intTimestampLabel == $dttToday)
				$strCssclass = "today";

			printf('<td class="%s"><a class="%s" href="#" onclick="selectDate(%s)">%s</a></td>', $strCssclass, $strCssclass, $intTimestampLabel, $intLabel);
			$intIndex++;
		}
		print('</tr>');
		$intRowCount++;
	}
?>
		<tr>
			<td colspan="7">Selected Day: <?php print(date("n/j/Y", $intTimestamp)); ?><br />&nbsp;</td>
		</tr>
	</table>
	<input type="button" class="button" name="Done" value="DONE" onclick="done()" /> &nbsp; 
	<input type="button" class="button" name="Cancel" value="CANCEL" onclick="cancel()" />
</center></form></body></html>
