<?php
	abstract class QQBaseNode extends QBaseClass {
		protected $objParentNode;
		protected $strType;
		protected $strName;
		protected $strPropertyName;
		protected $strRootTableName;

		protected $strTableName;
		protected $strPrimaryKey;
		protected $strClassName;

		public function __get($strName) {
			switch ($strName) {
				case '_ParentNode':
					return $this->objParentNode;
				case '_Name':
					return $this->strName;
				case '_PropertyName':
					return $this->strPropertyName;
				case '_Type':
					return $this->strType;
				case '_RootTableName':
					return $this->strRootTableName;
				case '_TableName':
					return $this->strTableName;
				case '_PrimaryKey':
					return $this->strPrimaryKey;
				case '_ClassName':
					return $this->strClassName;
				case '_PrimaryKeyNode':
					return null;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
		
		abstract public function GetColumnAliasHelper(QQueryBuilder $objBuilder, $strBegin, $strEnd, $blnExpandSelection);
	}


	class QQNode extends QQBaseNode {
		public function __construct($strName, $strPropertyName, $strType, $objParentNode = null) {
			$this->objParentNode = $objParentNode;
			$this->strName = $strName;
			$this->strPropertyName = $strPropertyName;
			$this->strType = $strType;
			if ($objParentNode) {
				if (version_compare(PHP_VERSION, '5.1.0') == -1)
					$this->strRootTableName = $objParentNode->__get('_RootTableName');
				else
					$this->strRootTableName = $objParentNode->_RootTableName;
			} else
				$this->strRootTableName = $strName;
		}

		/**
		 * @param mixed $mixValue
		 * @param QQueryBuilder $objBuilder
		 * @param boolean $blnEquality can be null (for no equality), true (to add a standard "equal to") or false (to add a standard "not equal to")
		 * @return string
		 */
		public function GetValue($mixValue, QQueryBuilder $objBuilder, $blnEqualityType = null) {
			if ($mixValue instanceof QQNamedValue)
				return $mixValue->Parameter($blnEqualityType);

			if ($mixValue instanceof QQNode) {
				if (is_null($blnEqualityType))
					$strToReturn = '';
				else if ($blnEqualityType)
					$strToReturn = '= ';
				else
					$strToReturn = '!= ';
				
				try {
					return $strToReturn . $mixValue->GetColumnAlias($objBuilder);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			} else {
				if (is_null($blnEqualityType)) {
					$blnIncludeEquality = false;
					$blnReverseEquality = false;
				} else {
					$blnIncludeEquality = true;
					if ($blnEqualityType)
						$blnReverseEquality = false;
					else
						$blnReverseEquality = true;
				}

//				try {
//					return $objBuilder->Database->SqlVariable(QType::Cast($mixValue, $this->_Type), $blnIncludeEquality, $blnReverseEquality);
//				} catch (QCallerException $objExc) {
//					$objExc->IncrementOffset();
//					$objExc->IncrementOffset();
//					throw $objExc;
//				}
				return $objBuilder->Database->SqlVariable($mixValue, $blnIncludeEquality, $blnReverseEquality);
			}
		}
		
		public function GetAsManualSqlColumn() {
			if ($this->strTableName)
				return $this->strTableName . '.' . $this->strName;
			else if (($this->objParentNode) && ($this->objParentNode->strTableName))
				return $this->objParentNode->strTableName . '.' . $this->strName;
			else
				return $this->strName;
		}

		public function GetColumnAlias(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null) {
			// Make sure our Root Tables Match
			if ($this->_RootTableName != $objBuilder->RootTableName)
				throw new QCallerException('Cannot use QQNode for "' . $this->_RootTableName . '" when querying against the "' . $objBuilder->RootTableName . '" table', 3);

			// Pull the Begin and End Escape Identifiers from the Database Adapter
			$strBegin = $objBuilder->Database->EscapeIdentifierBegin;
			$strEnd = $objBuilder->Database->EscapeIdentifierEnd;

			// If we are a standard QQNode at the top level column, simply return the column name
			if ((get_class($this) == 'QQNode') && (is_null($this->objParentNode->_Type)))
				return sprintf('%s%s%s.%s%s%s',
					$strBegin, $objBuilder->GetTableAlias($this->objParentNode->_Name), $strEnd,
					$strBegin, $this->strName, $strEnd);
			else {
				// Use the Helper to Iterate Through the Parent Chain and get the Parent Alias
				try {
					$strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $strBegin, $strEnd, $blnExpandSelection);

					if ($this->strTableName) {
						// Next, Join the Appropriate Table
						$objBuilder->AddJoinItem($this->strTableName, $strParentAlias . '__' . $this->strName,
							$strParentAlias, $this->strName, $this->strPrimaryKey, $objJoinCondition);

						if ($blnExpandSelection)
							call_user_func(array($this->strClassName, 'GetSelectFields'), $objBuilder, $strParentAlias . '__' . $this->strName);
					}
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

				// Finally, return the final column alias name (Parent Prefix with Current Node Name)
				return sprintf('%s%s%s.%s%s%s',
					$strBegin, $objBuilder->GetTableAlias($strParentAlias), $strEnd,
					$strBegin, $this->strName, $strEnd);
			}
		}

		public function GetColumnAliasHelper(QQueryBuilder $objBuilder, $strBegin, $strEnd, $blnExpandSelection, QQCondition $objJoinCondition = null) {
			// Are we at the Parent Node?
			if (is_null($this->objParentNode))
				// Yep -- Simply return the Parent Node Name
				return $this->strName;
			else {
				try {
					// No -- First get the Parent Alias
					$strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $strBegin, $strEnd, $blnExpandSelection);

					// Next, Join the Appropriate Table
					$objBuilder->AddJoinItem($this->strTableName, $strParentAlias . '__' . $this->strName,
						$strParentAlias, $this->strName, $this->strPrimaryKey, $objJoinCondition);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

				// Next, Expand the Selection Fields for this Table (if applicable)
				if ($blnExpandSelection) {
					call_user_func(array($this->strClassName, 'GetSelectFields'), $objBuilder, $strParentAlias . '__' . $this->strName);
				}

				// Return the Parent Alias
				return $strParentAlias . '__' . $this->strName;
			}
		}


		//////////////////
		// Helpers for Orm-generated DataGrids
		//////////////////
		protected function GetDataGridHtmlHelper($strNodeLabelArray, $intIndex) {
			if (($intIndex + 1) == count($strNodeLabelArray))
				return $strNodeLabelArray[$intIndex];
			else
				return sprintf('(%s ? %s : null)', $strNodeLabelArray[$intIndex], $this->GetDataGridHtmlHelper($strNodeLabelArray, $intIndex + 1));
		}
		public function GetDataGridHtml() {
			// Array-ify Node Hierarchy
			$objNodeArray = array();

			$objNodeArray[] = $this;
			while ($objNodeArray[count($objNodeArray) - 1]->objParentNode)
				$objNodeArray[] = $objNodeArray[count($objNodeArray) - 1]->objParentNode;

			$objNodeArray = array_reverse($objNodeArray, false);

			// Go through the objNodeArray to build out the DataGridHtml

			// Error Behavior
			if (count($objNodeArray) < 2)
				throw new Exception('Invalid QQNode to GetDataGridHtml on');

			// Simple Two-Step Node
			else if (count($objNodeArray) == 2)
				$strToReturn = '$_ITEM->' . $objNodeArray[1]->strPropertyName;

			// Complex N-Step Node
			else {
				$strNodeLabelArray[0] = '$_ITEM->' . $objNodeArray[1]->strPropertyName;
				for ($intIndex = 2; $intIndex < count($objNodeArray); $intIndex++) {
					$strNodeLabelArray[$intIndex - 1] = $strNodeLabelArray[$intIndex - 2] . '->' . $objNodeArray[$intIndex]->strPropertyName;
				}

				$strToReturn = $this->GetDataGridHtmlHelper($strNodeLabelArray, 0);
			}

			if (class_exists($this->strClassName))
				return sprintf('(%s) ? %s->__toString() : null;', $strToReturn, $strToReturn);

			return $strToReturn;
		}
		public function GetDataGridOrderByNode() {
			if ($this instanceof QQReverseReferenceNode)
				return $this->_PrimaryKeyNode;
			else
				return $this;
		}
	}

	class QQReverseReferenceNode extends QQNode {
		protected $strForeignKey;

		public function __construct($objParentNode, $strName, $strType, $strForeignKey, $strPropertyName = null) {
			$this->objParentNode = $objParentNode;
			if ($objParentNode) {
				if (version_compare(PHP_VERSION, '5.1.0') == -1)
					$this->strRootTableName = $objParentNode->__get('_RootTableName');
				else
					$this->strRootTableName = $objParentNode->_RootTableName;
			} else
				throw new QCallerException('ReverseReferenceNodes must have a Parent Node');
			$this->strName = $strName;
			$this->strType = $strType;
			$this->strForeignKey = $strForeignKey;
			$this->strPropertyName = $strPropertyName;
		}

		public function GetColumnAlias(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null) {
			// Make sure our Root Tables Match
			if ($this->_RootTableName != $objBuilder->RootTableName)
				throw new QCallerException('Cannot use QQNode for "' . $this->_RootTableName . '" when querying against the "' . $objBuilder->RootTableName . '" table', 3);

			// Pull the Begin and End Escape Identifiers from the Database Adapter
			$strBegin = $objBuilder->Database->EscapeIdentifierBegin;
			$strEnd = $objBuilder->Database->EscapeIdentifierEnd;

			// If we are a standard QQNode at the top level column, simply return the column name
			if ((get_class($this) == 'QQNode') && (is_null($this->objParentNode->_Type)))
/*				return sprintf('%s%s%s.%s%s%s',
					$strBegin, $this->objParentNode->_Name, $strEnd,
					$strBegin, $this->strName, $strEnd);*/
				return;
			else {
				// Use the Helper to Iterate Through the Parent Chain and get the Parent Alias
				$strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $strBegin, $strEnd, $blnExpandSelection);

				if ($this->strTableName) {
					// Next, Join the Appropriate Table
					$objBuilder->AddJoinItem($this->strTableName, $strParentAlias . '__' . $this->strName, $strParentAlias, $this->objParentNode->_PrimaryKey, $this->strForeignKey, $objJoinCondition);

					if ($blnExpandSelection)
						call_user_func(array($this->strClassName, 'GetSelectFields'), $objBuilder, $strParentAlias . '__' . $this->strName);
				}

				// Finally, return the final column alias name (Parent Prefix with Current Node Name)
/*				return sprintf('%s%s%s.%s%s%s',
					$strBegin, $strParentAlias, $strEnd,
					$strBegin, $this->strName, $strEnd);*/
				return;
			}
		}

		public function GetColumnAliasHelper(QQueryBuilder $objBuilder, $strBegin, $strEnd, $blnExpandSelection, QQCondition $objJoinCondition = null) {
			// Are we at the Parent Node?
			if (is_null($this->objParentNode))
				// Yep -- Simply return the Parent Node Name
				return $this->strName;
			else {
				// No -- First get the Parent Alias
				$strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $strBegin, $strEnd, $blnExpandSelection);

				// Next, Join the Appropriate Table
				$objBuilder->AddJoinItem($this->strTableName, $strParentAlias . '__' . $this->strName, $strParentAlias, $this->objParentNode->_PrimaryKey, $this->strForeignKey, $objJoinCondition);
				
				// Next, Expand the Selection Fields for this Table (if applicable)
				// TODO: If/when we add assn-based attributes, possibly add selectionfields addition here?
				if ($blnExpandSelection) {
					call_user_func(array($this->strClassName, 'GetSelectFields'), $objBuilder, $strParentAlias . '__' . $this->strName);
				}

				// Return the Parent Alias
				return $strParentAlias . '__' . $this->strName;
			}
		}

		public function GetExpandArrayAlias() {
//			$objNode = $this;
//			$objChildTableNode = $this->_ChildTableNode;
//			$strToReturn = $objChildTableNode->_Name . '__' . $objChildTableNode->_PrimaryKey;
			$strToReturn = $this->strName . '__' . $this->_PrimaryKey;

			$objNode = $this->_ParentNode;
			while ($objNode) {
				$strToReturn = $objNode->_Name . '__' . $strToReturn;
				$objNode = $objNode->_ParentNode;
			}
			
			return $strToReturn;
		}
	}

	class QQAssociationNode extends QQBaseNode {
		public function __construct($objParentNode) {
			$this->objParentNode = $objParentNode;
			if (version_compare(PHP_VERSION, '5.1.0') == -1)
				$this->strRootTableName = $objParentNode->__get('_RootTableName');
			else
				$this->strRootTableName = $objParentNode->_RootTableName;
		}

		public function GetColumnAlias(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null) {

			// Make sure our Root Tables Match
			if ($this->_RootTableName != $objBuilder->RootTableName)
				throw new QCallerException('Cannot use QQNode for "' . $this->_RootTableName . '" when querying against the "' . $objBuilder->RootTableName . '" table', 3);

			// Pull the Begin and End Escape Identifiers from the Database Adapter
			$strBegin = $objBuilder->Database->EscapeIdentifierBegin;
			$strEnd = $objBuilder->Database->EscapeIdentifierEnd;

			// If we are a standard QQNode at the top level column, simply return the column name
			if ((get_class($this) == 'QQNode') && (is_null($this->objParentNode->_Type)))
				return sprintf('%s%s%s.%s%s%s',
					$strBegin, $this->objParentNode->_Name, $strEnd,
					$strBegin, $this->strName, $strEnd);
			else {
				// Use the Helper to Iterate Through the Parent Chain and get the Parent Alias
				$strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $strBegin, $strEnd, $blnExpandSelection);

				if ($this->strTableName) {
					// Next, Join the Appropriate Table
					$objBuilder->AddJoinItem($this->strTableName, $strParentAlias . '__' . $this->strName,
						$strParentAlias, $this->strName, $this->strPrimaryKey);

					if ($blnExpandSelection)
						call_user_func(array($this->strClassName, 'GetSelectFields'), $objBuilder, $strParentAlias . '__' . $this->strName);
				}

				// Finally, return the final column alias name (Parent Prefix with Current Node Name)
				return sprintf('%s%s%s.%s%s%s',
					$strBegin, $strParentAlias, $strEnd,
					$strBegin, $this->strName, $strEnd);
			}
		}
		
		public function GetColumnAliasHelper(QQueryBuilder $objBuilder, $strBegin, $strEnd, $blnExpandSelection) {
			// Are we at the Parent Node?
			if (is_null($this->objParentNode))
				// Yep -- Simply return the Parent Node Name
				return $this->strName;
			else {
				// No -- First get the Parent Alias
				$strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $strBegin, $strEnd, $blnExpandSelection);

				// Next, Join the Appropriate Table
					$objBuilder->AddJoinItem($this->strTableName, $strParentAlias . '__' . $this->strName,
						$strParentAlias, $this->objParentNode->_PrimaryKey, $this->strPrimaryKey);

				// Next, Expand the Selection Fields for this Table (if applicable)
				// TODO: If/when we add assn-based attributes, possibly add selectionfields addition here?
//				if ($blnExpandSelection) {
//					call_user_func(array($this->strClassName, 'GetSelectFields'), $objBuilder, $strParentAlias . '__' . $this->strName);
//				}

				// Return the Parent Alias
				return $strParentAlias . '__' . $this->strName;
			}
		}
		
		public function GetExpandArrayAlias() {
			$objNode = $this;
			$objChildTableNode = $this->_ChildTableNode;
			$strToReturn = $objChildTableNode->_Name . '__' . $objChildTableNode->_PrimaryKey;
			while ($objNode) {
				$strToReturn = $objNode->_Name . '__' . $strToReturn;
				$objNode = $objNode->_ParentNode;
			}
			
			return $strToReturn;
		}
	}
	

	class QQNamedValue extends QQNode {
		const DelimiterCode = 3;
		public function __construct($strName) {
			$this->strName = $strName;
		}
		public function Parameter($blnEqualityType = null) {
			if (is_null($blnEqualityType))
			 	return chr(QQNamedValue::DelimiterCode) . '{' . $this->strName . '}';
			else if ($blnEqualityType)
				return chr(QQNamedValue::DelimiterCode) . '{=' . $this->strName . '=}';
			else
				return chr(QQNamedValue::DelimiterCode) . '{!' . $this->strName . '!}';
		}
	}

	abstract class QQCondition extends QBaseClass {
		protected $strOperator;
		abstract public function UpdateQueryBuilder(QQueryBuilder $objBuilder);
		public function __toString() {
			return 'QQCondition Object';
		}

		protected $blnProcessed;

		/**
		 * Used internally by Qcodo Query to get an individual where clause for a given condition
		 * Mostly used for conditional joins.
		 * 
		 * @param QDatabaseBase $objDatabase
		 * @param string $strRootTableName
		 * @param boolean $blnProcessOnce
		 */
		public function GetWhereClause(QQueryBuilder $objBuilder, $blnProcessOnce = false) {
			if ($blnProcessOnce && $this->blnProcessed)
				return null;

			$this->blnProcessed = true;

			try {
				$objConditionBuilder = new QPartialQueryBuilder($objBuilder);
				$this->UpdateQueryBuilder($objConditionBuilder);
				return $objConditionBuilder->GetWhereStatement();
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}
	class QQConditionAll extends QQCondition {
		public function __construct($mixParameterArray) {
			if (count($mixParameterArray))
				throw new QCallerException('All clause takes in no parameters', 3);
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem('1=1');
		}
	}
	class QQConditionNone extends QQCondition {
		public function __construct($mixParameterArray) {
			if (count($mixParameterArray))
				throw new QCallerException('None clause takes in no parameters', 3);
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem('1=0');
		}
	}
	abstract class QQConditionLogical extends QQCondition {
		protected $objConditionArray;
		protected function CollapseConditions($mixParameterArray) {
			$objConditionArray = array();
			foreach ($mixParameterArray as $mixParameter) {
				if (is_array($mixParameter))
					$objConditionArray = array_merge($objConditionArray, $mixParameter);
				else
					array_push($objConditionArray, $mixParameter);
			}

			foreach ($objConditionArray as $objCondition)
				if (!($objCondition instanceof QQCondition))
					throw new QCallerException('Logical Or/And clause parameters must all be QQCondition objects', 3);

			if (count($objConditionArray))
				return $objConditionArray;
			else
				throw new QCallerException('No parameters passed in to logical Or/And clause', 3);
		}
		public function __construct($mixParameterArray) {
			$objConditionArray = $this->CollapseConditions($mixParameterArray);
			try {
				$this->objConditionArray = QType::Cast($objConditionArray, QType::ArrayType);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$intLength = count($this->objConditionArray);
			if ($intLength) {
				$objBuilder->AddWhereItem('(');
				for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
					if (!($this->objConditionArray[$intIndex] instanceof QQCondition))
						throw new QCallerException($this->strOperator . ' clause has elements that are not Conditions');
					try {
						$this->objConditionArray[$intIndex]->UpdateQueryBuilder($objBuilder);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					if (($intIndex + 1) != $intLength)
						$objBuilder->AddWhereItem($this->strOperator);
				}
				$objBuilder->AddWhereItem(')');
			}
		}
	}
	class QQConditionOr extends QQConditionLogical {
		protected $strOperator = 'OR';
	}
	class QQConditionAnd extends QQConditionLogical {
		protected $strOperator = 'AND';
	}

	class QQConditionNot extends QQCondition {
		protected $objCondition;
		public function __construct(QQCondition $objCondition) {
			$this->objCondition = $objCondition;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem('(NOT');
			try {
				$this->objCondition->UpdateQueryBuilder($objBuilder);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$objBuilder->AddWhereItem(')');
		}
	}

	abstract class QQConditionComparison extends QQCondition {
		public $objQueryNode;
		public $mixOperand;
		public function __construct(QQNode $objQueryNode, $mixOperand) {
			$this->objQueryNode = $objQueryNode;
			if (!$objQueryNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based QQNode', 3);

			if ($mixOperand instanceof QQNamedValue)
				$this->mixOperand = $mixOperand;
			else if ($mixOperand instanceof QQAssociationNode)
				throw new QInvalidCastException('Comparison operand cannot be an Association-based QQNode', 3);
			else if ($mixOperand instanceof QQCondition)
				throw new QInvalidCastException('Comparison operand cannot be a QQCondition', 3);
			else if ($mixOperand instanceof QQClause)
				throw new QInvalidCastException('Comparison operand cannot be a QQClause', 3);
			else if (!($mixOperand instanceof QQNode)) {
//				try {
//					$this->mixOperand = QType::Cast($mixOperand, $objQueryNode->_Type);
//				} catch (QCallerException $objExc) {
//					$objExc->IncrementOffset();
//					$objExc->IncrementOffset();
//					throw $objExc;
//				}
				$this->mixOperand = $mixOperand;
			} else {
				if (!$mixOperand->_ParentNode)
					throw new QInvalidCastException('Unable to cast "' . $mixOperand->_Name . '" table to Column-based QQNode', 3);
				$this->mixOperand = $mixOperand;
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . $this->strOperator . $this->objQueryNode->GetValue($this->mixOperand, $objBuilder));
		}
	}

	class QQConditionIsNull extends QQConditionComparison {
		public function __construct(QQNode $objQueryNode) {
			$this->objQueryNode = $objQueryNode;
			if (!$objQueryNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based QQNode', 3);
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IS NULL');
		}
	}

	class QQConditionIsNotNull extends QQConditionComparison {
		public function __construct(QQNode $objQueryNode) {
			$this->objQueryNode = $objQueryNode;
			if (!$objQueryNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based QQNode', 3);
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IS NOT NULL');
		}
	}

	class QQConditionIn extends QQConditionComparison {
		public function __construct(QQNode $objQueryNode, $mixValuesArray) {
			$this->objQueryNode = $objQueryNode;
			if (!$objQueryNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based QQNode', 3);

			if ($mixValuesArray instanceof QQNamedValue)
				$this->mixOperand = $mixValuesArray;
			else if ($mixValuesArray instanceof QQSubQueryNode)
				$this->mixOperand = $mixValuesArray;
			else {
				try {
					$this->mixOperand = QType::Cast($mixValuesArray, QType::ArrayType);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->mixOperand instanceof QQNamedValue)
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IN (' . $this->mixOperand->Parameter() . ')');
			else if ($this->mixOperand instanceof QQSubQueryNode)
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IN ' . $this->mixOperand->GetColumnAlias($objBuilder));
			else {
				$strParameters = array();
				foreach ($this->mixOperand as $mixParameter) {
					array_push($strParameters, $objBuilder->Database->SqlVariable($mixParameter));
				}
				if (count($strParameters))
					$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IN (' . implode(',', $strParameters) . ')');
				else
					$objBuilder->AddWhereItem('1=0');
			}
		}
	}
	
	class QQConditionNotIn extends QQConditionComparison {
		public function __construct(QQNode $objQueryNode, $mixValuesArray) {
			$this->objQueryNode = $objQueryNode;
			if (!$objQueryNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based QQNode', 3);

			if ($mixValuesArray instanceof QQNamedValue)
				$this->mixOperand = $mixValuesArray;
			else if ($mixValuesArray instanceof QQSubQueryNode)
				$this->mixOperand = $mixValuesArray;
			else {
				try {
					$this->mixOperand = QType::Cast($mixValuesArray, QType::ArrayType);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->mixOperand instanceof QQNamedValue)
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT IN (' . $this->mixOperand->Parameter() . ')');
			else if ($this->mixOperand instanceof QQSubQueryNode)
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT IN ' . $this->mixOperand->GetColumnAlias($objBuilder));
			else {
				$strParameters = array();
				foreach ($this->mixOperand as $mixParameter) {
					array_push($strParameters, $objBuilder->Database->SqlVariable($mixParameter));
				}
				if (count($strParameters))
					$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT IN (' . implode(',', $strParameters) . ')');
				else
					$objBuilder->AddWhereItem('1=1');
			}
		}
	}	

	class QQConditionLike extends QQConditionComparison {
		public function __construct(QQNode $objQueryNode, $strValue) {
			$this->objQueryNode = $objQueryNode;
			if (!$objQueryNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based QQNode', 3);
				
			if ($strValue instanceof QQNamedValue)
				$this->mixOperand = $strValue;
			else {
				try {
					$this->mixOperand = QType::Cast($strValue, QType::String);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->mixOperand instanceof QQNamedValue)
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' LIKE ' . $this->mixOperand->Parameter());
			else
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' LIKE ' . $objBuilder->Database->SqlVariable($this->mixOperand));
		}
	}
	
	class QQConditionNotLike extends QQConditionComparison {
		public function __construct(QQNode $objQueryNode, $strValue) {
			$this->objQueryNode = $objQueryNode;
			if (!$objQueryNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based QQNode', 3);
				
			if ($strValue instanceof QQNamedValue)
				$this->mixOperand = $strValue;
			else {
				try {
					$this->mixOperand = QType::Cast($strValue, QType::String);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->mixOperand instanceof QQNamedValue)
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT LIKE ' . $this->mixOperand->Parameter());
			else
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT LIKE ' . $objBuilder->Database->SqlVariable($this->mixOperand));
		}
	}	
	
	class QQConditionBetween extends QQConditionComparison {
		protected $mixOperandTwo;
		public function __construct(QQNode $objQueryNode, $strMinValue, $strMaxValue) {
			$this->objQueryNode = $objQueryNode;
			if (!$objQueryNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based QQNode', 3);

			try {
				$this->mixOperand = QType::Cast($strMinValue, QType::String);
				$this->mixOperandTwo = QType::Cast($strMaxValue, QType::String);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				$objExc->IncrementOffset();
				throw $objExc;
			}				
				
			if ($strMinValue instanceof QQNamedValue)
				$this->mixOperand = $strMinValue;
			if ($strMaxValue instanceof QQNamedValue)
				$this->mixOperandTwo = $strMaxValue;							

		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->mixOperand instanceof QQNamedValue)
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' BETWEEN ' . $this->mixOperand->Parameter() . ' AND ' . $this->mixOperandTwo->Parameter());
			else
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' BETWEEN ' . $objBuilder->Database->SqlVariable($this->mixOperand) . ' AND ' . $objBuilder->Database->SqlVariable($this->mixOperandTwo));
		}
	}		
	
	class QQConditionNotBetween extends QQConditionComparison {
		protected $mixOperandTwo;
		public function __construct(QQNode $objQueryNode, $strMinValue, $strMaxValue) {
			$this->objQueryNode = $objQueryNode;
			if (!$objQueryNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based QQNode', 3);

			try {
				$this->mixOperand = QType::Cast($strMinValue, QType::String);
				$this->mixOperandTwo = QType::Cast($strMaxValue, QType::String);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				$objExc->IncrementOffset();
				throw $objExc;
			}				
				
			if ($strMinValue instanceof QQNamedValue)
				$this->mixOperand = $strMinValue;
			if ($strMaxValue instanceof QQNamedValue)
				$this->mixOperandTwo = $strMaxValue;							

		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->mixOperand instanceof QQNamedValue)
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT BETWEEN ' . $this->mixOperand->Parameter() . ' AND ' . $this->mixOperandTwo->Parameter());
			else
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT BETWEEN ' . $objBuilder->Database->SqlVariable($this->mixOperand) . ' AND ' . $objBuilder->Database->SqlVariable($this->mixOperandTwo));
		}
	}			

	class QQConditionEqual extends QQConditionComparison {
		protected $strOperator = ' = ';
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			return $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' ' . $this->objQueryNode->GetValue($this->mixOperand, $objBuilder, true));
		}
	}
	class QQConditionNotEqual extends QQConditionComparison {
		protected $strOperator = ' != ';
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			return $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' ' . $this->objQueryNode->GetValue($this->mixOperand, $objBuilder, false));
		}
	}
	class QQConditionGreaterThan extends QQConditionComparison {
		protected $strOperator = ' > ';
	}
	class QQConditionLessThan extends QQConditionComparison {
		protected $strOperator = ' < ';
	}
	class QQConditionGreaterOrEqual extends QQConditionComparison {
		protected $strOperator = ' >= ';
	}
	class QQConditionLessOrEqual extends QQConditionComparison {
		protected $strOperator = ' <= ';
	}

	class QQ {
		/////////////////////////
		// QQCondition Factories
		/////////////////////////

		static public function All() {
			return new QQConditionAll(func_get_args());
		}

		static public function None() {
			return new QQConditionNone(func_get_args());
		}

		static public function OrCondition(/* array and/or parameterized list of QLoad objects*/) {
			return new QQConditionOr(func_get_args());
		}

		static public function AndCondition(/* array and/or parameterized list of QLoad objects*/) {
			return new QQConditionAnd(func_get_args());
		}

		static public function Not(QQCondition $objCondition) {
			return new QQConditionNot($objCondition);
		}

		static public function Equal(QQNode $objQueryNode, $mixValue) {
			return new QQConditionEqual($objQueryNode, $mixValue);
		}
		static public function NotEqual(QQNode $objQueryNode, $mixValue) {
			return new QQConditionNotEqual($objQueryNode, $mixValue);
		}
		static public function GreaterThan(QQNode $objQueryNode, $mixValue) {
			return new QQConditionGreaterThan($objQueryNode, $mixValue);
		}
		static public function GreaterOrEqual(QQNode $objQueryNode, $mixValue) {
			return new QQConditionGreaterOrEqual($objQueryNode, $mixValue);
		}
		static public function LessThan(QQNode $objQueryNode, $mixValue) {
			return new QQConditionLessThan($objQueryNode, $mixValue);
		}
		static public function LessOrEqual(QQNode $objQueryNode, $mixValue) {
			return new QQConditionLessOrEqual($objQueryNode, $mixValue);
		}
		static public function IsNull(QQNode $objQueryNode) {
			return new QQConditionIsNull($objQueryNode);
		}
		static public function IsNotNull(QQNode $objQueryNode) {
			return new QQConditionIsNotNull($objQueryNode);
		}
		static public function In(QQNode $objQueryNode, $mixValuesArray) {
			return new QQConditionIn($objQueryNode, $mixValuesArray);
		}
		static public function NotIn(QQNode $objQueryNode, $mixValuesArray) {
			return new QQConditionNotIn($objQueryNode, $mixValuesArray);
		}		
		static public function Like(QQNode $objQueryNode, $strValue) {
			return new QQConditionLike($objQueryNode, $strValue);
		}
		static public function NotLike(QQNode $objQueryNode, $strValue) {
			return new QQConditionNotLike($objQueryNode, $strValue);
		}
		static public function Between(QQNode $objQueryNode, $strMinValue, $strMaxValue) {
			return new QQConditionBetween($objQueryNode, $strMinValue, $strMaxValue);
		}
		static public function NotBetween(QQNode $objQueryNode, $strMinValue, $strMaxValue) {
			return new QQConditionNotBetween($objQueryNode, $strMinValue, $strMaxValue);
		}		
		
		////////////////////////
		// QQCondition Shortcuts
		////////////////////////
		static public function _(QQNode $objQueryNode, $strSymbol, $mixValue, $mixValueTwo = null) {
			try {
				switch(strtolower(trim($strSymbol))) {
					case '=': return QQ::Equal($objQueryNode, $mixValue);
					case '!=': return QQ::NotEqual($objQueryNode, $mixValue);
					case '>': return QQ::GreaterThan($objQueryNode, $mixValue);
					case '<': return QQ::LessThan($objQueryNode, $mixValue);
					case '>=': return QQ::GreaterOrEqual($objQueryNode, $mixValue);
					case '<=': return QQ::LessOrEqual($objQueryNode, $mixValue);
					case 'in': return QQ::In($objQueryNode, $mixValue);
					case 'not in': return QQ::NotIn($objQueryNode, $mixValue);
					case 'like': return QQ::Like($objQueryNode, $mixValue);
					case 'not like': return QQ::NotLike($objQueryNode, $mixValue);
					case 'is null': return QQ::IsNull($objQueryNode, $mixValue);
					case 'is not null': return QQ::IsNotNull($objQueryNode, $mixValue);
					case 'between': return QQ::Between($objQueryNode, $mixValue, $mixValueTwo);
					case 'not between': return QQ::NotBetween($objQueryNode, $mixValue, $mixValueTwo);
					default:
						throw new QCallerException('Unknown Query Comparison Operation: ' . $strSymbol, 0);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/////////////////////////
		// QQSubQuery Factories
		/////////////////////////

		static public function SubSql($strSql, $objParentQueryNodes = null) {
			$objParentQueryNodeArray = func_get_args();
			return new QQSubQuerySqlNode($strSql, $objParentQueryNodeArray);
		}

		static public function Virtual($strName, QQSubQueryNode $objSubQueryDefinition = null) {
			return new QQVirtualNode($strName, $objSubQueryDefinition);
		}

		/////////////////////////
		// QQClause Factories
		/////////////////////////

		static public function Clause(/* parameterized list of QQClause objects */) {
			$objClauseArray = array();

			foreach (func_get_args() as $objClause)
				if ($objClause) {
					if (!($objClause instanceof QQClause))
						throw new QCallerException('Non-QQClause object was passed in to QQ::Clause');
					else
						array_push($objClauseArray, $objClause);
				}

			return $objClauseArray;
		}

		static public function OrderBy(/* array and/or parameterized list of QQNode objects*/) {
			return new QQOrderBy(func_get_args());
		}

		static public function GroupBy(/* array and/or parameterized list of QQNode objects*/) {
			return new QQGroupBy(func_get_args());
		}

		static public function Count($objNode, $strAttributeName) {
			return new QQCount($objNode, $strAttributeName);
		}

		static public function Sum($objNode, $strAttributeName) {
			return new QQSum($objNode, $strAttributeName);
		}

		static public function Minimum($objNode, $strAttributeName) {
			return new QQMinimum($objNode, $strAttributeName);
		}

		static public function Maximum($objNode, $strAttributeName) {
			return new QQMaximum($objNode, $strAttributeName);
		}

		static public function Average($objNode, $strAttributeName) {
			return new QQAverage($objNode, $strAttributeName);
		}

		static public function Expand($objNode, QQCondition $objJoinCondition = null) {
//			if (gettype($objNode) == 'string')
//				return new QQExpandVirtualNode(new QQVirtualNode($objNode));

			if ($objNode instanceof QQVirtualNode)
				return new QQExpandVirtualNode($objNode);
			else
				return new QQExpand($objNode, $objJoinCondition);
		}

		static public function ExpandAsArray($objNode) {
			return new QQExpandAsArray($objNode);
		}

		static public function LimitInfo($intMaxRowCount, $intOffset = 0) {
			return new QQLimitInfo($intMaxRowCount, $intOffset);
		}

		static public function Distinct() {
			return new QQDistinct();
		}

		/////////////////////////
		// NamedValue QQ Node
		/////////////////////////
		static public function NamedValue($strName) {
			return new QQNamedValue($strName);
		}
	}

	abstract class QQSubQueryNode extends QQNode {
/*
		protected $strFunctionName;
		protected $objNode;
		protected $objCondition;

		public function __construct(QQNode $objNode, QQCondition $objCondition = null) {
			$this->objParentNode = $objNode->objParentNode;
			$this->strName = 'NAMEFOO';
			$this->strPropertyName = 'PROPERTYFOO';
			$this->strType = 'integer';
			if ($this->objParentNode) {
				if (version_compare(PHP_VERSION, '5.1.0') == -1)
					$this->strRootTableName = $objNode->objParentNode->__get('_RootTableName');
				else
					$this->strRootTableName = $objNode->objParentNode->_RootTableName;
			} else
				$this->strRootTableName = 'ROOTFOO';

			$this->objNode = $objNode;
			$this->objCondition = $objCondition;
		}
		public function GetColumnAlias($objBuilder) {
			if ($this->objCondition) {
				$strConditionClause = $this->objCondition->GetWhereClause($objBuilder->Database, $this->strRootTableName, true);
				$strFromClause = $this->objCondition->GetFromClause($objBuilder->Database, $this->strRootTableName);
				return sprintf('(SELECT %s(%s) FROM %s WHERE %s)',
					$this->strFunctionName,
					$this->objNode->GetColumnAlias($this->objCondition->GetPartialBuilder($objBuilder->Database, $this->strRootTableName)),
					$strFromClause,
					$strConditionClause);
			} else {
				return 'HERE';
			}
		}*/
	}

	class QQSubQueryCountNode extends QQSubQueryNode {
		protected $strFunctionName = 'COUNT';
	}

	class QQSubQuerySqlNode extends QQSubQueryNode {
		protected $strSql;
		protected $objParentQueryNodes;
		public function __construct($strSql, $objParentQueryNodes = null) {
			$this->objParentNode = true;
			$this->objParentQueryNodes = $objParentQueryNodes;
			$this->strSql = $strSql;
		}
		public function GetColumnAlias(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null) {
			$strSql = $this->strSql;
			for ($intIndex = 1; $intIndex < count($this->objParentQueryNodes); $intIndex++) {
				if (!is_null($this->objParentQueryNodes[$intIndex]))
					$strSql = str_replace('{' . $intIndex . '}', $this->objParentQueryNodes[$intIndex]->GetColumnAlias($objBuilder), $strSql);
			}
			return '(' . $strSql . ')';
		}
	}
	
	class QQVirtualNode extends QQNode {
		protected $strName;
		protected $objSubQueryDefinition;
		public function __construct($strName, QQSubQueryNode $objSubQueryDefinition = null) {
			$this->objParentNode = true;
			$this->strName = trim(strtolower($strName));
			$this->objSubQueryDefinition = $objSubQueryDefinition;
		}
		public function GetColumnAlias(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null) {
			if ($this->objSubQueryDefinition) {
				$objBuilder->SetVirtualNode($this->strName, $this->objSubQueryDefinition);
				return $this->objSubQueryDefinition->GetColumnAlias($objBuilder);
			} else {
				try {
					return $objBuilder->GetVirtualNode($this->strName)->GetColumnAlias($objBuilder);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
		public function GetAttributeName() {
			return $this->strName;
		}
	}

	abstract class QQClause extends QBaseClass {
		abstract public function UpdateQueryBuilder(QQueryBuilder $objBuilder);
		abstract public function __toString();
	}

	class QQOrderBy extends QQClause {
		protected $objNodeArray;
		protected function CollapseNodes($mixParameterArray) {
			$objNodeArray = array();
			foreach ($mixParameterArray as $mixParameter) {
				if (is_array($mixParameter))
					$objNodeArray = array_merge($objNodeArray, $mixParameter);
				else
					array_push($objNodeArray, $mixParameter);
			}

			$blnPreviousIsNode = false;
			foreach ($objNodeArray as $objNode)
				if (!($objNode instanceof QQNode)) {
					if (!$blnPreviousIsNode)
						throw new QCallerException('OrderBy clause parameters must all be QQNode objects followed by an optional true/false "Ascending Order" option', 3);
					$blnPreviousIsNode = false;
				} else {
					if ($objNode instanceof QQReverseReferenceNode)
						throw new QInvalidCastException('Cannot order by a ReverseReferenceNode: ' . $objNode->_Name, 4);
					if (!$objNode->_ParentNode)
						throw new QInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based QQNode', 4);	
					$blnPreviousIsNode = true;
				}

			if (count($objNodeArray))
				return $objNodeArray;
			else
				throw new QCallerException('No parameters passed in to OrderBy clause', 3);
		}
		public function __construct($mixParameterArray) {
			$this->objNodeArray = $this->CollapseNodes($mixParameterArray);
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$intLength = count($this->objNodeArray);
			for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
				$strOrderByCommand = $this->objNodeArray[$intIndex]->GetColumnAlias($objBuilder);

				// Check to see if they want a ASC/DESC declarator
				if ((($intIndex + 1) < $intLength) &&
					!($this->objNodeArray[$intIndex + 1] instanceof QQNode)) {
					if ((!$this->objNodeArray[$intIndex + 1]) ||
						(trim(strtolower($this->objNodeArray[$intIndex + 1])) == 'desc'))
						$strOrderByCommand .= ' DESC';
					else
						$strOrderByCommand .= ' ASC';
					$intIndex++;
				}

				$objBuilder->AddOrderByItem($strOrderByCommand);
			}
		}
		
		/**
		 * This is used primarly by datagrids wanting to use the "old Beta 2" style of
		 * Manual Queries.  This allows a datagrid to use QQ::OrderBy even though
		 * the manually-written Load method takes in Beta 2 string-based SortByCommand information.
		 *
		 * @return string
		 */
		public function GetAsManualSql() {
			$strOrderByArray = array();
			$intLength = count($this->objNodeArray);
			for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
				$strOrderByCommand = $this->objNodeArray[$intIndex]->GetAsManualSqlColumn();

				// Check to see if they want a ASC/DESC declarator
				if ((($intIndex + 1) < $intLength) &&
					!($this->objNodeArray[$intIndex + 1] instanceof QQNode)) {
					if ((!$this->objNodeArray[$intIndex + 1]) ||
						(trim(strtolower($this->objNodeArray[$intIndex + 1])) == 'desc'))
						$strOrderByCommand .= ' DESC';
					else
						$strOrderByCommand .= ' ASC';
					$intIndex++;
				}

				array_push($strOrderByArray, $strOrderByCommand);
			}
			
			return implode(',', $strOrderByArray);
		}

		public function __toString() {
			return 'QQOrderBy Clause';
		}
	}
	
	class QQDistinct extends QQClause {
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->SetDistinctFlag();
		}
		public function __toString() {
			return 'QQDistinct Clause';
		}
	}

	class QQLimitInfo extends QQClause {
		protected $intMaxRowCount;
		protected $intOffset;
		public function __construct($intMaxRowCount, $intOffset = 0) {
			try {
				$this->intMaxRowCount = QType::Cast($intMaxRowCount, QType::Integer);
				$this->intOffset = QType::Cast($intOffset, QType::Integer);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->intOffset)
				$objBuilder->SetLimitInfo($this->intOffset . ',' . $this->intMaxRowCount);
			else
				$objBuilder->SetLimitInfo($this->intMaxRowCount);
		}
		public function __toString() {
			return 'QQLimitInfo Clause';
		}
		
		public function __get($strName){
		
			switch ($strName){
					case 'MaxRowCount':
					return $this->intMaxRowCount;
					break;
					
					case 'Offset':
					return $this->intOffset;
					break;

				}
		}		
	}
	
	class QQExpandVirtualNode extends QQClause {
		protected $objNode;
		public function __construct(QQVirtualNode $objNode) {
			$this->objNode = $objNode;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			try {
				$objBuilder->AddSelectFunction(null, $this->objNode->GetColumnAlias($objBuilder), $this->objNode->GetAttributeName());
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		public function __toString() {
			return 'QQExpandVirtualNode Clause';
		}
	}
	class QQExpand extends QQClause {
		protected $objNode;
		protected $objJoinCondition;

		public function __construct($objNode, QQCondition $objJoinCondition = null) {
			// Check against root and table QQNodes
			if ($objNode instanceof QQAssociationNode)
				throw new QCallerException('Expand clause parameter cannot be the association table\'s QQNode, itself', 2);
			else if (!($objNode instanceof QQNode))
				throw new QCallerException('Expand clause parameter must be a QQNode object', 2);
			else if (!$objNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based QQNode', 3);

			$this->objNode = $objNode;
			$this->objJoinCondition = $objJoinCondition;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$this->objNode->GetColumnAlias($objBuilder, true, $this->objJoinCondition);
		}
		public function __toString() {
			return 'QQExpand Clause';
		}
	}

	abstract class QQAggregationClause extends QQClause {
		protected $objNode;
		protected $strAttributeName;
		protected $strFunctionName;
		public function __construct($objNode, $strAttributeName) {
			// Check against root and table QQNodes
			if ($objNode instanceof QQAssociationNode)
				throw new QCallerException('Expand clause parameter cannot be the association table\'s QQNode, itself', 2);
			else if (!($objNode instanceof QQNode))
				throw new QCallerException('Expand clause parameter must be a QQNode object', 2);
			else if (!$objNode->_ParentNode)
				throw new QInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based QQNode', 3);

			$this->objNode = $objNode;
			$this->strAttributeName = $strAttributeName;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddSelectFunction($this->strFunctionName, $this->objNode->GetColumnAlias($objBuilder), $this->strAttributeName);
		}
	}
	class QQCount extends QQAggregationClause {
		protected $strFunctionName = 'COUNT';
		public function __toString() {
			return 'QQCount Clause';
		}
	}
	class QQSum extends QQAggregationClause {
		protected $strFunctionName = 'SUM';
		public function __toString() {
			return 'QQSum Clause';
		}
	}
	class QQMinimum extends QQAggregationClause {
		protected $strFunctionName = 'MIN';
		public function __toString() {
			return 'QQMinimum Clause';
		}
	}
	class QQMaximum extends QQAggregationClause {
		protected $strFunctionName = 'MAX';
		public function __toString() {
			return 'QQMaximum Clause';
		}
	}
	class QQAverage extends QQAggregationClause {
		protected $strFunctionName = 'AVG';
		public function __toString() {
			return 'QQAverage Clause';
		}
	}

	class QQExpandAsArray extends QQClause {
		protected $objNode;
		public function __construct($objNode) {
			// Ensure that this is an QQAssociationNode
			if ((!($objNode instanceof QQAssociationNode)) && (!($objNode instanceof QQReverseReferenceNode)))
				throw new QCallerException('ExpandAsArray clause parameter must be an Association Table-based QQNode', 2);
				
			$this->objNode = $objNode;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->objNode instanceof QQAssociationNode)
				$this->objNode->_ChildTableNode->GetColumnAlias($objBuilder, true);
			else
				$this->objNode->GetColumnAlias($objBuilder, true);
			$objBuilder->AddExpandAsArrayNode($this->objNode);
		}
		public function __toString() {
			return 'QQExpandAsArray Clause';
		}
	}

	class QQGroupBy extends QQClause {
		protected $objNodeArray;
		protected function CollapseNodes($mixParameterArray) {
			$objNodeArray = array();
			foreach ($mixParameterArray as $mixParameter) {
				if (is_array($mixParameter))
					$objNodeArray = array_merge($objNodeArray, $mixParameter);
				else
					array_push($objNodeArray, $mixParameter);
			}

			$objFinalNodeArray = array();
			foreach ($objNodeArray as $objNode) {
				if ($objNode instanceof QQAssociationNode)
					throw new QCallerException('GroupBy clause parameter cannot be an association table\'s QQNode, itself', 3);
				else if (!($objNode instanceof QQNode))
					throw new QCallerException('GroupBy clause parameters must all be QQNode objects', 3);
				if (!$objNode->_ParentNode)
					throw new QInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based QQNode', 4);
				if ($objNode->_PrimaryKeyNode) {
					array_push($objFinalNodeArray, $objNode->_PrimaryKeyNode);
				} else
					array_push($objFinalNodeArray, $objNode);
			}

			if (count($objFinalNodeArray))
				return $objFinalNodeArray;
			else
				throw new QCallerException('No parameters passed in to Expand clause', 3);
		}
		public function __construct($mixParameterArray) {
			$this->objNodeArray = $this->CollapseNodes($mixParameterArray);
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$intLength = count($this->objNodeArray);
			for ($intIndex = 0; $intIndex < $intLength; $intIndex++)
				$objBuilder->AddGroupByItem($this->objNodeArray[$intIndex]->GetColumnAlias($objBuilder));
		}
		public function __toString() {
			return 'QQGroupBy Clause';
		}
	}

	// Users can use the QQuery or the shortcut "QQ"
	class QQuery extends QQ {}

	class QQueryBuilder extends QBaseClass {
		protected $strSelectArray;
		protected $strColumnAliasArray;
		protected $intColumnAliasCount = 0;
		protected $strTableAliasArray;
		protected $intTableAliasCount = 0;
		protected $strFromArray;
		protected $strJoinArray;
		protected $strJoinConditionArray;
		protected $strWhereArray;
		protected $strOrderByArray;
		protected $strGroupByArray;
		protected $objVirtualNodeArray;
		protected $strLimitInfo;
		protected $blnDistinctFlag;
		protected $strExpandAsArrayNodes;

		protected $blnCountOnlyFlag;

		protected $objDatabase;
		protected $strRootTableName;
		
		protected $strEscapeIdentifierBegin;
		protected $strEscapeIdentifierEnd;

		public function AddSelectItem($strTableName, $strColumnName, $strFullAlias) {
			$strTableAlias = $this->GetTableAlias($strTableName);

			if (!array_key_exists($strFullAlias, $this->strColumnAliasArray)) {
				$strColumnAlias = 'a' . $this->intColumnAliasCount++;
				$this->strColumnAliasArray[$strFullAlias] = $strColumnAlias;
			} else {
				$strColumnAlias = $this->strColumnAliasArray[$strFullAlias];
			}

			$this->strSelectArray[$strFullAlias] = sprintf('%s%s%s.%s%s%s AS %s%s%s',
				$this->strEscapeIdentifierBegin, $strTableAlias, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strColumnName, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strColumnAlias, $this->strEscapeIdentifierEnd);
		}
		
		public function AddSelectFunction($strFunctionName, $strColumnName, $strFullAlias) {
			$this->strSelectArray[$strFullAlias] = sprintf('%s(%s) AS %s__%s%s',
				$strFunctionName, $strColumnName,
				$this->strEscapeIdentifierBegin, $strFullAlias, $this->strEscapeIdentifierEnd);
		}

		public function AddFromItem($strTableName) {
			$strTableAlias = $this->GetTableAlias($strTableName);

			$this->strFromArray[$strTableName] = sprintf('%s%s%s AS %s%s%s',
				$this->strEscapeIdentifierBegin, $strTableName, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strTableAlias, $this->strEscapeIdentifierEnd);
		}

		public function GetTableAlias($strTableName) {
			if (!array_key_exists($strTableName, $this->strTableAliasArray)) {
				$strTableAlias = 't' . $this->intTableAliasCount++;
				$this->strTableAliasArray[$strTableName] = $strTableAlias;
				return $strTableAlias;
			} else {
				return $this->strTableAliasArray[$strTableName];
			}
		}

		public function AddJoinItem($strJoinTableName, $strJoinTableAlias, $strTableName, $strColumnName, $strLinkedColumnName, QQCondition $objJoinCondition = null) {
			$strJoinItem = sprintf('LEFT JOIN %s%s%s AS %s%s%s ON %s%s%s.%s%s%s = %s%s%s.%s%s%s',
				$this->strEscapeIdentifierBegin, $strJoinTableName, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $this->GetTableAlias($strJoinTableAlias), $this->strEscapeIdentifierEnd,

				$this->strEscapeIdentifierBegin, $this->GetTableAlias($strTableName), $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strColumnName, $this->strEscapeIdentifierEnd,

				$this->strEscapeIdentifierBegin, $this->GetTableAlias($strJoinTableAlias), $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strLinkedColumnName, $this->strEscapeIdentifierEnd);

				$strJoinIndex = $strJoinItem;
			try {
				$strConditionClause = null;
				if ($objJoinCondition &&
					($strConditionClause = $objJoinCondition->GetWhereClause($this, false)))
					$strJoinItem .= ' AND ' . $strConditionClause;
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			/* If this table has already been joined, then we need to check for the following:
				1. Condition wasn't specified before and we aren't specifying one now
					Do Nothing --b/c nothing was changed or updated
				2. Condition wasn't specified before but we ARE specifying one now
					Update the indexed item in the joinArray with the new JoinItem WITH Condition
				3. Condition WAS specified before but we aren't specifying one now
					Do Nothing -- we need to keep the old condition intact
				4. Condition WAS specified before and we are specifying the SAME one now
					Do Nothing --b/c nothing was changed or updated
				5. Condition WAS specified before and we are specifying a DIFFERENT one now
					Do Nothing -- we need to keep the old condition intact
					TODO: throw an excpetion of mismatched conditions -- but this could be too
					intensive from a code processing standpoint
			*/
			if (array_key_exists($strJoinIndex, $this->strJoinArray)) {
				// Case 1 and 2
				if (!array_key_exists($strJoinIndex, $this->strJoinConditionArray)) {
					
					// Case 1
					if (!$strConditionClause) {
						return;

					// Case 2
					} else {
						$this->strJoinArray[$strJoinIndex] = $strJoinItem;
						$this->strJoinConditionArray[$strJoinIndex] = $strConditionClause;
						return;
					}
				}

				// Case 3
				if (!$strConditionClause)
					return;

				// Case 4
				if ($strConditionClause == $this->strJoinConditionArray[$strJoinIndex])
					return;

				// Case 5
				throw new QCallerException('You have two different Join Conditions on the same Expanded Table: ' . $strJoinIndex . "\r\n[" . $this->strJoinConditionArray[$strJoinIndex] . ']   vs.   [' . $strConditionClause . ']');
			}

			// Create the new JoinItem in teh JoinArray
			$this->strJoinArray[$strJoinIndex] = $strJoinItem;

			// If there is a condition, record that condition against this JoinIndex
			if ($strConditionClause)
				$this->strJoinConditionArray[$strJoinIndex] = $strConditionClause;
		}

		public function AddJoinCustomItem($strJoinTableName, $strJoinTableAlias, QQCondition $objJoinCondition) {
			$strJoinItem = sprintf('LEFT JOIN %s%s%s AS %s%s%s ON ',
				$this->strEscapeIdentifierBegin, $strJoinTableName, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $this->GetTableAlias($strJoinTableAlias), $this->strEscapeIdentifierEnd
			);

			try {
				if (($strConditionClause = $objJoinCondition->GetWhereClause($this->objDatabase, $this->strRootTableName, true)))
					$strJoinItem .= ' AND ' . $strConditionClause;
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$this->strJoinArray[$strJoinItem] = $strJoinItem;
		}

		public function AddJoinCustomSqlItem($strSql) {
			$this->strJoinArray[$strSql] = $strSql;
		}

		public function AddWhereItem($strItem) {
			array_push($this->strWhereArray, $strItem);
		}

		public function AddOrderByItem($strItem) {
			array_push($this->strOrderByArray, $strItem);
		}

		public function AddGroupByItem($strItem) {
			array_push($this->strGroupByArray, $strItem);
		}

		public function SetLimitInfo($strLimitInfo) {
			$this->strLimitInfo = $strLimitInfo;
		}
		
		public function SetDistinctFlag() {
			$this->blnDistinctFlag = true;
		}
		
		public function SetCountOnlyFlag() {
			$this->blnCountOnlyFlag = true;
		}
		
		public function SetVirtualNode($strName, QQSubQueryNode $objNode) {
			$this->objVirtualNodeArray[trim(strtolower($strName))] = $objNode;
		}
		
		public function GetVirtualNode($strName) {
			$strName = trim(strtolower($strName));
			if (array_key_exists($strName, $this->objVirtualNodeArray))
				return $this->objVirtualNodeArray[$strName];
			else throw new QCallerException('Undefined Virtual Node: ' . $strName);
		}

		public function AddExpandAsArrayNode($objNode) {
			$this->strExpandAsArrayNodes[$objNode->GetExpandArrayAlias()] = true;
		}

		public function __construct(QDatabaseBase $objDatabase, $strRootTableName) {
			$this->objDatabase = $objDatabase;
			$this->strEscapeIdentifierBegin = $objDatabase->EscapeIdentifierBegin;
			$this->strEscapeIdentifierEnd = $objDatabase->EscapeIdentifierEnd;
			$this->strRootTableName = $strRootTableName;

			$this->strSelectArray = array();
			$this->strColumnAliasArray = array();
			$this->strTableAliasArray = array();
			$this->strFromArray = array();
			$this->strJoinArray = array();
			$this->strJoinConditionArray = array();
			$this->strWhereArray = array();
			$this->strOrderByArray = array();
			$this->strGroupByArray = array();
			$this->objVirtualNodeArray = array();

			$this->strExpandAsArrayNodes = array();
		}
		
		public function GetStatement() {
			// SELECT Clause
			if ($this->blnCountOnlyFlag) {
				if ($this->blnDistinctFlag) {
					$strSql = "SELECT\r\n    COUNT(*) AS q_row_count\r\n" .
						"FROM    (SELECT DISTINCT ";
					$strSql .= "    " . implode(",\r\n    ", $this->strSelectArray);
				} else
					$strSql = "SELECT\r\n    COUNT(*) AS q_row_count\r\n";
			} else {
				if ($this->blnDistinctFlag)
					$strSql = "SELECT DISTINCT\r\n";
				else
					$strSql = "SELECT\r\n";
				if ($this->strLimitInfo)
					$strSql .= $this->objDatabase->SqlLimitVariablePrefix($this->strLimitInfo) . "\r\n";
				$strSql .= "    " . implode(",\r\n    ", $this->strSelectArray);
			}

			// FROM and JOIN Clauses
			$strSql .= sprintf("\r\nFROM\r\n    %s\r\n    %s",
				implode(",\r\n    ", $this->strFromArray),
				implode("\r\n    ", $this->strJoinArray));

			// WHERE Clause
			if (count($this->strWhereArray)) {
				$strWhere = implode("\r\n    ", $this->strWhereArray);
				if (trim($strWhere) != '1=1')
					$strSql .= "\r\nWHERE\r\n    " . $strWhere;
			}

			// Additional Ordering/Grouping clauses
			if (count($this->strGroupByArray))
				$strSql .= "\r\nGROUP BY\r\n    " . implode(",\r\n    ", $this->strGroupByArray);
			if (count($this->strOrderByArray))
				$strSql .= "\r\nORDER BY\r\n    " . implode(",\r\n    ", $this->strOrderByArray);

			// Limit Suffix (if applicable)
			if ($this->strLimitInfo)
				$strSql .= "\r\n" . $this->objDatabase->SqlLimitVariableSuffix($this->strLimitInfo);

			// For Distinct Count Queries
			if ($this->blnCountOnlyFlag && $this->blnDistinctFlag)
				$strSql .= "\r\n) as q_count_table";

			return $strSql;
		}

		

		public function __get($strName) {
			switch ($strName) {
				case 'Database':
					return $this->objDatabase;
				case 'RootTableName':
					return $this->strRootTableName;
				case 'ColumnAliasArray':
					return $this->strColumnAliasArray;
				case 'ExpandAsArrayNodes':
					if (count($this->strExpandAsArrayNodes))
						return $this->strExpandAsArrayNodes;
					else
						return null;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	/**
	 * 	Subclasses QQueryBuilder to handle the building of conditions for conditional expansions, subqueries, etc.
	 * 	Since regular queries use WhereClauses for conditions, we just use the where clause portion, and
	 * 	only build a condition clause appropriate for a conditional expansion.
	 */	
	class QPartialQueryBuilder extends QQueryBuilder {
		protected $objParentBuilder;
		public function __construct(QQueryBuilder $objBuilder) {
			parent::__construct($objBuilder->objDatabase, $objBuilder->strRootTableName);
			$this->objParentBuilder = $objBuilder;
			$this->strColumnAliasArray = &$objBuilder->strColumnAliasArray;
			$this->strTableAliasArray = &$objBuilder->strTableAliasArray;
		}
		public function GetWhereStatement() {
			return implode(' ', $this->strWhereArray);
		}
		public function GetFromStatement() {
			return implode(' ', $this->strFromArray) . ' ' . implode(' ', $this->strJoinArray);
		}
	}
?>