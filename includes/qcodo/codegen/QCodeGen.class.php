<?php
	require(__QCODO_CORE__ . '/codegen/QCodeGenBase.class.php');

	// Feel free to override any core QCodeGenBase methods here
	class QCodeGen extends QCodeGenBase {
		// Example: Overriding the Pluralize method
		protected function Pluralize($strName) {
			// Special Rules go Here
			switch (true) {
				case ($strName == 'person'):
					return 'people';
				case ($strName == 'Person'):
					return 'People';
				case ($strName == 'PERSON'):
					return 'PEOPLE';

				// Trying to be cute here...
				case (strtolower($strName) == 'fish'):
					return $strName . 'ies';

				// Otherwise, call parent
				default:
					return parent::Pluralize($strName);
			}
		}
	}

	require(__QCODO_CORE__ . '/codegen/library.inc.php');
?>