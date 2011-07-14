		/**
		 * Create and setup QListBox <%= $strControlId %>
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function <%= $strControlId %>_Create($strControlId = null) {
			$this-><%= $strControlId %> = new QListBox($this->objParentObject, $strControlId);
			$this-><%= $strControlId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objReverseReference->ObjectPropertyName) %>');
			$this-><%=$strControlId %>->AddItem(QApplication::Translate('- Select One -'), null);
			$<%= $objReverseReference->VariableName %>Array = <%= $objReverseReference->VariableType %>::LoadAll();
			if ($<%= $objReverseReference->VariableName %>Array) foreach ($<%= $objReverseReference->VariableName %>Array as $<%= $objReverseReference->VariableName %>) {
				$objListItem = new QListItem($<%= $objReverseReference->VariableName %>->__toString(), $<%= $objReverseReference->VariableName %>-><%= $objCodeGen->GetTable($objReverseReference->Table)->PrimaryKeyColumnArray[0]->PropertyName %>);
				if ($<%= $objReverseReference->VariableName %>-><%= $objReverseReference->PropertyName %> == $this-><%= $strObjectName %>-><%= $objTable->PrimaryKeyColumnArray[0]->PropertyName %>)
					$objListItem->Selected = true;
				$this-><%=$strControlId %>->AddItem($objListItem);
			}
<% if ($objReverseReference->NotNull) { %>
			// Because <%= $objReverseReference->VariableType %>'s <%= $objReverseReference->ObjectPropertyName %> is not null, if a value is already selected, it cannot be changed.
			if ($this-><%=$strControlId %>->SelectedValue)
				$this-><%=$strControlId %>->Enabled = false;
<% } %>
			return $this-><%= $strControlId %>;
		}

		/**
		 * Create and setup QLabel <%= $strLabelId %>
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <%= $strLabelId %>_Create($strControlId = null) {
			$this-><%= $strLabelId %> = new QLabel($this->objParentObject, $strControlId);
			$this-><%= $strLabelId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objReverseReference->ObjectPropertyName) %>');
			$this-><%= $strLabelId %>->Text = ($this-><%= $strObjectName %>-><%= $objReverseReference->ObjectPropertyName %>) ? $this-><%= $strObjectName %>-><%= $objReverseReference->ObjectPropertyName %>->__toString() : null;
			return $this-><%= $strLabelId %>;
		}