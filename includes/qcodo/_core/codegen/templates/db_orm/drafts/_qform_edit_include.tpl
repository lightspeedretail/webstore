<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<%= __FORM_DRAFTS__ %>" TargetFileName="<%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.tpl.php"/>
<?php
	// This is the HTML template include file (.tpl.php) for the <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.php
	// form DRAFT page.  Remember that this is a DRAFT.  It is MEANT to be altered/modified.

	// Be sure to move this out of the generated/ subdirectory before modifying to ensure that subsequent 
	// code re-generations do not overwrite your changes.

	$strPageTitle = QApplication::Translate('<%= $objTable->ClassName %>') . ' - ' . $this->mct<%= $objTable->ClassName %>->TitleVerb;
	require(__INCLUDES__ . '/header.inc.php');
?>

	<?php $this->RenderBegin() ?>

	<div id="titleBar">
		<h2><?php _p($this->mct<%= $objTable->ClassName %>->TitleVerb); ?></h2>
		<h1><?php _t('<%= $objTable->ClassName %>')?></h1>
	</div>

	<div id="formControls">
<% foreach ($objTable->ColumnArray as $objColumn) { %>
		<?php $this-><%= $objCodeGen->FormControlVariableNameForColumn($objColumn); %>->RenderWithName(); ?>

<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
<% if ($objReverseReference->Unique) { %>
		<?php $this-><%= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); %>->RenderWithName(); ?>

<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
		<?php $this-><%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>->RenderWithName(true, "Rows=7"); ?>

<% } %>
	</div>

	<div id="formActions">
		<div id="save"><?php $this->btnSave->Render(); ?></div>
		<div id="cancel"><?php $this->btnCancel->Render(); ?></div>
		<div id="delete"><?php $this->btnDelete->Render(); ?></div>
	</div>

	<?php $this->RenderEnd() ?>	

<?php require(__INCLUDES__ .'/footer.inc.php'); ?>