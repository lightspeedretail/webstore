<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<%= __PANEL_DRAFTS__ %>" TargetFileName="<%= $objTable->ClassName %>ListPanel.tpl.php"/>
<?php
	// This is the HTML template include file (.tpl.php) for <%= $objTable->ClassName %>ListPanel.
	// Remember that this is a DRAFT.  It is MEANT to be altered/modified.
	// Be sure to move this out of the drafts/dashboard directory before modifying to ensure that subsequent 
	// code re-generations do not overwrite your changes.
?>
	<?php $_CONTROL->dtg<%= $objTable->ClassNamePlural %>->Render(); ?>
	<p><?php $_CONTROL->btnCreateNew->Render(); ?></p>
