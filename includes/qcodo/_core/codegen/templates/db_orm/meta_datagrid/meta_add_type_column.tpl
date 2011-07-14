/**
		 * Similar to MetaAddColumn, except it creates a column for a Type-based Id.  You MUST specify
		 * the name of the Type class that this will attempt to use $NameArray against.
		 * 
		 * Also, $mixContent cannot be an array.  Only a single field can be specified.
		 *
		 * @param mixed $mixContent string or QQNode from <%= $objTable->ClassName %>
		 * @param string $strTypeClassName the name of the TypeClass to use $NameArray against
		 * @param mixed $objOverrideParameters
		 */
		public function MetaAddTypeColumn($mixContent, $strTypeClassName, $objOverrideParameters = null) {
			// Validate TypeClassName
			if (!class_exists($strTypeClassName) || !property_exists($strTypeClassName, 'NameArray'))
				throw new QCallerException('Invalid TypeClass Name: ' . $strTypeClassName);

			// Validate Node
			try {
				$objNode = $this->ResolveContentItem($mixContent);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Create the Column
			$strName = QConvertNotation::WordsFromCamelCase($objNode->_PropertyName);
			if (strtolower(substr($strName, strlen($strName) - 3)) == ' id')
				$strName = substr($strName, 0, strlen($strName) - 3);
			$strProperty = $objNode->GetDataGridHtml();
			$objNewColumn = new QDataGridColumn(
				QApplication::Translate($strName),
				sprintf('<?=(%s) ? %s::$NameArray[%s] : null;?>', $strProperty, $strTypeClassName, $strProperty),
				array(
					'OrderByClause' => QQ::OrderBy($objNode),
					'ReverseOrderByClause' => QQ::OrderBy($objNode, false)
				)
			);

			// Perform Overrides
			$objOverrideArray = func_get_args();
			if (count($objOverrideArray) > 2)
				try {
					unset($objOverrideArray[0]);
					unset($objOverrideArray[1]);
					$objNewColumn->OverrideAttributes($objOverrideArray);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			$this->AddColumn($objNewColumn);
			return $objNewColumn;
		}