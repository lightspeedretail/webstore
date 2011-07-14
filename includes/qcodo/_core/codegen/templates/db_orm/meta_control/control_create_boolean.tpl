		/**
		 * Create and setup QCheckBox <%= $strControlId %>
		 * @param string $strControlId optional ControlId to use
		 * @return QCheckBox
		 */
		public function <%= $strControlId %>_Create($strControlId = null) {
			$this-><%= $strControlId %> = new QCheckBox($this->objParentObject, $strControlId);
			$this-><%= $strControlId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objColumn->PropertyName) %>');
			$this-><%= $strControlId %>->Checked = $this-><%= $strObjectName %>-><%= $objColumn->PropertyName %>;
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
			$this-><%= $strLabelId %>->Text = ($this-><%= $strObjectName %>-><%= $objColumn->PropertyName %>) ? QApplication::Translate('Yes') : QApplication::Translate('No');
			return $this-><%= $strLabelId %>;
		}