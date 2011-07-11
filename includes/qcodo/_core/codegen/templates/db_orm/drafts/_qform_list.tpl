<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<%= __FORM_DRAFTS__ %>" TargetFileName="<%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_list.php"/>
<?php
	// Load the Qcodo Development Framework
	require(dirname(__FILE__) . '/../../includes/prepend.inc.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the <%= $objTable->ClassName %> class.  It uses the code-generated
	 * <%= $objTable->ClassName %>DataGrid control which has meta-methods to help with
	 * easily creating/defining <%= $objTable->ClassName %> columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_list.php AND
	 * <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage Drafts
	 */
	class <%= $objTable->ClassName %>ListForm extends QForm {
		// Local instance of the Meta DataGrid to list <%= $objTable->ClassNamePlural %>
		protected $dtg<%= $objTable->ClassNamePlural %>;

		// Create QForm Event Handlers as Needed

//		protected function Form_Exit() {}
//		protected function Form_Load() {}
//		protected function Form_PreRender() {}
//		protected function Form_Validate() {}

		protected function Form_Run() {
			// Security check for ALLOW_REMOTE_ADMIN
			// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
			QApplication::CheckRemoteAdmin();
		}

		protected function Form_Create() {
			// Instantiate the Meta DataGrid
			$this->dtg<%= $objTable->ClassNamePlural %> = new <%= $objTable->ClassName %>DataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtg<%= $objTable->ClassNamePlural %>->CssClass = 'datagrid';
			$this->dtg<%= $objTable->ClassNamePlural %>->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtg<%= $objTable->ClassNamePlural %>->Paginator = new QPaginator($this->dtg<%= $objTable->ClassNamePlural %>);
			$this->dtg<%= $objTable->ClassNamePlural %>->ItemsPerPage = 20;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$strEditPageUrl = __VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/<%= strtolower($objTable->Name) %>_edit.php';
			$this->dtg<%= $objTable->ClassNamePlural %>->MetaAddEditLinkColumn($strEditPageUrl, 'Edit', 'Edit');

			// Create the Other Columns (note that you can use strings for <%= $objTable->Name %>'s properties, or you
			// can traverse down QQN::<%= $objTable->Name %>() to display fields that are down the hierarchy)
<% foreach ($objTable->ColumnArray as $objColumn) { %>
<% if (!$objColumn->Reference) { %>
			$this->dtg<%= $objTable->ClassNamePlural %>->MetaAddColumn('<%= $objColumn->PropertyName %>');
<% } %>
<% if ($objColumn->Reference && $objColumn->Reference->IsType) { %>
			$this->dtg<%= $objTable->ClassNamePlural %>->MetaAddTypeColumn('<%= $objColumn->PropertyName %>', '<%= $objColumn->Reference->VariableType %>');
<% } %>
<% if ($objColumn->Reference && !$objColumn->Reference->IsType) { %>
			$this->dtg<%= $objTable->ClassNamePlural %>->MetaAddColumn(QQN::<%= $objTable->ClassName %>()-><%= $objColumn->Reference->PropertyName %>);
<% } %>
<% } %><% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %><% if ($objReverseReference->Unique) { %>
			$this->dtg<%= $objTable->ClassNamePlural %>->MetaAddColumn(QQN::<%= $objTable->ClassName; %>()-><%= $objReverseReference->ObjectDescription %>);
<% } %><% } %>
		}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_list.tpl.php as the included HTML template file
	<%= $objTable->ClassName %>ListForm::Run('<%= $objTable->ClassName %>ListForm');
?>