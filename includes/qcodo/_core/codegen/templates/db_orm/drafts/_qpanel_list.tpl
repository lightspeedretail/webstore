<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<%= __PANEL_DRAFTS__ %>" TargetFileName="<%= $objTable->ClassName %>ListPanel.class.php"/>
<?php
	/**
	 * This is the abstract Panel class for the List All functionality
	 * of the <%= $objTable->ClassName %> class.  This code-generated class
	 * contains a datagrid to display an HTML page that can
	 * list a collection of <%= $objTable->ClassName %> objects.  It includes
	 * functionality to perform pagination and sorting on columns.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QPanel which extends this <%= $objTable->ClassName %>ListPanelBase
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent re-
	 * code generation.
	 * 
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage Drafts
	 * 
	 */
	class <%= $objTable->ClassName %>ListPanel extends QPanel {
		// Local instance of the Meta DataGrid to list <%= $objTable->ClassNamePlural %>
		public $dtg<%= $objTable->ClassNamePlural %>;

		// Other public QControls in this panel
		public $btnCreateNew;
		public $pxyEdit;

		// Callback Method Names
		protected $strSetEditPanelMethod;
		protected $strCloseEditPanelMethod;
		
		public function __construct($objParentObject, $strSetEditPanelMethod, $strCloseEditPanelMethod, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Record Method Callbacks
			$this->strSetEditPanelMethod = $strSetEditPanelMethod;
			$this->strCloseEditPanelMethod = $strCloseEditPanelMethod;

			// Setup the Template
			$this->Template = '<%= $objTable->ClassName %>ListPanel.tpl.php';

			// Instantiate the Meta DataGrid
			$this->dtg<%= $objTable->ClassNamePlural %> = new <%= $objTable->ClassName %>DataGrid($this);

			// Style the DataGrid (if desired)
			$this->dtg<%= $objTable->ClassNamePlural %>->CssClass = 'datagrid';
			$this->dtg<%= $objTable->ClassNamePlural %>->AlternateRowStyle->CssClass = 'alternate';

			// Add Pagination (if desired)
			$this->dtg<%= $objTable->ClassNamePlural %>->Paginator = new QPaginator($this->dtg<%= $objTable->ClassNamePlural %>);
			$this->dtg<%= $objTable->ClassNamePlural %>->ItemsPerPage = 8;

			// Use the MetaDataGrid functionality to add Columns for this datagrid

			// Create an Edit Column
			$this->pxyEdit = new QControlProxy($this);
			$this->pxyEdit->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'pxyEdit_Click'));
			$this->dtg<%= $objTable->ClassNamePlural %>->MetaAddEditProxyColumn($this->pxyEdit, 'Edit', 'Edit');

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

			// Setup the Create New button
			$this->btnCreateNew = new QButton($this);
			$this->btnCreateNew->Text = QApplication::Translate('Create a New') . ' ' . QApplication::Translate('<%=$objTable->ClassName%>');
			$this->btnCreateNew->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnCreateNew_Click'));
		}

		public function pxyEdit_Click($strFormId, $strControlId, $strParameter) {
			$strParameterArray = explode(',', $strParameter);
			$objEditPanel = new <%= $objTable->ClassName %>EditPanel($this, $this->strCloseEditPanelMethod<% $strParameterList = ''; for ($intIndex = 0; $intIndex < count ($objTable->PrimaryKeyColumnArray); $intIndex++) $strParameterList .= ', $strParameterArray[' . $intIndex . ']'; return $strParameterList; %>);

			$strMethodName = $this->strSetEditPanelMethod;
			$this->objForm->$strMethodName($objEditPanel);
		}

		public function btnCreateNew_Click($strFormId, $strControlId, $strParameter) {
			$objEditPanel = new <%= $objTable->ClassName %>EditPanel($this, $this->strCloseEditPanelMethod, null);
			$strMethodName = $this->strSetEditPanelMethod;
			$this->objForm->$strMethodName($objEditPanel);
		}
	}
?>