/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * <%= $objTable->ClassName %>MetaControl to edit a single <%= $objTable->ClassName %> object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single <%= $objTable->ClassName %> object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <%= $objTable->ClassName %>MetaControl
		 * @param <%= $objTable->ClassName %> $<%= $objCodeGen->VariableNameFromTable($objTable->Name); %> new or existing <%= $objTable->ClassName %> object
		 */
		 public function __construct($objParentObject, <%= $objTable->ClassName %> $<%= $objCodeGen->VariableNameFromTable($objTable->Name); %>) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this <%= $objTable->ClassName %>MetaControl)
			$this->objParentObject = $objParentObject;

			// Setup linked <%= $objTable->ClassName %> object
			$this-><%= $objCodeGen->VariableNameFromTable($objTable->Name); %> = $<%= $objCodeGen->VariableNameFromTable($objTable->Name); %>;

			// Figure out if we're Editing or Creating New
			if ($this-><%= $objCodeGen->VariableNameFromTable($objTable->Name); %>->__Restored) {
				$this->strTitleVerb = QApplication::Translate('Edit');
				$this->blnEditMode = true;
			} else {
				$this->strTitleVerb = QApplication::Translate('Create');
				$this->blnEditMode = false;
			}
		 }

		/**
		 * Static Helper Method to Create using PK arguments
		 * You must pass in the PK arguments on an object to load, or leave it blank to create a new one.
		 * If you want to load via QueryString or PathInfo, use the CreateFromQueryString or CreateFromPathInfo
		 * static helper methods.  Finally, specify a CreateType to define whether or not we are only allowed to 
		 * edit, or if we are also allowed to create a new one, etc.
		 * 
		 * @param mixed $objParentObject QForm or QPanel which will be using this <%= $objTable->ClassName %>MetaControl
<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>
		 * @param <%= $objColumn->VariableType %> $<%= $objColumn->VariableName %> primary key value
<% } %>
		 * @param QMetaControlCreateType $intCreateType rules governing <%= $objTable->ClassName %> object creation - defaults to CreateOrEdit
 		 * @return <%= $objTable->ClassName %>MetaControl
		 */
		public static function Create($objParentObject, <% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>$<%= $objColumn->VariableName %> = null, <% } %>$intCreateType = QMetaControlCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>strlen($<%= $objColumn->VariableName %>) && <% } %><%----%>) {
				$<%= $objCodeGen->VariableNameFromTable($objTable->Name); %> = <%= $objTable->ClassName %>::Load(<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>$<%= $objColumn->VariableName %>, <% } %><%--%>);

				// <%= $objTable->ClassName %> was found -- return it!
				if ($<%= $objCodeGen->VariableNameFromTable($objTable->Name); %>)
					return new <%= $objTable->ClassName %>MetaControl($objParentObject, $<%= $objCodeGen->VariableNameFromTable($objTable->Name); %>);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QMetaControlCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a <%= $objTable->ClassName %> object with PK arguments: ' . <% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>$<%= $objColumn->VariableName %> . ', ' . <% } %><%----------%>);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QMetaControlCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new <%= $objTable->ClassName %>MetaControl($objParentObject, new <%= $objTable->ClassName %>());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <%= $objTable->ClassName %>MetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing <%= $objTable->ClassName %> object creation - defaults to CreateOrEdit
		 * @return <%= $objTable->ClassName %>MetaControl
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>
			$<%= $objColumn->VariableName %> = QApplication::PathInfo(<%= $_INDEX %>);
<% } %>
			return <%= $objTable->ClassName %>MetaControl::Create($objParentObject, <% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>$<%= $objColumn->VariableName %>, <% } %>$intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this <%= $objTable->ClassName %>MetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing <%= $objTable->ClassName %> object creation - defaults to CreateOrEdit
		 * @return <%= $objTable->ClassName %>MetaControl
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>
			$<%= $objColumn->VariableName %> = QApplication::QueryString('<%= $objColumn->VariableName %>');
<% } %>
			return <%= $objTable->ClassName %>MetaControl::Create($objParentObject, <% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>$<%= $objColumn->VariableName %>, <% } %>$intCreateType);
		}