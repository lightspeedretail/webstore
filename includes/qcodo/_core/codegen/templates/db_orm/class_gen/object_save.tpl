/**
		 * Save this <%= $objTable->ClassName %>
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
<%
	foreach ($objArray = $objTable->ColumnArray as $objColumn)
		if ($objColumn->Identity)
			return '		 * @return int';
	return '		 * @return void';
%>
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = <%= $objTable->ClassName %>::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO <%= $strEscapeIdentifierBegin %><%= $objTable->Name %><%= $strEscapeIdentifierEnd %> (
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) { %>
							<%= $strEscapeIdentifierBegin %><%= $objColumn->Name %><%= $strEscapeIdentifierEnd %>,
	<% } %>
<% } %><%--%>
						) VALUES (
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) { %>
							' . $objDatabase->SqlVariable($this-><%= $objColumn->VariableName %>) . ',
	<% } %>
<% } %><%--%>
						)
					');

<%
	foreach ($objArray = $objTable->PrimaryKeyColumnArray as $objColumn)
		if ($objColumn->Identity)
			return sprintf('					// Update Identity column and return its value
					$mixToReturn = $this->%s = $objDatabase->InsertId(\'%s\', \'%s\');',
					$objColumn->VariableName, $objTable->Name, $objColumn->Name);
%>
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ($objColumn->Timestamp) { %>
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								<%= $strEscapeIdentifierBegin %><%= $objColumn->Name %><%= $strEscapeIdentifierEnd %>
							FROM
								<%= $strEscapeIdentifierBegin %><%= $objTable->Name %><%= $strEscapeIdentifierEnd %>
							WHERE
<% foreach ($objTable->PrimaryKeyColumnArray as $objPkColumn) { %>
	<% if ($objPkColumn->Identity) { %>
								<%= $strEscapeIdentifierBegin %><%= $objPkColumn->Name %><%= $strEscapeIdentifierEnd %> = ' . $objDatabase->SqlVariable($this-><%= $objPkColumn->VariableName %>) . ' AND
	<% } %><% if (!$objPkColumn->Identity) { %>
								<%= $strEscapeIdentifierBegin %><%= $objPkColumn->Name %><%= $strEscapeIdentifierEnd %> = ' . $objDatabase->SqlVariable($this->__<%= $objPkColumn->VariableName %>) . ' AND
	<% } %>
<% } %><%-----%>
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this-><%= $objColumn->VariableName %>)
							throw new QOptimisticLockingException('<%= $objTable->ClassName %>');
					}
	<% } %>
<% } %>

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							<%= $strEscapeIdentifierBegin %><%= $objTable->Name %><%= $strEscapeIdentifierEnd %>
						SET
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) { %>
							<%= $strEscapeIdentifierBegin %><%= $objColumn->Name %><%= $strEscapeIdentifierEnd %> = ' . $objDatabase->SqlVariable($this-><%= $objColumn->VariableName %>) . ',
	<% } %>
<% } %><%--%>
						WHERE
<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>
	<% if ($objColumn->Identity) { %>
							<%= $strEscapeIdentifierBegin %><%= $objColumn->Name %><%= $strEscapeIdentifierEnd %> = ' . $objDatabase->SqlVariable($this-><%= $objColumn->VariableName %>) . ' AND
	<% } %><% if (!$objColumn->Identity) { %>
							<%= $strEscapeIdentifierBegin %><%= $objColumn->Name %><%= $strEscapeIdentifierEnd %> = ' . $objDatabase->SqlVariable($this->__<%= $objColumn->VariableName %>) . ' AND
	<% } %>
<% } %><%-----%>
					');
				}

<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
		<% $objReverseReferenceTable = $objCodeGen->TableArray[strtolower($objReverseReference->Table)]; %>
		<% $objReverseReferenceColumn = $objReverseReferenceTable->ColumnArray[strtolower($objReverseReference->Column)]; %>
				// Update the adjoined <%= $objReverseReference->ObjectDescription %> object (if applicable)
				// TODO: Make this into hard-coded SQL queries
				if ($this->blnDirty<%= $objReverseReference->ObjectPropertyName %>) {
					// Unassociate the old one (if applicable)
					if ($objAssociated = <%= $objReverseReference->VariableType %>::LoadBy<%= $objReverseReferenceColumn->PropertyName %>(<%= $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray) %>)) {
						$objAssociated-><%= $objReverseReferenceColumn->PropertyName %> = null;
						$objAssociated->Save();
					}

					// Associate the new one (if applicable)
					if ($this-><%= $objReverseReference->ObjectMemberVariable %>) {
						$this-><%= $objReverseReference->ObjectMemberVariable %>-><%= $objReverseReferenceColumn->PropertyName %> = $this-><%= $objTable->PrimaryKeyColumnArray[0]->VariableName %>;
						$this-><%= $objReverseReference->ObjectMemberVariable %>->Save();
					}

					// Reset the "Dirty" flag
					$this->blnDirty<%= $objReverseReference->ObjectPropertyName %> = false;
				}
	<% } %>
<% } %>
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Update __blnRestored and any Non-Identity PK Columns (if applicable)
			$this->__blnRestored = true;
<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>
	<% if ((!$objColumn->Identity) && ($objColumn->PrimaryKey)) { %>
			$this->__<%= $objColumn->VariableName %> = $this-><%= $objColumn->VariableName %>;
	<% } %>
<% } %>

<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ($objColumn->Timestamp) { %>
			// Update Local Timestamp
			$objResult = $objDatabase->Query('
				SELECT
					<%= $strEscapeIdentifierBegin %><%= $objColumn->Name %><%= $strEscapeIdentifierEnd %>
				FROM
					<%= $strEscapeIdentifierBegin %><%= $objTable->Name %><%= $strEscapeIdentifierEnd %>
				WHERE
<% foreach ($objTable->PrimaryKeyColumnArray as $objPkColumn) { %>
	<% if ($objPkColumn->Identity) { %>
					<%= $strEscapeIdentifierBegin %><%= $objPkColumn->Name %><%= $strEscapeIdentifierEnd %> = ' . $objDatabase->SqlVariable($this-><%= $objPkColumn->VariableName %>) . ' AND
	<% } %><% if (!$objPkColumn->Identity) { %>
					<%= $strEscapeIdentifierBegin %><%= $objPkColumn->Name %><%= $strEscapeIdentifierEnd %> = ' . $objDatabase->SqlVariable($this->__<%= $objPkColumn->VariableName %>) . ' AND
	<% } %>
<% } %><%-----%>
			');
						
			$objRow = $objResult->FetchArray();
			$this-><%= $objColumn->VariableName %> = $objRow[0];
	<% } %>
<% } %>

			// Return 
			return $mixToReturn;
		}