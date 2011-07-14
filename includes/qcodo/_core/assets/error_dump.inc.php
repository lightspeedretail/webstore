<?php
	/**
	 * Qcodo Error Dump
	 */
?>
<html>
	<head>
		<title>PHP <?php _p(QErrorHandler::$Type); ?> - <?php _p(QErrorHandler::$Message); ?></title>
		<style>
			body { font-family: 'Arial' 'Helvetica' 'sans-serif'; font-size: 11px; }
			a:link, a:visited { text-decoration: none; }
			a:hover { text-decoration: underline; }
			pre { font-family: 'Lucida Console' 'Courier New' 'Courier' 'monospaced'; font-size: 11px; line-height: 13px; }
			.page { padding: 10px; }
			.headingLeft { background-color: #440066; color: #ffffff; padding: 10px 0px 10px 10px; font-family: 'Verdana' 'Arial' 'Helvetica' 'sans-serif'; font-size: 18px; font-weight: bold; width: 70%; vertical-align: middle; }
			.headingLeftSmall { font-size: 10px; }
			.headingRight { background-color: #440066; color: #ffffff; padding: 0px 10px 10px 10px; font-family: 'Verdana' 'Arial' 'Helvetica' 'sans-serif'; font-size: 10px; width: 30%; vertical-align: middle; text-align: right; }
			.title { font-family: 'Verdana' 'Arial' 'Helvetica' 'sans-serif'; font-size: 19px; font-style: italic; color: #330055; }
			.code { background-color: #f4eeff; padding: 1px 10px 1px 10px; }
		</style>
		<script type="text/javascript">
			function RenderPage(strHtml) { document.rendered.strHtml.value = strHtml; document.rendered.submit(); }
			function ToggleHidden(strDiv) { var obj = document.getElementById(strDiv); var stlSection = obj.style; var isCollapsed = obj.style.display.length; if (isCollapsed) stlSection.display = ''; else stlSection.display = 'none'; }
		</script>
	</head>
	<body bgcolor="white" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"> 

	<table border="0" cellspacing="0" width="100%">
		<tr>
			<td nowrap="nowrap" class="headingLeft"><span class="headingLeftSmall"><?php _p(QErrorHandler::$Type); ?> in PHP Script<br /></span><?php _p($_SERVER["PHP_SELF"]); ?></td>
			<td nowrap="nowrap" class="headingRight">
				<b>PHP Version:</b> <?php _p(PHP_VERSION); ?>;&nbsp;&nbsp;<b>Zend Engine Version:</b> <?php _p(zend_version()); ?>;&nbsp;&nbsp;<b>Qcodo Version:</b> <?php _p(QCODO_VERSION); ?><br />
				<?php if (array_key_exists('OS', $_SERVER)) printf('<b>Operating System:</b> %s;&nbsp;&nbsp;', $_SERVER['OS']); ?><b>Application:</b> <?php _p($_SERVER['SERVER_SOFTWARE']); ?>;&nbsp;&nbsp;<b>Server Name:</b> <?php _p($_SERVER['SERVER_NAME']); ?><br />
				<b>HTTP User Agent:</b> <?php _p($_SERVER['HTTP_USER_AGENT']); ?></td>
		</tr>
	</table>
	
	<div class="page">
		<span class="title"><?php _p(QErrorHandler::$MessageBody, false); ?></span><br />
		<form method="post" action="<?php _p(__VIRTUAL_DIRECTORY__ . __PHP_ASSETS__) ;?>/_core/error_already_rendered_page.php" target="blank" name="rendered"><input type="hidden" name="strHtml" value=""></form>

			<b><?php _p(QErrorHandler::$Type); ?> Type:</b>&nbsp;&nbsp;
			<?php _p(QErrorHandler::$ObjectType); ?>
			<br /><br />

<?php
			if (isset(QErrorHandler::$RenderedPage)) {
?>
				<script type="text/javascript">RenderedPage = "<?php _p(QErrorHandler::PrepDataForScript(QErrorHandler::$RenderedPage), false); ?>";</script>
				<b>Rendered Page:</b>&nbsp;&nbsp;
				<a href="javascript:RenderPage(RenderedPage)">Click here</a> to view contents able to be rendered
				<br /><br />
<?php
			}
?>
			<b>Source File:</b>&nbsp;&nbsp;
			<?php _p(QErrorHandler::$Filename); ?>
			&nbsp;&nbsp;&nbsp;&nbsp;<b>Line:</b>&nbsp;&nbsp;
			<?php _p(QErrorHandler::$LineNumber); ?>
			<br /><br />

			<div class="code">
<?php
						_p('<pre>', false);
						for ($__exc_IntLine = max(1, QErrorHandler::$LineNumber - 5); $__exc_IntLine <= min(count(QErrorHandler::$FileLinesArray), QErrorHandler::$LineNumber + 5); $__exc_IntLine++) {
							if (QErrorHandler::$LineNumber == $__exc_IntLine)
								printf("<font color=red>Line %s:    %s</font>", $__exc_IntLine, htmlentities(QErrorHandler::$FileLinesArray[$__exc_IntLine - 1]));
							else
								printf("Line %s:    %s", $__exc_IntLine, htmlentities(QErrorHandler::$FileLinesArray[$__exc_IntLine - 1]));
						}
						_p('</pre>', false);
						unset($__exc_IntLine);
?>
			</div><br />
			
<?php
			if (isset(QErrorHandler::$ErrorAttributeArray))
				foreach (QErrorHandler::$ErrorAttributeArray as QErrorHandler::$ErrorAttribute) {
					printf("<b>%s:</b>&nbsp;&nbsp;", QErrorHandler::$ErrorAttribute->Label);
					QErrorHandler::$JavascriptLabel = str_replace(" ", "", QErrorHandler::$ErrorAttribute->Label);
					if (QErrorHandler::$ErrorAttribute->MultiLine) {
						printf("\n<a href=\"javascript:ToggleHidden('%s')\">Show/Hide</a>",
							QErrorHandler::$JavascriptLabel);
						printf('<br /><br /><div id="%s" class="code" style="Display: none;"><pre>%s</pre></div><br />',
							QErrorHandler::$JavascriptLabel,
							htmlentities(QErrorHandler::$ErrorAttribute->Contents));
					} else
						printf("%s\n<br /><br />\n", htmlentities(QErrorHandler::$ErrorAttribute->Contents));
				}
?>

			<b>Call Stack:</b>
			<br><br>
			<div class="code">
				<pre><?php _p(QErrorHandler::$StackTrace); ?></pre>
			</div><br />

			<b>Global Variables Dump:</b>&nbsp;&nbsp;
			<a href="javascript:ToggleHidden('VariableDump')">Show/Hide</a>
			<br /><br />
			<div id="VariableDump" class="code" style="Display: none;">
<?php
				_p('<pre>', false);

				// Dump All Variables
				foreach ($GLOBALS as $__exc_Key => $__exc_Value) {
					// TODO: Figure out why this is so strange
					if (isset($__exc_Key))
						if ($__exc_Key != "_SESSION")
							global $$__exc_Key;
				}

				$__exc_ObjVariableArray = get_defined_vars();
				$__exc_ObjVariableArrayKeys = array_keys($__exc_ObjVariableArray);
				sort($__exc_ObjVariableArrayKeys);

				$__exc_StrToDisplay = "";
				$__exc_StrToScript = "";
				foreach ($__exc_ObjVariableArrayKeys as $__exc_Key) {
					if ((strpos($__exc_Key, "__exc_") === false) && (strpos($__exc_Key, "_DATE_") === false) && ($__exc_Key != "GLOBALS") && !($__exc_ObjVariableArray[$__exc_Key] instanceof QForm)) {
						try {
							if (($__exc_Key == 'HTTP_SESSION_VARS') || ($__exc_Key == '_SESSION')) {
								$__exc_ObjSessionVarArray = array();
								foreach ($$__exc_Key as $__exc_StrSessionKey => $__exc_StrSessionValue) {
									if (strpos($__exc_StrSessionKey, 'qform') !== 0)
										$__exc_ObjSessionVarArray[$__exc_StrSessionKey] = $__exc_StrSessionValue;
								}
								$__exc_StrVarExport = htmlentities(var_export($__exc_ObjSessionVarArray, true));
							} else if (($__exc_ObjVariableArray[$__exc_Key] instanceof QControl) || ($__exc_ObjVariableArray[$__exc_Key] instanceof QForm))
								$__exc_StrVarExport = htmlentities($__exc_ObjVariableArray[$__exc_Key]->VarExport());
							else
								$__exc_StrVarExport = htmlentities(var_export($__exc_ObjVariableArray[$__exc_Key], true));

							$__exc_StrToDisplay .= sprintf("  <a href=\"javascript:RenderPage(%s)\" title=\"%s\">%s</a>\n", $__exc_Key, $__exc_StrVarExport, $__exc_Key);
							$__exc_StrToScript .= sprintf("  %s = \"<pre>%s</pre>\";\n", $__exc_Key, QErrorHandler::PrepDataForScript($__exc_StrVarExport));
						} catch (Exception $__exc_objExcOnVarDump) {
							$__exc_StrToDisplay .= sprintf("  Fatal error:  Nesting level too deep - recursive dependency?\n", $__exc_objExcOnVarDump->Message);
						}
					}
				}

				_p($__exc_StrToDisplay . '</pre>', false);
				printf('<script type="text/javascript">%s</script>', $__exc_StrToScript);
?>
			</div><br />
			<hr width="100%" size="1" color="#dddddd" />
			<center><em>
				<?php _p(QErrorHandler::$Type); ?> Report Generated:&nbsp;&nbsp;<?php _p(QErrorHandler::$DateTimeOfError); ?>
				<br/>
<?php if (QErrorHandler::$FileNameOfError) { ?>
				<?php _p(QErrorHandler::$Type); ?> Report Logged:&nbsp;&nbsp;<?php _p(QErrorHandler::$FileNameOfError); ?>
<?php } else { ?>
				<?php _p(QErrorHandler::$Type); ?> Report NOT Logged
<?php } ?>
			</em></center>
	</div>
	</body>
</html>

<?php if (QErrorHandler::$FileNameOfError) { ?>
<!--qcodo--<error valid="true">
<type><?php _p(QErrorHandler::$Type); ?></type>
<title><?php _p(QErrorHandler::$Message); ?></title>
<datetime><?php _p(QErrorHandler::$DateTimeOfError); ?></datetime>
<isoDateTime><?php _p(QErrorHandler::$IsoDateTimeOfError); ?></isoDateTime>
<filename><?php _p(QErrorHandler::$FileNameOfError); ?></filename>
<script><?php _p($_SERVER["PHP_SELF"]); ?></script>
<server><?php _p($_SERVER['SERVER_NAME']); ?></server>
<agent><?php _p($_SERVER['HTTP_USER_AGENT']); ?></agent>
</error>-->
<?php } ?>