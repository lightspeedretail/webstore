<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<%= __FORM_DRAFTS__ %>" TargetFileName="<%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.php"/>
<?php
	// Load the Qcodo Development Framework
	require(dirname(__FILE__) . '/../../includes/prepend.inc.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do Create, Edit, and Delete functionality
	 * of the <%= $objTable->ClassName %> class.  It uses the code-generated
	 * <%= $objTable->ClassName %>MetaControl class, which has meta-methods to help with
	 * easily creating/defining controls to modify the fields of a <%= $objTable->ClassName %> columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.php AND
	 * <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.tpl.php out of this Form Drafts directory.
	 *
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage Drafts
	 */
	class <%= $objTable->ClassName %>EditForm extends QForm {
		// Local instance of the <%= $objTable->ClassName %>MetaControl
		protected $mct<%= $objTable->ClassName %>;

		// Controls for <%= $objTable->ClassName %>'s Data Fields
<% foreach ($objTable->ColumnArray as $objColumn) { %>
		protected $<%= $objCodeGen->FormControlVariableNameForColumn($objColumn); %>;
<% } %>

		// Other ListBoxes (if applicable) via Unique ReverseReferences and ManyToMany References
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
		protected $<%= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); %>;
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
		protected $<%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>;
<% } %>

		// Other Controls
		protected $btnSave;
		protected $btnDelete;
		protected $btnCancel;

		// Create QForm Event Handlers as Needed

//		protected function Form_Exit() {}
//		protected function Form_Load() {}
//		protected function Form_PreRender() {}

		protected function Form_Run() {
			// Security check for ALLOW_REMOTE_ADMIN
			// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
			QApplication::CheckRemoteAdmin();
		}

		protected function Form_Create() {
			// Use the CreateFromPathInfo shortcut (this can also be done manually using the <%= $objTable->ClassName %>MetaControl constructor)
			// MAKE SURE we specify "$this" as the MetaControl's (and thus all subsequent controls') parent
			$this->mct<%= $objTable->ClassName %> = <%= $objTable->ClassName %>MetaControl::CreateFromPathInfo($this);

			// Call MetaControl's methods to create qcontrols based on <%= $objTable->ClassName %>'s data fields
<% foreach ($objTable->ColumnArray as $objColumn) { %>
			$this-><%= $objCodeGen->FormControlVariableNameForColumn($objColumn); %> = $this->mct<%= $objTable->ClassName %>-><%= $objCodeGen->FormControlVariableNameForColumn($objColumn); %>_Create();
<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
			$this-><%= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); %> = $this->mct<%= $objTable->ClassName %>-><%= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); %>_Create();
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
			$this-><%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %> = $this->mct<%= $objTable->ClassName %>-><%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>_Create();
<% } %>

			// Create Buttons and Actions on this Form
			$this->btnSave = new QButton($this);
			$this->btnSave->Text = QApplication::Translate('Save');
			$this->btnSave->AddAction(new QClickEvent(), new QAjaxAction('btnSave_Click'));
			$this->btnSave->CausesValidation = true;

			$this->btnCancel = new QButton($this);
			$this->btnCancel->Text = QApplication::Translate('Cancel');
			$this->btnCancel->AddAction(new QClickEvent(), new QAjaxAction('btnCancel_Click'));

			$this->btnDelete = new QButton($this);
			$this->btnDelete->Text = QApplication::Translate('Delete');
			$this->btnDelete->AddAction(new QClickEvent(), new QConfirmAction(QApplication::Translate('Are you SURE you want to DELETE this') . ' ' . QApplication::Translate('<%= $objTable->ClassName %>') . '?'));
			$this->btnDelete->AddAction(new QClickEvent(), new QAjaxAction('btnDelete_Click'));
			$this->btnDelete->Visible = $this->mct<%= $objTable->ClassName %>->EditMode;
		}

		/**
		 * This Form_Validate event handler allows you to specify any custom Form Validation rules.
		 * It will also Blink() on all invalid controls, as well as Focus() on the top-most invalid control.
		 */
		protected function Form_Validate() {
			// By default, we report that Custom Validations passed
			$blnToReturn = true;

			// Custom Validation Rules
			// TODO: Be sure to set $blnToReturn to false if any custom validation fails!

			$blnFocused = false;
			foreach ($this->GetErrorControls() as $objControl) {
				// Set Focus to the top-most invalid control
				if (!$blnFocused) {
					$objControl->Focus();
					$blnFocused = true;
				}

				// Blink on ALL invalid controls
				$objControl->Blink();
			}

			return $blnToReturn;
		}

		// Button Event Handlers

		protected function btnSave_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Save" processing to the <%= $objTable->ClassName %>MetaControl
			$this->mct<%= $objTable->ClassName %>->Save<%= $objTable->ClassName %>();
			$this->RedirectToListPage();
		}

		protected function btnDelete_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Delete" processing to the <%= $objTable->ClassName %>MetaControl
			$this->mct<%= $objTable->ClassName %>->Delete<%= $objTable->ClassName %>();
			$this->RedirectToListPage();
		}

		protected function btnCancel_Click($strFormId, $strControlId, $strParameter) {
			$this->RedirectToListPage();
		}

		// Other Methods

		protected function RedirectToListPage() {
			QApplication::Redirect(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__ . '/<%= strtolower($objTable->Name) %>_list.php');
		}
	}

	// Go ahead and run this form object to render the page and its event handlers, implicitly using
	// <%= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) %>_edit.tpl.php as the included HTML template file
	<%= $objTable->ClassName %>EditForm::Run('<%= $objTable->ClassName %>EditForm');
?>