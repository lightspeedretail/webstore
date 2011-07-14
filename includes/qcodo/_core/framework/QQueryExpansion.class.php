<?php
	/**
	 * Utility object used by the Code Generator and the generated data objects
	 * to assist with Early Binding of referenced objects for Manual Queries (e.g. Beta 2-style Queries)
	 * 
	 * This class will only be used/included when codegenned with <manualQuery support="true"/> in the codegen settings.
	 * It is rare for this to be used manually.
	 */
	class QQueryExpansion extends QBaseClass {
		protected $strSelectArray;
		protected $strFromArray;
		protected $strWhereArray;

		public function __construct($strClassName = null, $strParentAlias = null, $objExpansionMap = null) {
			$this->strSelectArray = array();
			$this->strFromArray = array();
			$this->strWhereArray = array();
			
			if ($strClassName) {
				try {
					call_user_func(array($strClassName, 'ExpandQuery'), $strParentAlias, null, $objExpansionMap, $this);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}

		public function AddSelectItem($strItem) {
			array_push($this->strSelectArray, $strItem);
		}

		public function AddFromItem($strItem) {
			array_push($this->strFromArray, $strItem);
		}

		public function AddWhereItem($strItem) {
			array_push($this->strWhereArray, $strItem);
		}

		public function GetSelectSql($strPrefix = ",\n					", $strGlue = ",\n					") {
			if (count($this->strSelectArray) > 0) {
				return $strPrefix . implode($strGlue, $this->strSelectArray);
			} else {
				return '';
			}
		}

		public function GetFromSql($strPrefix = '', $strGlue = "\n					") {
			if (count($this->strFromArray) > 0) {
				return $strPrefix . implode($strGlue, $this->strFromArray);
			} else {
				return '';
			}
		}

		public function GetWhereSql($strPrefix, $strGlue) {
			if (count($this->strWhereArray) > 0) {
				return $strPrefix . implode($strGlue, $this->strWhereArray);
			} else {
				return '';
			}
		}
	}
?>