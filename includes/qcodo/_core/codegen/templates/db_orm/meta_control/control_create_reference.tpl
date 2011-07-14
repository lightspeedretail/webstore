		/**
		 * Create and setup QListBox <%= $strControlId %>
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function <%= $strControlId %>_Create($strControlId = null) {
			$this-><%= $strControlId %> = new QListBox($this->objParentObject, $strControlId);
			$this-><%= $strControlId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objColumn->Reference->PropertyName) %>');
<% if ($objColumn->NotNull) { %>
			$this-><%=$strControlId %>->Required = true;
			if (!$this->blnEditMode)
				$this-><%=$strControlId %>->AddItem(QApplication::Translate('- Select One -'), null);
<% } %><% if (!$objColumn->NotNull) { %>
			$this-><%=$strControlId %>->AddItem(QApplication::Translate('- Select One -'), null);
<% } %>
			$<%= $objColumn->Reference->VariableName %>Array = <%= $objColumn->Reference->VariableType %>::LoadAll();
			if ($<%= $objColumn->Reference->VariableName %>Array) foreach ($<%= $objColumn->Reference->VariableName %>Array as $<%= $objColumn->Reference->VariableName %>) {
				$objListItem = new QListItem($<%= $objColumn->Reference->VariableName %>->__toString(), $<%= $objColumn->Reference->VariableName %>-><%= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName %>);
				if (($this-><%= $strObjectName %>-><%= $objColumn->Reference->PropertyName %>) && ($this-><%= $strObjectName %>-><%= $objColumn->Reference->PropertyName %>-><%= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName %> == $<%= $objColumn->Reference->VariableName %>-><%= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName %>))
					$objListItem->Selected = true;
				$this-><%=$strControlId %>->AddItem($objListItem);
			}
			return $this-><%= $strControlId %>;
		}

		/**
		 * Create and setup QLabel <%= $strLabelId %>
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <%= $strLabelId %>_Create($strControlId = null) {
			$this-><%= $strLabelId %> = new QLabel($this->objParentObject, $strControlId);
			$this-><%= $strLabelId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objColumn->Reference->PropertyName) %>');
			$this-><%= $strLabelId %>->Text = ($this-><%= $strObjectName %>-><%= $objColumn->Reference->PropertyName %>) ? $this-><%= $strObjectName %>-><%= $objColumn->Reference->PropertyName %>->__toString() : null;
<% if ($objColumn->NotNull) { %>
			$this-><%=$strLabelId %>->Required = true;
<% } %>
			return $this-><%= $strLabelId %>;
		}