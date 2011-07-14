/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					// Controls that point to <%= $objTable->ClassName %> fields
<% foreach ($objTable->ColumnArray as $objColumn) { %><%
	$strControlId = $objCodeGen->FormControlVariableNameForColumn($objColumn);
	$strPropertyName = $objColumn->PropertyName . 'Control';
	$strClassName = $objCodeGen->FormControlTypeForColumn($objColumn);
%><%@ property_set_case('strControlId', 'strPropertyName', 'strClassName'); %>
<% } %>
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %><% if ($objReverseReference->Unique) { %><%
		$strControlId = $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);
		$strPropertyName = $objReverseReference->ObjectDescription . 'Control';
		$strClassName = 'QListBox';
%><%@ property_set_case('strControlId', 'strPropertyName', 'strClassName'); %>
<% } %><% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %><%
	$strControlId = $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);
	$strPropertyName = $objManyToManyReference->ObjectDescription . 'Control';
	$strClassName = 'QListBox';
%><%@ property_set_case('strControlId', 'strPropertyName', 'strClassName'); %>
<% } %>
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}