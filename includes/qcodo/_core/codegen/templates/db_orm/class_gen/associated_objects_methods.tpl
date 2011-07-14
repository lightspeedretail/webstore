///////////////////////////////
		// ASSOCIATED OBJECTS' METHODS
		///////////////////////////////

<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %><% if (!$objReverseReference->Unique) { %>
	<%@ associated_object('objTable', 'objReverseReference'); %>
<% } %><% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
	<%@ associated_object_manytomany('objTable', 'objManyToManyReference'); %>
<% } %>
