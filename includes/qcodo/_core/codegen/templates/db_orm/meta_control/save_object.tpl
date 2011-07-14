/**
		 * This will save this object's <%= $objTable->ClassName; %> instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function Save<%= $objTable->ClassName; %>() {
			try {
				// Update any fields for controls that have been created
<% foreach ($objTable->ColumnArray as $objColumn) { %><%
	if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) {
		// Use the "control_create_" subtemplates to generate the code
		// required to create/setup the control.
		$mixArguments = array(
			'objColumn' => $objColumn,
			'strObjectName' => $objCodeGen->VariableNameFromTable($objTable->Name),
			'strClassName' => $objTable->ClassName,
			'strControlId' => $objCodeGen->FormControlVariableNameForColumn($objColumn)
		);
	
		// Figure out WHICH "control_create_" to use
		if ($objColumn->Reference) {
			if ($objColumn->Reference->IsType)
				$strTemplateFilename = 'type';
			else
				$strTemplateFilename = 'reference';
		} else switch ($objColumn->VariableType) {
			case QType::Boolean:
				$strTemplateFilename = 'checkbox';
				break;
			case QType::DateTime:
				$strTemplateFilename = 'calendar';
				break;
			default:
				$strTemplateFilename = 'textbox';
				break;
		}

		// Get the subtemplate and evaluate
		return $objCodeGen->EvaluateSubTemplate(sprintf('control_update_%s.tpl', $strTemplateFilename), $strModuleName, $mixArguments) . "\n";
	} else
		return null;
%><% } %>

				// Update any UniqueReverseReferences (if any) for controls that have been created for it
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %><%
	if ($objReverseReference->Unique) {
		// Use the "control_update_unique_reversereference" subtemplate to generate the code
		// required to create/setup the control.
		$mixArguments = array(
			'objReverseReference' => $objReverseReference,
			'strObjectName' => $objCodeGen->VariableNameFromTable($objTable->Name),
			'strClassName' => $objTable->ClassName,
			'strControlId' => $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference)
		);
	
		// Get the subtemplate and evaluate
		return $objCodeGen->EvaluateSubTemplate('control_update_unique_reversereference.tpl', $strModuleName, $mixArguments) . "\n";
	} else
		return null;
%><% } %>

				// Save the <%= $objTable->ClassName; %> object
				$this-><%= $objCodeGen->VariableNameFromTable($objTable->Name); %>->Save();

				// Finally, update any ManyToManyReferences (if any)
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
				$this-><%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>_Update();
<% } %>
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}