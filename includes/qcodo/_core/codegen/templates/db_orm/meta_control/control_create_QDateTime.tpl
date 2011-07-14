		/**
		 * Create and setup QDateTimePicker <%= $strControlId %>
		 * @param string $strControlId optional ControlId to use
		 * @return QDateTimePicker
		 */
		public function <%= $strControlId %>_Create($strControlId = null) {
			$this-><%= $strControlId %> = new QDateTimePicker($this->objParentObject, $strControlId);
			$this-><%= $strControlId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objColumn->PropertyName) %>');
			$this-><%= $strControlId %>->DateTime = $this-><%= $strObjectName %>-><%= $objColumn->PropertyName %>;
			$this-><%= $strControlId %>->DateTimePickerType = QDateTimePickerType::<%
	switch ($objColumn->DbType) {
		case QDatabaseFieldType::DateTime:
			return 'DateTime';
		case QDatabaseFieldType::Time:
			return 'Time';
		default:
			return 'Date';
	}
%>;
<% if ($objColumn->NotNull) { %>
			$this-><%=$strControlId %>->Required = true;
<% } %>
			return $this-><%= $strControlId %>;
		}

		/**
		 * Create and setup QLabel <%= $strLabelId %>
		 * @param string $strControlId optional ControlId to use
		 * @param string $strDateTimeFormat optional DateTimeFormat to use
		 * @return QLabel
		 */
		public function <%= $strLabelId %>_Create($strControlId = null, $strDateTimeFormat = null) {
			$this-><%= $strLabelId %> = new QLabel($this->objParentObject, $strControlId);
			$this-><%= $strLabelId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objColumn->PropertyName) %>');
			$this->str<%= $objColumn->PropertyName %>DateTimeFormat = $strDateTimeFormat;
			$this-><%= $strLabelId %>->Text = sprintf($this-><%= $strObjectName %>-><%= $objColumn->PropertyName %>) ? $this-><%= $strObjectName %>-><%= $objColumn->PropertyName %>->__toString($this->str<%= $objColumn->PropertyName %>DateTimeFormat) : null;
<% if ($objColumn->NotNull) { %>
			$this-><%=$strLabelId %>->Required = true;
<% } %>
			return $this-><%= $strLabelId %>;
		}

		protected $str<%= $objColumn->PropertyName %>DateTimeFormat;