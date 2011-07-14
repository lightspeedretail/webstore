<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __DATAGEN_META_CONTROLS__ %>" TargetFileName="<%= $objTable->ClassName %>DataGridGen.class.php"/>
<?php
	/**
	 * This is the "Meta" DataGrid class for the List functionality
	 * of the <%= $objTable->ClassName %> class.  This code-generated class
	 * contains a QDataGrid class which can be used by any QForm or QPanel,
	 * listing a collection of <%= $objTable->ClassName %> objects.  It includes
	 * functionality to perform pagination and sorting on columns.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create an instance of this DataGrid in a QForm or QPanel.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent re-
	 * code generation.
	 * 
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage MetaControls
	 * 
	 */
	class <%= $objTable->ClassName %>DataGridGen extends QDataGrid {
		<%@ constructor('objTable'); %>


		<%@ meta_add_column('objTable'); %>


		<%@ meta_add_type_column('objTable'); %>


		<%@ meta_add_edit_column('objTable'); %>


		<%@ meta_data_binder('objTable'); %>


		<%@ resolve_content_item('objTable'); %>
	}
?>