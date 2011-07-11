////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="<%= $objTable->ClassName %>"><sequence>';
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if (!$objColumn->Reference || $objColumn->Reference->IsType) { %>
			$strToReturn .= '<element name="<%= $objColumn->PropertyName %>" type="xsd:<%= QType::SoapType($objColumn->VariableType) %>"/>';
	<% } %><% if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { %>
			$strToReturn .= '<element name="<%= $objColumn->Reference->PropertyName %>" type="xsd1:<%= $objColumn->Reference->VariableType %>"/>';
	<% } %>
<% } %>
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('<%= $objTable->ClassName %>', $strComplexTypeArray)) {
				$strComplexTypeArray['<%= $objTable->ClassName %>'] = <%= $objTable->ClassName %>::GetSoapComplexTypeXml();
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { %>
				<%= $objColumn->Reference->VariableType%>::AlterSoapComplexTypeArray($strComplexTypeArray);
	<% } %>
<% } %>
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, <%= $objTable->ClassName %>::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new <%= $objTable->ClassName %>();
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if (!$objColumn->Reference || $objColumn->Reference->IsType) { %>
			if (property_exists($objSoapObject, '<%= $objColumn->PropertyName %>'))
<% if ($objColumn->VariableType != QType::DateTime) { %>
				$objToReturn-><%= $objColumn->VariableName %> = $objSoapObject-><%= $objColumn->PropertyName %>;
<% } %><% if ($objColumn->VariableType == QType::DateTime) { %>
				$objToReturn-><%= $objColumn->VariableName %> = new QDateTime($objSoapObject-><%= $objColumn->PropertyName %>);
<% } %>
	<% } %><% if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { %>
			if ((property_exists($objSoapObject, '<%= $objColumn->Reference->PropertyName %>')) &&
				($objSoapObject-><%= $objColumn->Reference->PropertyName %>))
				$objToReturn-><%= $objColumn->Reference->PropertyName %> = <%= $objColumn->Reference->VariableType %>::GetObjectFromSoapObject($objSoapObject-><%= $objColumn->Reference->PropertyName %>);
	<% } %>
<% } %>
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, <%= $objTable->ClassName %>::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ($objColumn->VariableType == QType::DateTime) { %>
			if ($objObject-><%= $objColumn->VariableName %>)
				$objObject-><%= $objColumn->VariableName %> = $objObject-><%= $objColumn->VariableName %>->__toString(QDateTime::FormatSoap);
	<% } %><% if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { %>
			if ($objObject-><%= $objColumn->Reference->VariableName %>)
				$objObject-><%= $objColumn->Reference->VariableName %> = <%= $objColumn->Reference->VariableType %>::GetSoapObjectFromObject($objObject-><%= $objColumn->Reference->VariableName %>, false);
			else if (!$blnBindRelatedObjects)
				$objObject-><%= $objColumn->VariableName %> = null;
	<% } %>
<% } %>
			return $objObject;
		}
