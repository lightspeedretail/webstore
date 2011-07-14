///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
<% foreach ($objTable->IndexArray as $objIndex) { %>
	<% if ($objIndex->Unique) { %>
		<%@ index_load_single('objTable', 'objIndex'); %>
	<% } %><% if (!$objIndex->Unique) { %>
		<%@ index_load_array('objTable', 'objIndex'); %>
	<% } %>
<% } %>



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
	<%@ index_load_array_manytomany('objTable', 'objManyToManyReference') %>
<% } %>
