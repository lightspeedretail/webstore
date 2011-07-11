<template OverwriteFlag="false" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __DATA_META_CONTROLS__ %>" TargetFileName="<%= $objTable->ClassName %>MetaControl.class.php"/>
<?php
	require(__DATAGEN_META_CONTROLS__ . '/<%= $objTable->ClassName %>MetaControlGen.class.php');

	/**
	 * This is a MetaControl customizable subclass, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality of the
	 * <%= $objTable->ClassName %> class.  This code-generated class extends from
	 * the generated MetaControl class, which contains all the basic elements to help a QPanel or QForm
	 * display an HTML form that can manipulate a single <%= $objTable->ClassName %> object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a <%= $objTable->ClassName %>MetaControl
	 * class.
	 *
	 * This file is intended to be modified.  Subsequent code regenerations will NOT modify
	 * or overwrite this file.
	 * 
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage MetaControls
	 */
	class <%= $objTable->ClassName %>MetaControl extends <%= $objTable->ClassName %>MetaControlGen {
	}
?>