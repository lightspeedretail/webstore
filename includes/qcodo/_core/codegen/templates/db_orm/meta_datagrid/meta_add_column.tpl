/**
		 * Given the description of the Column's contents, this is a simple, express
		 * way of adding a column to this <%= $objTable->ClassName %> datagrid.  The description of a column's
		 * content can be either a text string description of a simple field name
		 * in the <%= $objTable->ClassName %> object, or it can be any QQNode extending from QQN::<%= $objTable->ClassName %>().
		 * 
		 * MetaAddColumn will automatically pre-configure the column with the name, html
		 * and sort rules given the content being specified.
		 * 
		 * Any of these things can be overridden with OverrideParameters.
		 * 
		 * Finally, $mixContents can also be an array of contents, if displaying and/or
		 * sorting using two fields from the <%= $objTable->ClassName %> object.
		 *
		 * @param mixed $mixContents
		 * @param string $objOverrideParameters[]
		 * @return QDataGridColumn
		 */
		public function MetaAddColumn($mixContent, $objOverrideParameters = null) {
			if (is_array($mixContent)) {
				$objNodeArray = array();

				try {
					foreach ($mixContent as $mixItem) {
						$objNodeArray[] = $this->ResolveContentItem($mixItem);
					}
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

				if (count($objNodeArray) == 0)
					throw new QCallerException('No content specified');

				// Create Various Arrays to be used by DGC
				$strNameArray = '';
				$strHtmlArray = '';
				$objSort = array();
				$objSortDescending = array();
				foreach ($objNodeArray as $objNode) {
					$strNameArray[] = QApplication::Translate(QConvertNotation::WordsFromCamelCase($objNode->_PropertyName));
					$strHtmlArray[] = $objNode->GetDataGridHtml();
					$objSort[] = $objNode->GetDataGridOrderByNode();
					$objSortDescending[] = $objNode->GetDataGridOrderByNode();
					$objSortDescending[] = false;
				}

				$objNewColumn = new QDataGridColumn(
					implode(', ', $strNameArray), 
					'<?=' . implode(' . ", " . ', $strHtmlArray) . '?>',
					array(
						'OrderByClause' => new QQOrderBy($objNodeArray),
						'ReverseOrderByClause' => new QQOrderBy($objSortDescending)
					)
				);
			} else {
				try {
					$objNode = $this->ResolveContentItem($mixContent);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

				$objNewColumn = new QDataGridColumn(
					QApplication::Translate(QConvertNotation::WordsFromCamelCase($objNode->_PropertyName)),
					'<?=' . $objNode->GetDataGridHtml() . '?>',
					array(
						'OrderByClause' => QQ::OrderBy($objNode->GetDataGridOrderByNode()),
						'ReverseOrderByClause' => QQ::OrderBy($objNode->GetDataGridOrderByNode(), false)
					)
				);
			}

			$objOverrideArray = func_get_args();
			if (count($objOverrideArray) > 1)
				try {
					unset($objOverrideArray[0]);
					$objNewColumn->OverrideAttributes($objOverrideArray);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			$this->AddColumn($objNewColumn);
			return $objNewColumn;
		}