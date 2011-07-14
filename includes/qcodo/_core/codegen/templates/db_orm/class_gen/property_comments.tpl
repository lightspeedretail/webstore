<% foreach ($objTable->ColumnArray as $objColumn) { %>
	 * @property <%= $objColumn->VariableType %> $<%= $objColumn->PropertyName %> the value for <%= $objColumn->VariableName %> <% if ($objColumn->Identity) return '(Read-Only PK)'; else if ($objColumn->PrimaryKey) return '(PK)'; else if ($objColumn->Timestamp) return '(Read-Only Timestamp)'; else if ($objColumn->Unique) return '(Unique)'; else if ($objColumn->NotNull) return '(Not Null)'; %>
<% } %>
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { %>
	 * @property <%= $objColumn->Reference->VariableType %> $<%= $objColumn->Reference->PropertyName %> the value for the <%= $objColumn->Reference->VariableType %> object referenced by <%= $objColumn->VariableName %> <% if ($objColumn->Identity) return '(Read-Only PK)'; else if ($objColumn->PrimaryKey) return '(PK)'; else if ($objColumn->Unique) return '(Unique)'; else if ($objColumn->NotNull) return '(Not Null)'; %>
	<% } %>
<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
	 * @property <%= $objReverseReference->VariableType %> $<%= $objReverseReference->ObjectPropertyName %> the value for the <%= $objReverseReference->VariableType %> object that uniquely references this <%= $objTable->ClassName %>
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objReference) { %>
	 * @property <%= $objReference->VariableType %> $_<%=$objReference->ObjectDescription%> the value for the private _obj<%=$objReference->ObjectDescription%> (Read-Only) if set due to an expansion on the <%=$objReference->Table%> association table
	 * @property <%= $objReference->VariableType %>[] $_<%=$objReference->ObjectDescription%>Array the value for the private _obj<%=$objReference->ObjectDescription%>Array (Read-Only) if set due to an ExpandAsArray on the <%=$objReference->Table%> association table
<% } %><% foreach ($objTable->ReverseReferenceArray as $objReference) { %><% if (!$objReference->Unique) { %>
	 * @property <%= $objReference->VariableType %> $_<%=$objReference->ObjectDescription%> the value for the private _obj<%=$objReference->ObjectDescription%> (Read-Only) if set due to an expansion on the <%=$objReference->Table%>.<%=$objReference->Column%> reverse relationship
	 * @property <%= $objReference->VariableType %>[] $_<%=$objReference->ObjectDescription%>Array the value for the private _obj<%=$objReference->ObjectDescription%>Array (Read-Only) if set due to an ExpandAsArray on the <%=$objReference->Table%>.<%=$objReference->Column%> reverse relationship
<% } %><% } %>
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)