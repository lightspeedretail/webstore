<?php
define ('__SUBDIRECTORY__', preg_replace('/\/?\w+\.php$/', '', $_SERVER['PHP_SELF']));
define ('__DOCROOT__', substr(dirname(__FILE__), 0, strlen(dirname(__FILE__)) - strlen(__SUBDIRECTORY__)));
define ('__VIRTUAL_DIRECTORY__', '');
$strUrl = $_SERVER['SCRIPT_NAME'];
$strUrl = str_replace("index.php","",$strUrl);
?><style type="text/css">
	.install_agreement {
		display: block;
		font-size: 0.8em;
		height: 300px;
		line-height: 12pt;
		margin: 5px 0 10px;
		padding: 5px 2px 2px 10px;
		width: 725px;
	}
	legend { font-size: 0.8em; line-height: 13pt; }
	label[for=InstallForm_iagree] { display: inline; margin-left: 10px; margin-right: 5px; padding-top: 5px; }
	input[type="checkbox"] { margin-left: 20px; height: 20px; width: 20px; }
</style>
<div class="span10">
	<h3>The End</h3>
	<div class="hero-unit">
		<div class="editinstructions">Your installation is complete!</div>
			<div id="agreement" class="install_agreement">
				<p>Web Store has been installed. If this is a new installation, then the next step will be to perform an upload from LightSpeed. Please see <a href="http://www.lightspeedretail.com/help/?p=8759">the Web Store setup guide</a> for the next steps.</p>

				<p><strong>Note: If this is an upgrade of a prior version of Web Store, you will need to edit your shipping modules and turn on what delivery speeds are offered. We recommend checking your active shipping and payment modules in Admin Panel to verify settings were upgraded correctly.</strong></p>

				<p>To visit your new store, go to <?php echo CHtml::link("my home page",$strUrl); ?></p>
			</div>

	</div>

</div>