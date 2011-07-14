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
<% } %><% if (!$objColumn->NotNull) { %>
			$this-><%=$strControlId %>->AddItem(QApplication::Translate('- Select One -'), null);
<% } %>
			foreach (<%= $objColumn->Reference->VariableType %>::$NameArray as $intId => $strValue)
				$this-><%= $strControlId %>->AddItem(new QListItem($strValue, $intId, $this-><%= $strObjectName %>-><%= $objColumn->PropertyName %> == $intId));
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
			$this-><%= $strLabelId %>->Text = ($this-><%= $strObjectName %>-><%= $objColumn->PropertyName %>) ? <%= $objColumn->Reference->VariableType %>::$NameArray[$this-><%= $strObjectName %>-><%= $objColumn->PropertyName %>] : null;
<% if ($objColumn->NotNull) { %>
			$this-><%=$strLabelId %>->Required = true;
<% } %>
			return $this-><%= $strLabelId %>;
		}