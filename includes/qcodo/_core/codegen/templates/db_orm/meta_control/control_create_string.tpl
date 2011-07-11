		/**
		 * Create and setup QTextBox <%= $strControlId %>
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function <%= $strControlId %>_Create($strControlId = null) {
			$this-><%= $strControlId %> = new QTextBox($this->objParentObject, $strControlId);
			$this-><%= $strControlId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objColumn->PropertyName) %>');
			$this-><%= $strControlId %>->Text = $this-><%= $strObjectName %>-><%= $objColumn->PropertyName %>;
<% if ($objColumn->NotNull) { %>
			$this-><%=$strControlId %>->Required = true;
<% } %>
<% if ($objColumn->DbType == QDatabaseFieldType::Blob) { %>
			$this-><%=$strControlId %>->TextMode = QTextMode::MultiLine;
<% } %>
<% if (($objColumn->VariableType == QType::String) && (is_numeric($objColumn->Length))) { %>
			$this-><%=$strControlId %>->MaxLength = <%= $strClassName %>::<%= $objColumn->PropertyName %>MaxLength;
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
			$this-><%= $strLabelId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objColumn->PropertyName) %>');
			$this-><%= $strLabelId %>->Text = $this-><%= $strObjectName %>-><%= $objColumn->PropertyName %>;
<% if ($objColumn->NotNull) { %>
			$this-><%=$strLabelId %>->Required = true;
<% } %>
			return $this-><%= $strLabelId %>;
		}