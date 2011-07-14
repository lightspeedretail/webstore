/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				// General MetaControlVariables
				case '<%= $objTable->ClassName %>': return $this-><%= $objCodeGen->VariableNameFromTable($objTable->Name); %>;
				case 'TitleVerb': return $this->strTitleVerb;
				case 'EditMode': return $this->blnEditMode;

				// Controls that point to <%= $objTable->ClassName %> fields -- will be created dynamically if not yet created
<% foreach ($objTable->ColumnArray as $objColumn) { %><%
	$strControlId = $objCodeGen->FormControlVariableNameForColumn($objColumn);
	$strLabelId = $objCodeGen->FormLabelVariableNameForColumn($objColumn);
	$strPropertyName = $objColumn->PropertyName;
%><%@ property_get_case('strControlId', 'strLabelId', 'strPropertyName'); %>
<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %><% if ($objReverseReference->Unique) { %><%
		$strControlId = $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);
		$strLabelId = $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference);
		$strPropertyName = $objReverseReference->ObjectDescription;
%><%@ property_get_case('strControlId', 'strLabelId', 'strPropertyName'); %>
<% } %><% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %><%
	$strControlId = $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);
	$strLabelId = $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference);
	$strPropertyName = $objManyToManyReference->ObjectDescription;
%><%@ property_get_case('strControlId', 'strLabelId', 'strPropertyName'); %>
<% } %>
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}