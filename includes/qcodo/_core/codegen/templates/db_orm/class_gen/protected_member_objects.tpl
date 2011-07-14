///////////////////////////////
		// PROTECTED MEMBER OBJECTS
		///////////////////////////////

<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { %>
		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column <%= $objTable->Name %>.<%= $objColumn->Name %>.
		 *
		 * NOTE: Always use the <%= $objColumn->Reference->PropertyName %> property getter to correctly retrieve this <%= $objColumn->Reference->VariableType %> object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var <%= $objColumn->Reference->VariableType %> <%= $objColumn->Reference->VariableName %>
		 */
		protected $<%= $objColumn->Reference->VariableName %>;

	<% } %>
<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
		/**
		 * Protected member variable that contains the object which points to
		 * this object by the reference in the unique database column <%= $objReverseReference->Table %>.<%= $objReverseReference->Column %>.
		 *
		 * NOTE: Always use the <%= $objReverseReference->ObjectPropertyName %> property getter to correctly retrieve this <%= $objReverseReference->VariableType %> object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var <%= $objReverseReference->VariableType %> <%= $objReverseReference->ObjectMemberVariable %>
		 */
		protected $<%= $objReverseReference->ObjectMemberVariable %>;
		
		/**
		 * Used internally to manage whether the adjoined <%= $objReverseReference->ObjectDescription %> object
		 * needs to be updated on save.
		 * 
		 * NOTE: Do not manually update this value 
		 */
		protected $blnDirty<%= $objReverseReference->ObjectPropertyName %>;

	<% } %>
<% } %>
