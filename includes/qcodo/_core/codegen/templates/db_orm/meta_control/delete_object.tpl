/**
		 * This will DELETE this object's <%= $objTable->ClassName; %> instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function Delete<%= $objTable->ClassName; %>() {
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
			$this-><%= $objCodeGen->VariableNameFromTable($objTable->Name) %>->UnassociateAll<%= $objManyToManyReference->ObjectDescriptionPlural %>();
<% } %>
			$this-><%= $objCodeGen->VariableNameFromTable($objTable->Name); %>->Delete();
		}