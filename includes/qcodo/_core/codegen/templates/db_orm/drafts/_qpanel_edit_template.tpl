<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<%= __PANEL_DRAFTS__ %>" TargetFileName="<%=$objTable->ClassName%>EditPanel.tpl.php"/>
<?php
	// This is the HTML template include file (.tpl.php) for <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>EditPanel.
	// Remember that this is a DRAFT.  It is MEANT to be altered/modified.
	// Be sure to move this out of the drafts/dashboard subdirectory before modifying to ensure that subsequent 
	// code re-generations do not overwrite your changes.
?>
	<div id="formControls">
<% foreach ($objTable->ColumnArray as $objColumn) { %>
		<?php $_CONTROL-><%= $objCodeGen->FormControlVariableNameForColumn($objColumn); %>->RenderWithName(); ?>

<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
<% if ($objReverseReference->Unique) { %>
		<?php $_CONTROL-><%= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); %>->RenderWithName(); ?>

<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
		<?php $_CONTROL-><%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>->RenderWithName(true, "Rows=7"); ?>

<% } %>
	</div>

	<div id="formActions">
		<div id="save"><?php $_CONTROL->btnSave->Render(); ?></div>
		<div id="cancel"><?php $_CONTROL->btnCancel->Render(); ?></div>
		<div id="delete"><?php $_CONTROL->btnDelete->Render(); ?></div>
	</div>
