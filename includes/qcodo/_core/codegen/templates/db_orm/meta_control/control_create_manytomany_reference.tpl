		/**
		 * Create and setup QListBox <%= $strControlId %>
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function <%= $strControlId %>_Create($strControlId = null) {
			$this-><%= $strControlId %> = new QListBox($this->objParentObject, $strControlId);
			$this-><%= $strControlId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural) %>');
			$this-><%=$strControlId %>->SelectionMode = QSelectionMode::Multiple;
			$objAssociatedArray = $this-><%= $strObjectName %>->Get<%= $objManyToManyReference->ObjectDescription; %>Array();
			$<%= $objManyToManyReference->VariableName %>Array = <%= $objManyToManyReference->VariableType %>::LoadAll();
			if ($<%= $objManyToManyReference->VariableName %>Array) foreach ($<%= $objManyToManyReference->VariableName %>Array as $<%= $objManyToManyReference->VariableName %>) {
				$objListItem = new QListItem($<%= $objManyToManyReference->VariableName %>->__toString(), $<%= $objManyToManyReference->VariableName %>-><%= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName %>);
				foreach ($objAssociatedArray as $objAssociated) {
					if ($objAssociated-><%= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName %> == $<%= $objManyToManyReference->VariableName %>-><%= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName %>)
						$objListItem->Selected = true;
				}
				$this-><%=$strControlId %>->AddItem($objListItem);
			}
			return $this-><%= $strControlId %>;
		}

		/**
		 * Create and setup QLabel <%= $strLabelId %>
		 * @param string $strControlId optional ControlId to use
		 * @param string $strGlue glue to display in between each associated object
		 * @return QLabel
		 */
		public function <%= $strLabelId %>_Create($strControlId = null, $strGlue = ', ') {
			$this-><%= $strLabelId %> = new QLabel($this->objParentObject, $strControlId);
			$this-><%= $strControlId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural) %>');
			
			$objAssociatedArray = $this-><%= $strObjectName %>->Get<%= $objManyToManyReference->ObjectDescription; %>Array();
			$strItems = array();
			foreach ($objAssociatedArray as $objAssociated)
				$strItems[] = $objAssociated->__toString();
			$this-><%= $strLabelId %>->Text = implode($strGlue, $strItems);
			return $this-><%= $strLabelId %>;
		}