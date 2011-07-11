	 * property-read <%= $objTable->ClassName %> $<%= $objTable->ClassName %> the actual <%= $objTable->ClassName %> data class being edited
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	 * property <%= $objCodeGen->FormControlClassForColumn($objColumn); %> $<%= $objColumn->PropertyName %>Control
	 * property-read QLabel $<%= $objColumn->PropertyName %>Label
<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %><% if ($objReverseReference->Unique) { %>
	 * property QListBox $<%=$objReverseReference->ObjectDescription%>Control
	 * property-read QLabel $<%=$objReverseReference->ObjectDescription%>Label
<% } %><% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
	 * property QListBox $<%=$objManyToManyReference->ObjectDescription%>Control
	 * property-read QLabel $<%=$objManyToManyReference->ObjectDescription%>Label
<% } %>
	 * property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * property-read boolean $EditMode a boolean indicating whether or not this is being edited or created