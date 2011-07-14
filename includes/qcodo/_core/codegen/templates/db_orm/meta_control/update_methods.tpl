<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %><%
		// Use the "control_update_manytomany_reference" subtemplate to generate the code
		// required to create/setup the control.
		$mixArguments = array(
			'objManyToManyReference' => $objManyToManyReference,
			'strObjectName' => $objCodeGen->VariableNameFromTable($objTable->Name),
			'strClassName' => $objTable->ClassName,
			'strControlId' => $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference)
		);

		// Get the subtemplate and evaluate
		return $objCodeGen->EvaluateSubTemplate('control_update_manytomany_reference.tpl', $strModuleName, $mixArguments) . "\n";
%><% } %>