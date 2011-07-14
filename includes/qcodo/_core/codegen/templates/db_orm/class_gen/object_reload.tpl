/**
		 * Reload this <%= $objTable->ClassName %> from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved <%= $objTable->ClassName %> object.');

			// Reload the Object
			$objReloaded = <%= $objTable->ClassName %>::Load(<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>$this-><%=$objColumn->VariableName%>, <% } %><%--%>);

			// Update $this's local variables to match
<% foreach ($objTable->ColumnArray as $objColumn) { %>
<% if (!$objColumn->Identity) { %>
<% if ($objColumn->Reference) { %>
			$this-><%= $objColumn->PropertyName %> = $objReloaded-><%= $objColumn->PropertyName %>;
<% } %><% if (!$objColumn->Reference) { %>
			$this-><%= $objColumn->VariableName %> = $objReloaded-><%= $objColumn->VariableName %>;
<% } %><% if ($objColumn->PrimaryKey) { %>
			$this->__<%= $objColumn->VariableName %> = $this-><%= $objColumn->VariableName %>;
<% } %><% } %><% } %>
		}