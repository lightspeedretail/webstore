		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
<% foreach ($objTable->ColumnArray as $objColumn) { %>
				case '<%= $objColumn->PropertyName %>':
					// Gets the value for <%= $objColumn->VariableName %> <% if ($objColumn->Identity) return '(Read-Only PK)'; else if ($objColumn->PrimaryKey) return '(PK)'; else if ($objColumn->Timestamp) return '(Read-Only Timestamp)'; else if ($objColumn->Unique) return '(Unique)'; else if ($objColumn->NotNull) return '(Not Null)'; %>
					// @return <%= $objColumn->VariableType %>
					return $this-><%= $objColumn->VariableName %>;

<% } %>

				///////////////////
				// Member Objects
				///////////////////
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { %>
				case '<%= $objColumn->Reference->PropertyName %>':
					// Gets the value for the <%= $objColumn->Reference->VariableType %> object referenced by <%= $objColumn->VariableName %> <% if ($objColumn->Identity) return '(Read-Only PK)'; else if ($objColumn->PrimaryKey) return '(PK)'; else if ($objColumn->Unique) return '(Unique)'; else if ($objColumn->NotNull) return '(Not Null)'; %>
					// @return <%= $objColumn->Reference->VariableType %>
					try {
						if ((!$this-><%= $objColumn->Reference->VariableName %>) && (!is_null($this-><%= $objColumn->VariableName %>)))
							$this-><%= $objColumn->Reference->VariableName %> = <%= $objColumn->Reference->VariableType %>::Load($this-><%= $objColumn->VariableName %>);
						return $this-><%= $objColumn->Reference->VariableName %>;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

	<% } %>
<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
		<% $objReverseReferenceTable = $objCodeGen->TableArray[strtolower($objReverseReference->Table)]; %>
		<% $objReverseReferenceColumn = $objReverseReferenceTable->ColumnArray[strtolower($objReverseReference->Column)]; %>
				case '<%= $objReverseReference->ObjectPropertyName %>':
					// Gets the value for the <%= $objReverseReference->VariableType %> object that uniquely references this <%= $objTable->ClassName %>
					// by <%= $objReverseReference->ObjectMemberVariable %> (Unique)
					// @return <%= $objReverseReference->VariableType %>
					try {
						if ($this-><%= $objReverseReference->ObjectMemberVariable %> === false)
							// We've attempted early binding -- and the reverse reference object does not exist
							return null;
						if (!$this-><%= $objReverseReference->ObjectMemberVariable %>)
							$this-><%= $objReverseReference->ObjectMemberVariable %> = <%= $objReverseReference->VariableType %>::LoadBy<%= $objReverseReferenceColumn->PropertyName %>(<%= $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray) %>);
						return $this-><%= $objReverseReference->ObjectMemberVariable %>;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

	<% } %>
<% } %>

				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

<% foreach ($objTable->ManyToManyReferenceArray as $objReference) { %>
				case '_<%=$objReference->ObjectDescription%>':
					// Gets the value for the private _obj<%=$objReference->ObjectDescription%> (Read-Only)
					// if set due to an expansion on the <%=$objReference->Table%> association table
					// @return <%= $objReference->VariableType %>
					return $this->_obj<%=$objReference->ObjectDescription%>;

				case '_<%=$objReference->ObjectDescription%>Array':
					// Gets the value for the private _obj<%=$objReference->ObjectDescription%>Array (Read-Only)
					// if set due to an ExpandAsArray on the <%=$objReference->Table%> association table
					// @return <%= $objReference->VariableType %>[]
					return (array) $this->_obj<%=$objReference->ObjectDescription%>Array;

<% } %><% foreach ($objTable->ReverseReferenceArray as $objReference) { %><% if (!$objReference->Unique) { %>
				case '_<%=$objReference->ObjectDescription%>':
					// Gets the value for the private _obj<%=$objReference->ObjectDescription%> (Read-Only)
					// if set due to an expansion on the <%=$objReference->Table%>.<%=$objReference->Column%> reverse relationship
					// @return <%= $objReference->VariableType %>
					return $this->_obj<%=$objReference->ObjectDescription%>;

				case '_<%=$objReference->ObjectDescription%>Array':
					// Gets the value for the private _obj<%=$objReference->ObjectDescription%>Array (Read-Only)
					// if set due to an ExpandAsArray on the <%=$objReference->Table%>.<%=$objReference->Column%> reverse relationship
					// @return <%= $objReference->VariableType %>[]
					return (array) $this->_obj<%=$objReference->ObjectDescription%>Array;

<% } %><% } %>

				case '__Restored':
					return $this->__blnRestored;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}