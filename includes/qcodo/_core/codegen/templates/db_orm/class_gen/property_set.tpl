		/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) { %>
				case '<%= $objColumn->PropertyName %>':
					// Sets the value for <%= $objColumn->VariableName %> <% if ($objColumn->PrimaryKey) return '(PK)'; else if ($objColumn->Unique) return '(Unique)'; else if ($objColumn->NotNull) return '(Not Null)'; %>
					// @param <%= $objColumn->VariableType %> $mixValue
					// @return <%= $objColumn->VariableType %>
					try {
		<% if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { %>
						$this-><%= $objColumn->Reference->VariableName %> = null;
		<% } %>
						return ($this-><%= $objColumn->VariableName %> = QType::Cast($mixValue, <%= $objColumn->VariableTypeAsConstant %>));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

	<% } %>
<% } %>

				///////////////////
				// Member Objects
				///////////////////
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { %>
				case '<%= $objColumn->Reference->PropertyName %>':
					// Sets the value for the <%= $objColumn->Reference->VariableType %> object referenced by <%= $objColumn->VariableName %> <% if ($objColumn->Identity) return '(Read-Only PK)'; else if ($objColumn->PrimaryKey) return '(PK)'; else if ($objColumn->Unique) return '(Unique)'; else if ($objColumn->NotNull) return '(Not Null)'; %>
					// @param <%= $objColumn->Reference->VariableType %> $mixValue
					// @return <%= $objColumn->Reference->VariableType %>
					if (is_null($mixValue)) {
						$this-><%= $objColumn->VariableName %> = null;
						$this-><%= $objColumn->Reference->VariableName %> = null;
						return null;
					} else {
						// Make sure $mixValue actually is a <%= $objColumn->Reference->VariableType %> object
						try {
							$mixValue = QType::Cast($mixValue, '<%= $objColumn->Reference->VariableType %>');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED <%= $objColumn->Reference->VariableType %> object
						if (is_null($mixValue-><%= $objCodeGen->TableArray[strtolower($objColumn->Reference->Table)]->ColumnArray[strtolower($objColumn->Reference->Column)]->PropertyName %>))
							throw new QCallerException('Unable to set an unsaved <%= $objColumn->Reference->PropertyName %> for this <%= $objTable->ClassName %>');

						// Update Local Member Variables
						$this-><%= $objColumn->Reference->VariableName %> = $mixValue;
						$this-><%= $objColumn->VariableName %> = $mixValue-><%= $objCodeGen->TableArray[strtolower($objColumn->Reference->Table)]->ColumnArray[strtolower($objColumn->Reference->Column)]->PropertyName %>;

						// Return $mixValue
						return $mixValue;
					}
					break;

	<% } %>
<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
				case '<%= $objReverseReference->ObjectPropertyName %>':
					// Sets the value for the <%= $objReverseReference->VariableType %> object referenced by <%= $objReverseReference->ObjectMemberVariable %> (Unique)
					// @param <%= $objReverseReference->VariableType %> $mixValue
					// @return <%= $objReverseReference->VariableType %>
					if (is_null($mixValue)) {
						$this-><%= $objReverseReference->ObjectMemberVariable %> = null;

						// Make sure we update the adjoined <%= $objReverseReference->VariableType %> object the next time we call Save()
						$this->blnDirty<%= $objReverseReference->ObjectPropertyName %> = true;

						return null;
					} else {
						// Make sure $mixValue actually is a <%= $objReverseReference->VariableType %> object
						try {
							$mixValue = QType::Cast($mixValue, '<%= $objReverseReference->VariableType %>');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}

						// Are we setting <%= $objReverseReference->ObjectMemberVariable %> to a DIFFERENT $mixValue?
						if ((!$this-><%= $objReverseReference->ObjectPropertyName %>) || ($this-><%= $objReverseReference->ObjectPropertyName %>-><%= $objCodeGen->GetTable($objReverseReference->Table)->PrimaryKeyColumnArray[0]->PropertyName %> != $mixValue-><%= $objCodeGen->GetTable($objReverseReference->Table)->PrimaryKeyColumnArray[0]->PropertyName %>)) {
							// Yes -- therefore, set the "Dirty" flag to true
							// to make sure we update the adjoined <%= $objReverseReference->VariableType %> object the next time we call Save()
							$this->blnDirty<%= $objReverseReference->ObjectPropertyName %> = true;

							// Update Local Member Variable
							$this-><%= $objReverseReference->ObjectMemberVariable %> = $mixValue;
						} else {
							// Nope -- therefore, make no changes
						}

						// Return $mixValue
						return $mixValue;
					}
					break;

	<% } %>
<% } %>
				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}