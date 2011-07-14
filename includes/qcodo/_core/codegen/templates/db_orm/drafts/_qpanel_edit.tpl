<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<%= __PANEL_DRAFTS__ %>" TargetFileName="<%= $objTable->ClassName %>EditPanel.class.php"/>
<?php
	/**
	 * This is a quick-and-dirty draft QPanel object to do Create, Edit, and Delete functionality
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
	class <%= $objTable->ClassName %>EditPanel extends QPanel {
		// Local instance of the <%= $objTable->ClassName %>MetaControl
		protected $mct<%= $objTable->ClassName %>;

		// Controls for <%= $objTable->ClassName %>'s Data Fields
<% foreach ($objTable->ColumnArray as $objColumn) { %>
		public $<%= $objCodeGen->FormControlVariableNameForColumn($objColumn); %>;
<% } %>

		// Other ListBoxes (if applicable) via Unique ReverseReferences and ManyToMany References
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
		public $<%= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); %>;
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
		public $<%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>;
<% } %>

		// Other Controls
		public $btnSave;
		public $btnDelete;
		public $btnCancel;

		// Callback
		protected $strClosePanelMethod;

		public function __construct($objParentObject, $strClosePanelMethod, <% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>$<%= $objColumn->VariableName; %> = null, <% } %>$strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Setup Callback and Template
			$this->strTemplate = '<%= $objTable->ClassName %>EditPanel.tpl.php';
			$this->strClosePanelMethod = $strClosePanelMethod;

			// Construct the <%= $objTable->ClassName %>MetaControl
			// MAKE SURE we specify "$this" as the MetaControl's (and thus all subsequent controls') parent
			$this->mct<%= $objTable->ClassName %> = <%= $objTable->ClassName %>MetaControl::Create($this<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>, $<%= $objColumn->VariableName; %><% } %>);

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
			$this->btnSave->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnSave_Click'));
			$this->btnSave->CausesValidation = $this;

			$this->btnCancel = new QButton($this);
			$this->btnCancel->Text = QApplication::Translate('Cancel');
			$this->btnCancel->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnCancel_Click'));

			$this->btnDelete = new QButton($this);
			$this->btnDelete->Text = QApplication::Translate('Delete');
			$this->btnDelete->AddAction(new QClickEvent(), new QConfirmAction(QApplication::Translate('Are you SURE you want to DELETE this') . ' ' . QApplication::Translate('<%= $objTable->ClassName %>') . '?'));
			$this->btnDelete->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnDelete_Click'));
			$this->btnDelete->Visible = $this->mct<%= $objTable->ClassName %>->EditMode;
		}

		// Control AjaxAction Event Handlers
		public function btnSave_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Save" processing to the <%= $objTable->ClassName %>MetaControl
			$this->mct<%= $objTable->ClassName %>->Save<%= $objTable->ClassName %>();
			$this->CloseSelf(true);
		}

		public function btnDelete_Click($strFormId, $strControlId, $strParameter) {
			// Delegate "Delete" processing to the <%= $objTable->ClassName %>MetaControl
			$this->mct<%= $objTable->ClassName %>->Delete<%= $objTable->ClassName %>();
			$this->CloseSelf(true);
		}

		public function btnCancel_Click($strFormId, $strControlId, $strParameter) {
			$this->CloseSelf(false);
		}

		// Close Myself and Call ClosePanelMethod Callback
		protected function CloseSelf($blnChangesMade) {
			$strMethod = $this->strClosePanelMethod;
			$this->objForm->$strMethod($blnChangesMade);
		}
	}
?>