// General Variables
		protected $<%= $objCodeGen->VariableNameFromTable($objTable->Name); %>;
		protected $objParentObject;
		protected $strTitleVerb;
		protected $blnEditMode;

		// Controls that allow the editing of <%= $objTable->ClassName %>'s individual data fields
<% foreach ($objTable->ColumnArray as $objColumn) { %>
		protected $<%= $objCodeGen->FormControlVariableNameForColumn($objColumn); %>;
<% } %>

		// Controls that allow the viewing of <%= $objTable->ClassName %>'s individual data fields
<% foreach ($objTable->ColumnArray as $objColumn) { %>
<% if (!$objColumn->Identity && !$objColumn->Timestamp) { %>
		protected $<%= $objCodeGen->FormLabelVariableNameForColumn($objColumn); %>;
<% } %>
<% } %>

		// QListBox Controls (if applicable) to edit Unique ReverseReferences and ManyToMany References
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
		protected $<%= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); %>;
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
		protected $<%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>;
<% } %>

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
		protected $<%= $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference); %>;
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
		protected $<%= $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference); %>;
<% } %>